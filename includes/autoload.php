<?php
/**
 * Autoloader
 *
 * @package WPPayForm
 */

if (!defined('ABSPATH')) {
    exit;
}

spl_autoload_register(function ($class) {

    // Do not load unless in plugin domain.
    $namespace = 'VueContactManager';
    if (strpos($class, $namespace) !== 0) {
        return;
    }

    // Remove the root namespace.
    $unprefixed = substr($class, strlen($namespace));

    // Build the file path.
    $file_path = str_replace('\\', DIRECTORY_SEPARATOR, $unprefixed);

    $file = dirname(__FILE__) . $file_path . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

