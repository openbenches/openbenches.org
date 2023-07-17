<?php
namespace Commentics;

class Security
{
    public function encode($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function decode($value)
    {
        return html_entity_decode($value, ENT_QUOTES, 'UTF-8');
    }

    public function isInjected($value)
    {
        if (preg_match('/(\n+)|(\r+)/', $value)) {
            return true;
        } else {
            return false;
        }
    }
}
