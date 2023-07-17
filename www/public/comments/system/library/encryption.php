<?php
namespace Commentics;

class Encryption
{
    private $setting;

    public function __construct($registry)
    {
        $this->setting = $registry->get('setting');
    }

    public function encrypt($value)
    {
        $key = hash('sha256', $this->setting->get('encryption_key'), true);

        $iv = openssl_random_pseudo_bytes(16);

        $cipher = openssl_encrypt($value, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        $hash = hash_hmac('sha256', $cipher, $key, true);

        $encrypted = $iv . $hash . $cipher;

        $encrypted = base64_encode($encrypted);

        return $encrypted;
    }

    public function decrypt($value)
    {
        $value = base64_decode($value);

        $iv = substr($value, 0, 16);

        $hash = substr($value, 16, 32);

        $cipher = substr($value, 48);

        $key = hash('sha256', $this->setting->get('encryption_key'), true);

        if (hash_hmac('sha256', $cipher, $key, true) !== $hash) {
            return '';
        }

        $decrypted = openssl_decrypt($cipher, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        return $decrypted;
    }
}
