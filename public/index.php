<?php

use Project\App;

include dirname(__DIR__) . '/loader.php';
$loader->loadFile('throws.php');
$app = new App($loader);

$app->bootstrap()->proccess();
