<?php
require 'admin.simplibook.in/composer.phar';

use Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

$application = new Application();
$input = new ArrayInput(['command' => 'update']);
$application->run($input);
