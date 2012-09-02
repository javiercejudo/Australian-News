<?php
require __DIR__ . '/../config.php';
require DIRLIB . 'App.php';
file_put_contents(__DIR__ . '/../data/smh.xml', file_get_contents(SOURCE));
App::load_XML();
