<?php
if (file_exists('install/')) {
    header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/install/');

    die();
} else {
    header('HTTP/1.0 403 Forbidden');

    echo '<!DOCTYPE html>';
    echo '<html>';
    echo '<head>';
    echo '<title>403 Forbidden</title>';
    echo '<meta name="robots" content="noindex">';
    echo '</head>';
    echo '<body>';
    echo '<h1>Forbidden</h1>';
    echo '<p>You don\'t have permission to access this folder.</p>';
    echo '<hr>';
    echo '</body>';
    echo '</html>';
}
