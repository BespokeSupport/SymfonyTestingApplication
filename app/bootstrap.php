<?php

if (file_exists(dirname(__FILE__) . '/../../../../vendor/autoload.php')) {
    $loader = require_once dirname(__FILE__) . '/../../../../vendor/autoload.php';
} elseif (file_exists(dirname(__FILE__) . '/../vendor/autoload.php')) {
    $loader = require_once dirname(__FILE__) . '/../vendor/autoload.php';
} else {
    throw new \Exception('Composer Autoload not found');
}

return $loader;
