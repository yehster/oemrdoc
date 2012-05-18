<?php
require_once("/var/www/openemr/library/doctrine/common/ICD9Constants.php");

function lookupByCode($em,$searchString)
{
        $qb = $em->createQueryBuilder()
        ->select("code, code.frequency")
        ->where("code.code like :startsWith")
        ->from("library\doctrine\Entities\ICD9\ICD9Code","code")           
        ->orderBy("code.frequency","DESC")
        ->addOrderBy("code.code","ASC");
    $qb->addOrderBy("code.frequency","DESC");

    $qb->setParameter("startsWith",$searchString."%");

    $qry=$qb->getQuery();

    return $qry->getResult();    
}

class code_node
{
    public function __construct($cd)
    {
        $this->code=$cd;
        $this->children=array();
    }

    public function add_child($code_node)
    {
        $this->children[$code_node->codeText()]=$code_node;
    }
    protected $code;
    
    public function parentText()
    {
        if($this->code->getParent()==null)
        {
            return "ROOT";
        }
        return $this->code->getParent()->getCode();
    }
    public function codeText()
    {
        return $this->code->getCode();
    }
    protected $children;
}

function populate_parent_code_array(&$parent, &$child)
{
    foreach($child as $key=>$code_node)
    {

        if(isset($parent[$code_node->parentText()]))
        {
            echo $code_node->codeText().":".$code_node->parentText().PHP_EOL;
            $parent[$code_node->parentText()]->add_child($code_node);
            unset($child[$key]);
        }
    }
}
function remove_found_entries(&$found,&$orig)
{
    foreach($found as $key=>$value)
    {
        unset($orig[$key]);
    }
}        

function generate_codes($codeList)
{
    $DOM=new DOMDocument("1.0","utf-8");
    $top=array();
    $section=array();
    $ns=array();
    $sp=array();
    foreach($codeList as $codeInfo)
    {
        $code=$codeInfo[0];
        
        if($codeInfo["frequency"]>0)
        {
            $top[$code->getCode()]=new code_node($code);
        }
        elseif($code->type==ICD9_TYPE_SECTION)
        {
            $section[$code->getCode()]=new code_node($code);
        }
        elseif($code->type==ICD9_TYPE_NON_SPECIFIC)
        {
            $ns[$code->getCode()]=new code_node($code);
        }
        elseif($code->type==ICD9_TYPE_SPECIFIC)
        {
            $sp[$code->getCode()]=new code_node($code);
        }
    }
    echo count($top).":".count($section).":".count($ns).":".count($sp).PHP_EOL;
    populate_parent_code_array($ns,$sp);    
    
    populate_parent_code_array($section,$sp);
    
    populate_parent_code_array($ns,$ns);        
    populate_parent_code_array($section,$ns);
    
    populate_parent_code_array($section,$section);

    echo count($top).":".count($section).":".count($ns).":".count($sp).PHP_EOL;    
    
    foreach($section as $code_node)
    {
        echo $code_node->codeText().":".$code_node->parentText().PHP_EOL;
    }
}
?>
