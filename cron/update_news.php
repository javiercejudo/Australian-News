<?php
// */10 * * * * php -f /path/to/this/file
require __DIR__ . '/../config.php';
require DIRLIB . 'App.php';
App::load_XML();
