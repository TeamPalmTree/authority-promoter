<?php

// add views folder
Finder::instance()->add_path(__DIR__);

Autoloader::add_classes(array(
    'Controller_Auth'   => __DIR__.'/classes/controller/auth.php',
    'Controller_Promoter'   => __DIR__.'/classes/controller/promoter.php',
    'Auth_Login_Promoterauth' => __DIR__.'/classes/auth/login/promoterauth.php',
));