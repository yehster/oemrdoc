<?php
use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\Common\Cache;


set_include_path(get_include_path() . PATH_SEPARATOR . '/var/www/openemr');

require_once 'Doctrine/Common/ClassLoader.php';
//require 'Doctrine/Common/Cache/ApcCache.php';
//require 'Doctrine/Common/Cache/AbstractCache.php';
//require 'library/doctrine/Entities/ORMPatient.php';
$classLoader = new \Doctrine\Common\ClassLoader();
$classLoader->register();

$memcache = new Memcache();
$memcache->connect('127.0.0.1', 11211);
$memcache->flush();

$config = new Configuration;
$cache = new Doctrine\Common\Cache\MemcacheCache;
$cache->setMemcache($memcache);
$config->setMetadataCacheImpl($cache);
// apc cache?

$driverImpl = $config->newDefaultAnnotationDriver('/var/www/openemr'.'/library/doctrine/Entities');
$config->setMetadataDriverImpl($driverImpl);
$config->setQueryCacheImpl($cache);


$config->setProxyDir('/var/www/openemr'.'/library/doctrine/Proxies');
$config->setProxyNamespace('Proxies');
// $config->setAutoGenerateProxyClasses((APPLICATION_ENV == "development")); 
// Still need to figure out how to setup proxy classes properly
$config->setAutoGenerateProxyClasses(true); 


$connectionParams = array(
    'dbname' => 'openemr',
    'user' => 'openemr',
    'password' => 'openemr',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
);

$em = EntityManager::create($connectionParams, $config);
?>
