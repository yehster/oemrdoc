<?php
require_once  'Doctrine/Common/ClassLoader.php';
require_once ('/usr/share/php/Doctrine/Symfony/Component/Console/Helper/HelperSet.php');
/*
require_once ('/usr/share/php/Doctrine/DBAL/Configuration.php');
require_once ('/usr/share/php/Doctrine/ORM/Configuration.php');
require_once ('/usr/share/php/Doctrine/Common/Annotations/AnnotationReader.php');
require_once ('/usr/share/php/Doctrine/Common/Lexer.php');
require_once ('/usr/share/php/Doctrine/Common/Cache/Cache.php');
require_once ('/usr/share/php/Doctrine/Common/Cache/AbstractCache.php');
require_once ('/usr/share/php/Doctrine/Common/Cache/ArrayCache.php');
require_once ('/usr/share/php/Doctrine/Common/Annotations/Lexer.php');
require_once ('/usr/share/php/Doctrine/Common/Annotations/Parser.php');
*/



$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__.'/Entities');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__.'/Proxies');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Symfony','/usr/share/Doctrine/Symfony');
$classLoader->register();


$config = new \Doctrine\ORM\Configuration();

$driverImpl = $config->newDefaultAnnotationDriver(__DIR__.'/Entities');
$config->setMetadataDriverImpl($driverImpl);


$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);


$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');

$connectionParams = array(
    'dbname' => 'openemr',
    'user' => 'openemr',
    'password' => 'openemr',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
);
$em = \Doctrine\ORM\EntityManager::create($connectionParams, $config);
$params=array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
);



$helperSet = new \Symfony\Component\Console\Helper\HelperSet($params);

?>
