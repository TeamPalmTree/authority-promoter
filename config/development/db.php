<?php

return array(
    'authority' => array(
        'connection'  => array(
            'dsn'        => 'mysql:host=authority;dbname=authority',
            'username'   => 'root',
            'password'   => '',
        ),
        'type'        => 'pdo',
        'identifier'   => '`',
        'table_prefix' => '',
        'charset'      => 'utf8',
        'collation'    => false,
        'enable_cache' => true,
        'profiling'    => false,
        'readonly'     => false,
    ),
);
