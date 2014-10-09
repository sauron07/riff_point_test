<?php
/**
 * Created by PhpStorm.
 * User: matveev
 * Date: 9/11/14
 * Time: 11:53 AM
 */
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' =>'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'riff_point',
                )
            )
        )
    )
);