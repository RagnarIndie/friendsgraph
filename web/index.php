<?php
defined('ENV') or define('ENV', 'dev'); #user 'prod' in production

defined('ROOT_DIR') or define('ROOT_DIR', __DIR__.'/../');
defined('CONFIG_DIR') or define('CONFIG_DIR', ROOT_DIR.'/config/');
defined('DATA_DIR') or define('DATA_DIR', ROOT_DIR.'/data/');

$app = require_once __DIR__.'/../app/app.php';

$app->run();