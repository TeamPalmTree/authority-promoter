<?php

// add views folder
Finder::instance()->add_path(__DIR__);

Autoloader::add_classes(array(
    'Controller_Auth'   => __DIR__.'/classes/controller/auth.php',
    'Controller_Promoter'   => __DIR__.'/classes/controller/promoter.php',
    'Promoter\\Model\\Model_User' => __DIR__.'/classes/model/user.php',
    'Promoter' => __DIR__.'/classes/promoter.php',
    'HttpAccessDeniedException'   => __DIR__.'/classes/exceptions.php',
));