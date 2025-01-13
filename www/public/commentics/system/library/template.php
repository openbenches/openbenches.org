<?php
namespace Commentics;

class Template
{
    private $code;
    private $minify = false;

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setMinify($minify)
    {
        $this->minify = $minify;
    }

    public function parse()
    {
        $this->removeComment();

        $this->loadTemplate();

        $this->echoVariableQuote();

        $this->echoVariable();

        $this->setVariable();

        $this->parseIf();
        $this->parseElseIf();
        $this->parseElse();
        $this->parseEndIf();

        $this->parseForEach();
        $this->parseEndForEach();

        $this->startCount();
        $this->increaseCount();
        $this->decreaseCount();

        if ($this->minify) {
            $this->minify();
        }

        $this->code = trim($this->code);

        return $this->code;
    }

    /* Remove comment e.g. {# This is a comment #} */
    private function removeComment()
    {
        $this->code = preg_replace('/.*{#.*?#}([\r\n])/', '', $this->code);
    }

    /* Parse loading of template e.g. @template main/comment */
    private function loadTemplate()
    {
        if (preg_match_all('/@template (.*)[\r\n]/', $this->code, $matches)) {
            foreach ($matches[0] as $index => $tag) {
                $template = trim($matches[1][$index]);

                if ($template == 'field/{{ field.template }}') {
                    $this->code = str_replace($tag, "<?php require(\$this->loadTemplate('field/' . \$field['template'])); ?>" . PHP_EOL, $this->code);
                } else {
                    $this->code = str_replace($tag, "<?php require(\$this->loadTemplate('$template')); ?>" . PHP_EOL, $this->code);
                }
            }
        }
    }

    /* Echo variable surrounded by quotes e.g. title="{{ var }}" */
    private function echoVariableQuote()
    {
        if (preg_match_all('/"{{ (.*?) }}"/', $this->code, $matches)) {
            foreach ($matches[0] as $index => $tag) {
                $vars = explode('.', $matches[1][$index]);

                if (count($vars) == 1) {
                    /* Only encode double quotes for language strings */
                    if (substr($vars[0], 0, 5) == 'lang_') {
                        $this->code = str_replace($tag, "\"<?php echo \$this->variable->encodeDouble(\$$vars[0]); ?>\"", $this->code);
                    } else {
                        $this->code = str_replace($tag, "\"<?php echo \$$vars[0]; ?>\"", $this->code);
                    }
                }

                if (count($vars) == 2) {
                    $this->code = str_replace($tag, "\"<?php echo \$$vars[0]['$vars[1]']; ?>\"", $this->code);
                }
            }
        }
    }

    /* Echo variable e.g. {{ var }} */
    private function echoVariable()
    {
        if (preg_match_all('/{{ (.*?) }}/', $this->code, $matches)) {
            foreach ($matches[0] as $index => $tag) {
                $vars = explode('.', $matches[1][$index]);

                if (count($vars) == 1) {
                    $this->code = str_replace($tag, "<?php echo \$$vars[0]; ?>", $this->code);
                }

                if (count($vars) == 2) {
                    $this->code = str_replace($tag, "<?php echo \$$vars[0]['$vars[1]']; ?>", $this->code);
                }
            }
        }
    }

    /* Set variable e.g. @set reply_depth = 0  */
    private function setVariable()
    {
        if (preg_match_all('/@set ([\p{L}0-9_]+) = (.*)/', $this->code, $matches)) {
            foreach ($matches[0] as $index => $tag) {
                $variable = trim($matches[1][$index]);

                $value = trim($matches[2][$index]);

                $this->code = str_replace($tag, "<?php \$$variable = $value; ?>", $this->code);
            }
        }
    }

    /* Parse if statement e.g. @if show_flag */
    private function parseIf()
    {
        if (preg_match_all('/@if (.*)[\r\n]/', $this->code, $matches)) {
            $this->parseIfAndElseIf('if', $matches);
        }
    }

    /* Parse elseif statement e.g. @elseif show_flag */
    private function parseElseIf()
    {
        if (preg_match_all('/@elseif (.*)[\r\n]/', $this->code, $matches)) {
            $this->parseIfAndElseIf('elseif', $matches);
        }
    }

