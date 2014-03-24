<?php

// add views folder
Finder::instance()->add_path(__DIR__);

Autoloader::add_classes(array(
    'Controller_Auth'   => __DIR__.'/classes/controller/auth.php',
    'Controller_Promoter'   => __DIR__.'/classes/controller/promoter.php',
    'Promoter' => __DIR__.'/classes/promoter.php',
    'HttpAccessDeniedException'   => __DIR__.'/classes/exceptions.php',
    'Promoter\\Model\\Promoter_User' => __DIR__.'/classes/model/promoter/user.php',
    'Promoter\\Model\\Promoter_General' => __DIR__.'/classes/model/promoter/general.php',
    'Promoter\\Model\\Promoter_Metadata' => __DIR__.'/classes/model/promoter/metadata.php',
));