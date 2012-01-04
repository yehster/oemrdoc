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

    session_name("OpenEMR");
    session_start();

//if(isset($_SESSION['em']))
if(false) // not sure about caching entity manager for session
{
    $em=$_SESSION['em'];
}
else
{

require_once 'Doctrine/Common/ClassLoader.php';
require_once 'Doctrine/ORM/Query/AST/Node.php';
require_once 'Doctrine/ORM/Query/AST/Functions/FunctionNode.php';

$logLocation="/var/log/doctrine/info.log";
function doctrine_log($text)
{
$fhLog=fopen($GLOBALS['logLocation'],'a');
if($fhLog!==false)
{
    fwrite($fhLog,$text."\n");
    fclose($fhLog);
}    
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

$host=$GLOBALS['sqlconf']["host"];
$connectionParams = array(
    'dbname' => 'openemr',
    'user' => 'openemr',
    'password' => 'mydbpwd',
    'host' => $host,
    'driver' => 'pdo_mysql',
);

$em = EntityManager::create($connectionParams, $config);
}
$conn=$em->getConnection();
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
?>