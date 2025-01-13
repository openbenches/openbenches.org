<?php
namespace Commentics;

class Variable
{
    /* Uppercases the first character of each word in the string */
    public function fixCase($value)
    {
        if (function_exists('mb_convert_case') && is_callable('mb_convert_case')) {
            if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
                $value = mb_convert_case($value, MB_CASE_TITLE_SIMPLE, 'UTF-8');
            } else {
                $value = mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
            }
        } else {
            $value = ucwords($this->strtolower($value));
        }

        $replacements = array(
            '&Amp;'   => '&amp;',
            ' And '   => ' and ',
            ' Of '    => ' of ',
            '&#039;S' => '&#039;s',
            '\'S'     => '\'s',
        );

        $value = str_replace(array_keys($replacements), $replacements, $value);

        return $value;
    }

    /* Lowercases every letter in the string */
    public function strtolower($value)
    {
        if (function_exists('mb_strtolower') && is_callable('mb_strtolower')) {
            return mb_strtolower($value, 'UTF-8');
        } else {
            return strtolower($value);
        }
    }

    /* Uppercases every letter in the string */
    public function strtoupper($value)
    {
        if (function_exists('mb_strtoupper') && is_callable('mb_strtoupper')) {
            return mb_strtoupper($value, 'UTF-8');
        } else {
            return strtoupper($value);
        }
    }

    /* Gets the position of the needle in the haystack */
    public function strpos($haystack, $needle, $offset = 0)
    {
        if (function_exists('mb_strpos') && is_callable('mb_strpos')) {
            return mb_strpos($haystack, $needle, $offset, 'UTF-8');
        } else {
            return strpos($haystack, $needle, $offset);
        }
    }

    /* Case insensitive version of stringPosition() */
    public function stripos($haystack, $needle, $offset = 0)
    {
        if (function_exists('mb_stripos') && is_callable('mb_stripos')) {
            return mb_stripos($haystack, $needle, $offset, 'UTF-8');
        } else {
            return stripos($haystack, $needle, $offset);
        }
    }

    /* Gets the portion of string after the specified needle */
    public function strstr($haystack, $needle, $before_needle = false)
    {
        if (function_exists('mb_strstr') && is_callable('mb_strstr')) {
            return mb_strstr($haystack, $needle, $before_needle, 'UTF-8');
        } else {
            return strstr($haystack, $needle, $before_needle);
        }
    }

    /* Case insensitive version of stringString() */
    public function stristr($haystack, $needle, $before_needle = false)
    {
        if (function_exists('mb_stristr') && is_callable('mb_stristr')) {
            return mb_stristr($haystack, $needle, $before_needle, 'UTF-8');
        } else {
            return stristr($haystack, $needle, $before_needle);
        }
    }

    /* Gets the portion of string between the start and length parameters */
    public function substr($value, $start, $length)
    {
        if (function_exists('mb_substr') && is_callable('mb_substr')) {
            return mb_substr($value, $start, $length, 'UTF-8');
        } else {
            return substr($value, $start, $length);
        }
    }

    /* Gets a random string of mixed case letters and numbers */
    public function random($length = 20, $friendly = false)
    {
        if ($friendly) {
            $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // omits characters I/1 and O/0
        } else {
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        }

        $key = '';

        for ($i = 0; $i < $length; $i++) {
            $key .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $key;
    }

    public function hint($hint)
    {
        $hint = str_replace("\"", "&quot;", $hint); // replace " with &quot;

        return $hint;
    }

    public function escapeSingle($text)
    {
        $text = str_replace("'", "\'", $text); // replace ' with \'

        return $text;
    }

    public function encodeDouble($text)
    {
        $text = str_replace("\"", "&quot;", $text); // replace " with &quot;

        return $text;
    }

    public function formatDate($date, $format, $lang)
    {
        $date = date($format, strtotime($date));

        $date = str_ireplace('January', $lang['lang_january'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Jan', $lang['lang_january_short'], $date);
        }

        $date = str_ireplace('February', $lang['lang_february'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Feb', $lang['lang_february_short'], $date);
        }

        $date = str_ireplace('March', $lang['lang_march'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Mar', $lang['lang_march_short'], $date);
        }

        $date = str_ireplace('April', $lang['lang_april'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Apr', $lang['lang_april_short'], $date);
        }

        $date = str_ireplace('May', $lang['lang_may'], $date, $count);
        if (!$count) {
            $date = str_ireplace('May', $lang['lang_may_short'], $date);
        }

        $date = str_ireplace('June', $lang['lang_june'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Jun', $lang['lang_june_short'], $date);
        }

        $date = str_ireplace('July', $lang['lang_july'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Jul', $lang['lang_july_short'], $date);
        }

        $date = str_ireplace('August', $lang['lang_august'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Aug', $lang['lang_august_short'], $date);
        }

        $date = str_ireplace('September', $lang['lang_september'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Sep', $lang['lang_september_short'], $date);
        }

        $date = str_ireplace('October', $lang['lang_october'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Oct', $lang['lang_october_short'], $date);
        }

        $date = str_ireplace('November', $lang['lang_november'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Nov', $lang['lang_november_short'], $date);
        }

        $date = str_ireplace('December', $lang['lang_december'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Dec', $lang['lang_december_short'], $date);
        }

        $date = str_ireplace('Monday', $lang['lang_monday'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Mon', $lang['lang_monday_short'], $date);
        }

        $date = str_ireplace('Tuesday', $lang['lang_tuesday'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Tue', $lang['lang_tuesday_short'], $date);
        }

        $date = str_ireplace('Wednesday', $lang['lang_wednesday'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Wed', $lang['lang_wednesday_short'], $date);
        }

        $date = str_ireplace('Thursday', $lang['lang_thursday'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Thu', $lang['lang_thursday_short'], $date);
        }

        $date = str_ireplace('Friday', $lang['lang_friday'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Fri', $lang['lang_friday_short'], $date);
        }

        $date = str_ireplace('Saturday', $lang['lang_saturday'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Sat', $lang['lang_saturday_short'], $date);
        }

        $date = str_ireplace('Sunday', $lang['lang_sunday'], $date, $count);
        if (!$count) {
            $date = str_ireplace('Sun', $lang['lang_sunday_short'], $date);
        }

        return $date;
    }
}
