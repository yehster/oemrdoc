<?php

//$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
//$classLoader->register();

//$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
//$classLoader->register();


$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);

$driverImpl = $config->newDefaultAnnotationDriver('/var/www/openemr'.'/library/doctrine/Entities');
$config->setMetadataDriverImpl($driverImpl);


$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');

$connectionParams = array(
    'dbname' => 'openemr',
    'user' => 'openemr',
    'password' => 'openemr',
    'host' => 'donaghy',
    'driver' => 'pdo_mysql',
);
$em = \Doctrine\ORM\EntityManager::create($connectionParams, $config);
$params=array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
);



$helperSet = new \Symfony\Component\Console\Helper\HelperSet($params);

?>
