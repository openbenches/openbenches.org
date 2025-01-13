<?php
namespace Commentics;

class Modification
{
    private $db;
    private $log;
    private $setting;

    public function __construct($registry)
    {
        $this->db      = $registry->get('db');
        $this->log     = $registry->get('log');
        $this->setting = $registry->get('setting');

        if (!$this->db->isConnected() || !$this->db->isInstalled()) {
            return;
        }

        // Only process modifications if they exist and there's no cache
        if ($this->hasModificationFiles() && $this->isModificationCacheEmpty()) {
            // Set the log's filename
            $this->log->setFilename('modification');

            // Clear the log
            $this->log->clear();

            // Initialise logging variable
            $log_data = array();

            // Clear modification cache
            remove_directory(CMTX_DIR_CACHE . 'modification/', false, false);

            $xmls = array();

            $files = glob(CMTX_DIR_MODIFICATION . '*.xml');

            if ($files) {
                foreach ($files as $file) {
                    $xmls[] = file_get_contents($file);
                }
            }

            // Don't run if server does not support 'DOM' extension
            if ($xmls && !extension_loaded('dom')) {
                $this->log->write('DOM extension not loaded');

                return;
            }

            $modification = array();

            foreach ($xmls as $xml) {
                if (empty($xml)) {
                    continue;
                }

                $dom = new \DOMDocument('1.0', 'UTF-8');

                $dom->preserveWhiteSpace = false;

                $dom->loadXml($xml);

                // Log
                $log_info = array();

                $log_info[] = 'NAME: ' . $dom->getElementsByTagName('name')->item(0)->textContent;

                // Wipe the past modification from the backup array
                $recovery = array();

                // Set a recovery of the modification code in case we need to use it if an abort attribute is used
                if (isset($modification)) {
                    $recovery = $modification;
                }

                $files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');

                foreach ($files as $file) {
                    $operations = $file->getElementsByTagName('operation');

                    $files = explode('|', $file->getAttribute('path'));

                    foreach ($files as $file) {
                        $path = '';

                        /* Get the full path of the files that are going to be used for modification */
                        if ((substr($file, 0, 8) == 'frontend')) {
                            $path = CMTX_DIR_ROOT . 'frontend/' . substr($file, 9);
                        }

                        if ((substr($file, 0, 7) == 'backend')) {
                            $path = CMTX_DIR_ROOT . $this->setting->get('backend_folder') . substr($file, 7);
                        }

                        if ((substr($file, 0, 6) == 'system')) {
                            $path = CMTX_DIR_ROOT . 'system/' . substr($file, 7);
                        }

                        if ($path) {
                            $files = glob($path, GLOB_BRACE);

                            if ($files) {
                                foreach ($files as $file) {
                                    $key = '';

                                    // Get the key to be used for the modification cache filename
                                    if (substr($file, 0, strlen(CMTX_DIR_ROOT . 'frontend')) == CMTX_DIR_ROOT . 'frontend') {
                                        $key = 'frontend' . substr($file, strlen(CMTX_DIR_ROOT . 'frontend'));
                                    }

                                    if (substr($file, 0, strlen(CMTX_DIR_ROOT . $this->setting->get('backend_folder'))) == CMTX_DIR_ROOT . $this->setting->get('backend_folder')) {
                                        $key = $this->setting->get('backend_folder') . substr($file, strlen(CMTX_DIR_ROOT . $this->setting->get('backend_folder')));
                                    }

                                    if (substr($file, 0, strlen(CMTX_DIR_ROOT . 'system')) == CMTX_DIR_ROOT . 'system') {
                                        $key = 'system' . substr($file, strlen(CMTX_DIR_ROOT . 'system'));
                                    }

                                    // If the file's content is not already in the modification array we need to add it
                                    if (!isset($modification[$key])) {
                                        $content = file_get_contents($file);

                                        $modification[$key] = preg_replace('~\r?\n~', "\n", $content);

                                        $original[$key] = preg_replace('~\r?\n~', "\n", $content);

                                        // Log
                                        $log_info[] = 'FILE: ' . $key;
                                    }

                                    foreach ($operations as $operation) {
                                        $error = $operation->getAttribute('error');

                                        // Ignoreif
                                        $ignoreif = $operation->getElementsByTagName('ignoreif')->item(0);

                                        if ($ignoreif) {
                                            if ($ignoreif->getAttribute('regex') != 'true') {
                                                if (strpos($modification[$key], trim($ignoreif->textContent)) !== false) {
                                                    continue;
                                                }
                                            } else {
                                                if (preg_match(trim($ignoreif->textContent), $modification[$key])) {
                                                    continue;
                                                }
                                            }
                                        }

                                        $status = false;

                                        // Search and replace
                                        if ($operation->getElementsByTagName('search')->item(0)->getAttribute('regex') != 'true') {
                                            // Search
                                            $search = $operation->getElementsByTagName('search')->item(0)->textContent;
                                            $trim = $operation->getElementsByTagName('search')->item(0)->getAttribute('trim');
                                            $index = $operation->getElementsByTagName('search')->item(0)->getAttribute('index');

                                            // Trim line if no trim attribute is set or is set to true
                                            if (!$trim || $trim == 'true') {
                                                $search = trim($search);
                                            }

                                            // Add
                                            $add = $operation->getElementsByTagName('add')->item(0)->textContent;
                                            $trim = $operation->getElementsByTagName('add')->item(0)->getAttribute('trim');
                                            $position = $operation->getElementsByTagName('add')->item(0)->getAttribute('position');
                                            $offset = $operation->getElementsByTagName('add')->item(0)->getAttribute('offset');

                                            if ($offset == '') {
                                                $offset = 0;
                                            }

                                            // Trim line if no trim attribute is set or is set to true
                                            if (!$trim || $trim == 'true') {
                                                $add = trim($add);
                                            }

                                            // Log
                                            $log_info[] = 'SEARCH: ' . $search;

                                            // Check if using indexes
                                            if ($index !== '') {
                                                $indexes = explode(',', $index);
                                            } else {
                                                $indexes = array();
                                            }

                                            // Get all of the matches
                                            $i = 0;

                                            $lines = explode("\n", $modification[$key]);

                                            for ($line_id = 0; $line_id < count($lines); $line_id++) {
                                                $line = $lines[$line_id];

                                                // Status
                                                $match = false;

                                                // Check to see if the line matches the search code
                                                if (stripos($line, $search) !== false) {
                                                    // If indexes are not used then just set the found status to true
                                                    if (!$indexes) {
                                                        $match = true;
                                                    } else if (in_array($i, $indexes)) {
                                                        $match = true;
                                                    }

                                                    $i++;
                                                }

                                                // Now for replacing or adding to the matched elements
                                                if ($match) {
                                                    switch ($position) {
                                                        default:
                                                        case 'replace':
                                                            $new_lines = explode("\n", $add);

                                                            if ($offset < 0) {
                                                                array_splice($lines, $line_id + $offset, abs($offset) + 1, array(str_replace($search, $add, $line)));

                                                                $line_id -= $offset;
                                                            } else {
                                                                array_splice($lines, $line_id, $offset + 1, array(str_replace($search, $add, $line)));
                                                            }

                                                            break;
                                                        case 'before':
                                                            $new_lines = explode("\n", $add);

                                                            array_splice($lines, $line_id - $offset, 0, $new_lines);

                                                            $line_id += count($new_lines);

                                                            break;
                                                        case 'after':
                                                            $new_lines = explode("\n", $add);

                                                            array_splice($lines, ($line_id + 1) + $offset, 0, $new_lines);

                                                            $line_id += count($new_lines);

                                                            break;
                                                    }

                                                    // Log
                                                    $log_info[] = 'LINE: ' . $line_id;

                                                    $status = true;
                                                }
                                            }

                                            $modification[$key] = implode("\n", $lines);
                                        } else {
                                            $search = trim($operation->getElementsByTagName('search')->item(0)->textContent);
                                            $limit = $operation->getElementsByTagName('search')->item(0)->getAttribute('limit');
                                            $replace = trim($operation->getElementsByTagName('add')->item(0)->textContent);

                                            // Limit
                                            if (!$limit) {
                                                $limit = -1;
                                            }

                                            // Log
                                            $match = array();

                                            preg_match_all($search, $modification[$key], $match, PREG_OFFSET_CAPTURE);

                                            // Remove part of the the result if a limit is set
                                            if ($limit > 0) {
                                                $match[0] = array_slice($match[0], 0, $limit);
                                            }

                                            if ($match[0]) {
                                                $log_info[] = 'REGEX: ' . $search;

                                                for ($i = 0; $i < count($match[0]); $i++) {
                                                    $log_info[] = 'LINE: ' . (substr_count(substr($modification[$key], 0, $match[0][$i][1]), "\n") + 1);
                                                }

                                                $status = true;
                                            }

                                            // Make the modification
                                            $modification[$key] = preg_replace($search, $replace, $modification[$key], $limit);
                                        }

                                        if (!$status) {
                                            if ($error == 'abort') { // Abort applying this modification completely
                                                $modification = $recovery;

                                                $log_info[] = 'ERROR: SEARCH NOT FOUND. OPERATION ABORTED.';

                                                $log_data[] = $log_info;
                                                break 5;
                                            } else if ($error == 'skip') { // Skip current operation or break
                                                $log_info[] = 'ERROR: SEARCH NOT FOUND. OPERATION SKIPPED.';

                                                $log_data[] = $log_info;

                                                continue;
                                            } else { // Break current operations
                                                $log_info[] = 'ERROR: SEARCH NOT FOUND. ALL OPERATIONS ABORTED.';

                                                $log_data[] = $log_info;

                                                break;
                                            }
                                        }

                                        $log_info = array();
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($log_data) {
                foreach ($log_data as $data) {
                    $this->log->write(implode("\r\n", $data) . "\r\n");
                }
            }

            if (!is_dir(CMTX_DIR_CACHE . 'modification/')) {
                @mkdir(CMTX_DIR_CACHE . 'modification/', 0777);
            }

            /* Write all modification files */
            foreach ($modification as $key => $value) {
                if ($original[$key] != $value) { // Only create a file if there are changes
                    $path = '';

                    $directories = explode('/', dirname($key));

                    foreach ($directories as $directory) {
                        $path = $path . '/' . $directory;

                        if (!is_dir(CMTX_DIR_CACHE . 'modification/' . $path)) {
                            @mkdir(CMTX_DIR_CACHE . 'modification/' . $path, 0777);
                        }
                    }

                    $handle = @fopen(CMTX_DIR_CACHE . 'modification/' . $key, 'w');

                    if ($handle) {
                        fwrite($handle, $value);

                        fclose($handle);
                    }
                }
            }
        }
    }

    private function hasModificationFiles()
    {
        $files = glob(CMTX_DIR_MODIFICATION . '*.xml');

        if ($files) {
            return true;
        } else {
            return false;
        }
    }

    private function isModificationCacheEmpty()
    {
        $files = glob(CMTX_DIR_CACHE . 'modification/*', GLOB_ONLYDIR);

        if (!$files) {
            return true;
        } else {
            return false;
        }
    }
}
