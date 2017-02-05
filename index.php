<?php
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
define('APP_DEBUG',true);
define('APP_PATH','./App/');
define('WEB_SITE','http://127.0.0.1/ThinkGms/');

define('RUNTIME_PATH','./Runtime/');
require './ThinkPHP/ThinkPHP.php';