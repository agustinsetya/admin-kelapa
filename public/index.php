<?php

// Minimum PHP untuk CI4.5+ adalah 8.1
$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    exit(sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION
    ));
}

// Path ke front controller (file ini)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Pastikan working directory = folder public/
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// Muat Paths dari app/Config
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();

// Muat Boot baru dan jalankan aplikasi web
require $paths->systemDirectory . '/Boot.php';
exit(CodeIgniter\Boot::bootWeb($paths));
