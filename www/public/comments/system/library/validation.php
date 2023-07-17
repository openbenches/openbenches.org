<?php
namespace Commentics;

class Validation
{
    public function isInt($value)
    {
        return ctype_digit($value);
    }

    public function isAlpha($value)
    {
        return ctype_alpha($value);
    }

    public function isAlnum($value)
    {
        return ctype_alnum($value);
    }

    public function isUpper($value)
    {
        return ctype_upper($value);
    }

    public function isLower($value)
    {
        return ctype_lower($value);
    }

    public function isFloat($value)
    {
        if (floatval($value)) {
            return true;
        } else {
            return false;
        }
    }

    public function isHex($value)
    {
        return ctype_xdigit($value);
    }

    public function isEmail($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    public function isUrl($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return true;
        } else {
            return false;
        }
    }

    public function isSelectableAvatar($value)
    {
        if (preg_match('/^[a-z0-9-_]+$/i', $value)) {
            return true;
        } else {
            return false;
        }
    }

    public function isFolder($value)
    {
        if (preg_match('/^[a-z0-9-_]+$/i', $value)) {
            return true;
        } else {
            return false;
        }
    }

    public function isPath($value)
    {
        if (preg_match('/^[a-z0-9-_\/\.:]+$/i', $value)) {
            return true;
        } else {
            return false;
        }
    }

    public function isIpAddress($value)
    {
        if (filter_var($value, FILTER_VALIDATE_IP)) {
            return true;
        } else {
            return false;
        }
    }

    public function length($value)
    {
        if (function_exists('mb_strlen') && is_callable('mb_strlen')) {
            return mb_strlen($value, 'UTF-8');
        } else {
            return strlen(utf8_decode($value));
        }
    }
}
