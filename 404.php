<?php
define('CONFIG_PROTECTION', false);
require_once(__DIR__ . '/config.php');
session_start();
new Page('404');
