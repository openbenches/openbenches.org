<?php
namespace Commentics;

class ToolTextFinderModel extends Model
{
    public function search($data)
    {
        $results = array();

        $lang_text_result = $this->loadWord('tool/text_finder', 'lang_text_result');

        $text = $this->security->decode($data['text']);

        $text = str_replace("'", "\'", $text);

        if ($data['location'] == 'backend') {
            $path = CMTX_DIR_VIEW . 'default/language/' . $this->setting->get('language_backend') . '/';
        } else {
            $path = CMTX_DIR_ROOT . 'frontend/view/default/language/' . $this->setting->get('language_frontend') . '/';
        }

        $directory = new \RecursiveDirectoryIterator($path, \FilesystemIterator::UNIX_PATHS);

        $iterator = new \RecursiveIteratorIterator($directory);

        $matches = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        foreach ($matches as $match) {
            $file = file($match[0]);

            foreach ($file as $line_number => $line) {
                $line_number++;

                $line = trim(preg_replace('/\s+/', ' ', $line));

                $line_to_search = $this->variable->strstr($line, '= \'');

                if (($data['case'] == 'sensitive' && $this->variable->strpos($line_to_search, $text) !== false) || ($data['case'] == 'insensitive' && $this->variable->stripos($line_to_search, $text) !== false)) {
                    $results[] = '<p>' . sprintf($lang_text_result, $line_number, $match[0]) . '<br><code>' . $this->security->encode($line) . '</code></p>';
                }
            }
        }

        return $results;
    }
}
