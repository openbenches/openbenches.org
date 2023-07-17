<?php
namespace Commentics;

class ModuleLanguageEditorModel extends Model
{
    private $exclude = array(
        'lang_title_digg',
        'lang_title_facebook',
        'lang_title_linkedin',
        'lang_title_reddit',
        'lang_title_twitter',
        'lang_title_weibo',
        'lang_text_powered_by'
    );

    public function getText()
    {
        $results = array();

        $directory1 = $this->getDirectoryIterator(CMTX_DIR_ROOT . 'frontend/view/default/language/' . $this->setting->get('language_frontend') . '/');

        $directory2 = $this->getDirectoryIterator(CMTX_DIR_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/language/' . $this->setting->get('language_frontend') . '/');

        $iterator = new \AppendIterator();

        if (!empty($directory1)) {
            $iterator->append(new \RecursiveIteratorIterator($directory1));
        }

        if (!empty($directory2)) {
            $iterator->append(new \RecursiveIteratorIterator($directory2));
        }

        $matches = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        $custom_file = '';

        foreach ($matches as $match) {
            if (substr($match[0], -10) == 'custom.php') {
                $custom_file = file($match[0]);
            } else {
                $file = file($match[0]);

                foreach ($file as $line_number => $line) {
                    $line_number++;

                    $line = trim(preg_replace('/\s+/', ' ', $line));

                    if ($line && substr($line, 0, 2) != '//' && $line != '<?php') {
                        $parts = explode('$_[\'', $line);
                        $parts = explode('\'] = \'', $parts[1]);
                        $parts[1] = $this->variable->substr($parts[1], 0, -2);
                        $parts[1] = str_replace("\'", "'", $parts[1]);

                        if (!in_array($parts[0], $this->exclude)) {
                            $results[$parts[0]] = array(
                                'key'  => $parts[0],
                                'text' => $this->security->encode($parts[1])
                            );
                        }
                    }
                }
            }
        }

        if ($custom_file) {
            foreach ($custom_file as $line_number => $line) {
                $line_number++;

                $line = trim(preg_replace('/\s+/', ' ', $line));

                if ($line && substr($line, 0, 2) != '//' && $line != '<?php') {
                    $parts = explode('$_[\'', $line);
                    $parts = explode('\'] = \'', $parts[1]);
                    $parts[1] = $this->variable->substr($parts[1], 0, -2);
                    $parts[1] = str_replace("\'", "'", $parts[1]);

                    if (!in_array($parts[0], $this->exclude)) {
                        if (empty($results[$parts[0]])) {
                            $results[$parts[0]] = array(
                                'key'    => $parts[0],
                                'text'   => $this->security->encode($parts[1]),
                                'custom' => $this->security->encode($parts[1])
                            );
                        } else {
                            $results[$parts[0]]['custom'] = $this->security->encode($parts[1]);
                        }
                    }
                }
            }
        }

        return $results;
    }

    private function getDirectoryIterator($path)
    {
        if (file_exists($path)) {
            $directory_iterator = new \RecursiveDirectoryIterator($path, \FilesystemIterator::UNIX_PATHS);
        } else {
            $directory_iterator = false;
        }

        return $directory_iterator;
    }

    public function update($data)
    {
        $language_file = CMTX_DIR_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/language/' . $this->setting->get('language_frontend') . '/custom.php';

        $directory = dirname($language_file);

        if (!is_dir($directory)) {
            @mkdir($directory, 0777, true);
        }

        file_put_contents($language_file, '<?php' . "\r\n");

        $handle = fopen($language_file, 'a');

        $ignore = array('csrf_key');

        foreach ($data as $key => $value) {
            if (!in_array($key, $ignore) && trim($value)) {
                $value = $this->security->decode($value);

                $value = str_replace("'", "\\'", $value);

                fputs($handle, '$_[\'' . $key . '\'] = \'' . $value . '\';' . "\r\n");
            }
        }

        fclose($handle);
    }
}
