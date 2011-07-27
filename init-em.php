<?php
use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\Common,
    Doctrine\Common\Cache,
    Doctrine\ORM\Query\AST\Functions,
    Doctrine\ORM\Query\AST;


set_include_path(get_include_path() . PATH_SEPARATOR . '/var/www/openemr');
require_once("/var/www/openemr/sites/default/sqlconf.php");
error_reporting("E_ALL & ~E_DEPRECATED");
if($undefined==1)
{
    
};

session_name("OpenEMR");
session_start();


if(isset($_SESSION['em']))
{
    $em=$_SESSION['em'];
}
else
{

require_once 'Doctrine/Common/ClassLoader.php';
require_once 'Doctrine/ORM/Query/AST/Node.php';
require_once 'Doctrine/ORM/Query/AST/Functions/FunctionNode.php';


class MatchQualityNode extends Doctrine\ORM\Query\AST\Functions\FunctionNode
{
    public $SearchExpression = null;
    public $MatchExpression = null;


    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(\Doctrine\ORM\Query\Lexer::T_IDENTIFIER);
        $parser->match(\Doctrine\ORM\Query\Lexer::T_OPEN_PARENTHESIS);
        $this->SearchExpression = $parser->ArithmeticPrimary();
        $parser->match(\Doctrine\ORM\Query\Lexer::T_COMMA);
        $this->MatchExpression = $parser->ArithmeticPrimary();
        $parser->match(\Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'MatchQuality(' .
            $this->SearchExpression->dispatch($sqlWalker) . ', ' .
            $this->MatchExpression->dispatch($sqlWalker) .
        ')';
    }

}

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

$config->addCustomNumericFunction("MATCHQUALITY", "MatchQualityNode");

$connectionParams = array(
    'dbname' => 'openemr',
    'user' => 'openemr',
    'password' => 'mydbpwd',
    'host' => $GLOBALS[$dbase],
    'driver' => 'pdo_mysql',
);

$em = EntityManager::create($connectionParams, $config);
$_SESSION['em']=$em;
}
?>
