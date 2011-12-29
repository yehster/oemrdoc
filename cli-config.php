<?php
use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\Common,
    Doctrine\Common\Cache,
    Doctrine\ORM\Query\AST\Functions,
    Doctrine\ORM\Query\AST;


set_include_path(get_include_path() . PATH_SEPARATOR . '/var/www/openemr');
require_once("/var/www/openemr/sites/default/sqlconf.php");
$doctrineroot="/var/www/openemr/library/doctrine";

require_once 'Doctrine/Common/ClassLoader.php';
require_once 'Doctrine/ORM/Query/AST/Node.php';
require_once 'Doctrine/ORM/Query/AST/Functions/FunctionNode.php';

$logLocation="/var/log/doctrine/info.log";
function doctrine_log($text)
{
$fhLog=fopen($GLOBALS['logLocation'],'a');
fwrite($fhLog,$text);
fclose($fhLog);
    
}
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

$classLoader = new \Doctrine\Common\ClassLoader();
$classLoader->register();


$config = new Configuration;
$cacheDriver = new \Doctrine\Common\Cache\ApcCache();
$cacheDriver->save('cache_id', 'my_data');
$config->setMetadataCacheImpl($cacheDriver);

$driverImpl = $config->newDefaultAnnotationDriver('/var/www/openemr'.'/library/doctrine/Entities');
$config->setMetadataDriverImpl($driverImpl);
$config->setQueryCacheImpl($cacheDriver);


$config->setProxyDir('/var/www/openemr'.'/library/doctrine/Proxies');
$config->setProxyNamespace('Proxies');
// $config->setAutoGenerateProxyClasses((APPLICATION_ENV == "development")); 
// Still need to figure out how to setup proxy classes properly
$config->setAutoGenerateProxyClasses(true); 

$config->addCustomNumericFunction("MATCHQUALITY", "MatchQualityNode");
$host=$GLOBALS['sqlconf']['host'];

$connectionParams = array(
    'dbname' => 'openemr',
    'user' => 'openemr',
    'password' => 'mydbpwd',
    'host' => $host,
    'driver' => 'pdo_mysql',
);

$em = EntityManager::create($connectionParams, $config);
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
//$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('blob', 'string');
$params=array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
);



$helperSet = new \Symfony\Component\Console\Helper\HelperSet($params);

?>
