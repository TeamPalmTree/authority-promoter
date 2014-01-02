<?php

// add views folder
Finder::instance()->add_path(__DIR__);

Autoloader::add_classes(array(
    'Controller_Auth'   => __DIR__.'/classes/controller/auth.php',
    'Controller_Promoter'   => __DIR__.'/classes/controller/promoter.php',
));