    /* Carry out either the if or elseif parsing */
    private function parseIfAndElseIf($type, $matches)
    {
        foreach ($matches[0] as $index => $tag) {
            $content = trim($matches[1][$index]);

            $replacements = array(
                ' equals '       => ' == ',
                ' not equal to ' => ' != ',
                ' more than '    => ' > ',
                ' less than '    => ' < ',
                ' and '          => ' && ',
                ' or '           => ' || '
            );

            $content = strtr($content, $replacements);

            $words = explode(' ', $content);

            $parsed = '';

            $ignore = array('true', 'false', 'no');

            foreach ($words as $word) {
                if (in_array($word, $ignore)) {
                    // let it through
                } else if (substr($word, 0, 1) == "'") {
                    // let it through
                } else if (is_numeric($word)) {
                    // let it through
                } else if (preg_match('/[\p{L}0-9_]+/', $word)) { // variable
                    $vars = explode('.', $word);

                    if (count($vars) == 1) {
                        $word = "\$$vars[0]";
                    }

                    if (count($vars) == 2) {
                        $word = "\$$vars[0]['$vars[1]']";
                    }
                }

                $parsed .= $word . ' ';
            }

            $parsed = str_replace(' no ', ' !', $parsed);

            $parsed = trim($parsed);

            $this->code = str_replace($tag, "<?php $type ($parsed): ?>" . PHP_EOL, $this->code);
        }
    }

    /* Parse else e.g. @else */
    private function parseElse()
    {
        $this->code = str_replace('@else', '<?php else: ?>', $this->code);
    }

    /* Parse endif e.g. @endif */
    private function parseEndIf()
    {
        $this->code = str_replace('@endif', '<?php endif; ?>', $this->code);
    }

    /* Parse foreach e.g. @foreach comments as comment */
    private function parseForEach()
    {
        if (preg_match_all('/@foreach (.*)[\r\n]/', $this->code, $matches)) {
            foreach ($matches[0] as $index => $tag) {
                $content = trim($matches[1][$index]);

                $replacements = array(
                    'and' => '=>'
                );

                $content = strtr($content, $replacements);

                $words = explode(' ', $content);

                $parsed = '';

                $ignore = array('as');

                foreach ($words as $word) {
                    if (in_array($word, $ignore)) {
                        // let it through
                    } else if (preg_match('/[\p{L}0-9_]+/', $word)) {
                        $vars = explode('.', $word);

                        if (count($vars) == 1) {
                            $word = "\$$vars[0]";
                        }

                        if (count($vars) == 2) {
                            $word = "\$$vars[0]['$vars[1]']";
                        }
                    }

                    $parsed .= $word . ' ';
                }

                $parsed = trim($parsed);

                $this->code = str_replace($tag, "<?php foreach ($parsed): ?>" . PHP_EOL, $this->code);
            }
        }
    }

    /* Parse endforeach e.g. @endforeach */
    private function parseEndForEach()
    {
        $this->code = str_replace('@endforeach', '<?php endforeach; ?>', $this->code);
    }

    /* Parse start of count e.g. @start count at 1 */
    private function startCount()
    {
        if (preg_match_all('/@start count at ([0-9]+)[\r\n]/', $this->code, $matches)) {
            foreach ($matches[0] as $index => $tag) {
                $content = trim($matches[1][$index]);

                $this->code = str_replace($tag, "<?php \$count = $content; ?>", $this->code);
            }
        }
    }

    /* Parse increase of count e.g. @increase count */
    private function increaseCount()
    {
        $this->code = str_replace('@increase count', '<?php $count++; ?>', $this->code);
    }

    /* Parse decrease of count e.g. @decrease count */
    private function decreaseCount()
    {
        $this->code = str_replace('@decrease count', '<?php $count--; ?>', $this->code);
    }

    /* Minify HTML */
    private function minify()
    {
        $this->code = preg_replace('/(\s){2,}/s', '', $this->code);
    }
}
