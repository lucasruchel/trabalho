<?php
return array(
     'db' => array(
         'driver'    => 'Pdo_Pgsql', // OU Pdo_Pgsql
         'host'      => 'localhost',
         'port'      => '5432',
         'dbname'    => 'album',
     ),
     'service_manager' => array(
         'factories' => array(
             'Zend\Db\Adapter\Adapter'
                     => 'Zend\Db\Adapter\AdapterServiceFactory',
         ),
     ),
 );