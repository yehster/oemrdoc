<?php
require_once("/var/www/openemr/library/doctrine/common/ICD9Constants.php");

function lookupByCode($em,$searchString,$type="library\doctrine\Entities\ICD9\ICD9Code",$freq_filter=false)
{
        $qb = $em->createQueryBuilder()
        ->select("code, code.frequency")
        ->from($type,"code")
        ->where("code.code like :startsWith");
        if($freq_filter)
        {
            $qb->andWhere("code.frequency>0");
        }
        $qb->orderBy("code.frequency","DESC")
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
    
    public function codeType()
    {
        return $this->code->type;
    }
    public function codeDesc()
    {
        return $this->code->getShort_desc();
    }
    protected $children;
    
    public function getChildren()
    {
        return $this->children;
    }
    
    public function frequency()
    {
        return $this->code->getFrequency();
    }
}

function populate_parent_code_array(&$parent, &$child)
{
    foreach($child as $key=>$code_node)
    {

        if(isset($parent[$code_node->parentText()]))
        {
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

function create_code_row($DOM,$par_elem,$code_node,$depth,$parent)
{
    $tr=$DOM->createElement("tr");
    $tr->setAttribute("type",$code_node->codeType());
    if($code_node->frequency()>0)
    {
        $tr->setAttribute("class","priority");
    }
    $tdDesc=$DOM->createElement("td",$code_node->codeDesc());
    $tdDesc->setAttribute("class","codeDesc");
    $tr->appendChild($tdDesc);
    $tdCode=$DOM->createElement("td",$code_node->codeText());
    $tdCode->setAttribute("class","codeNum");
    $tr->appendChild($tdCode);
    $par_elem->appendChild($tr);
    if($depth>1)
    {
        $tr->setAttribute("parent_code",$parent);
    }
    
    $tr->setAttribute("depth",$depth);
}
function create_code_rows($DOM,$par_elem,$code_nodes,$depth,$parent=null)
{
    foreach($code_nodes as $code_node)
    {
        $children=$code_node->getChildren();
        if((count($children)!==1) && $parent!=null)
            {create_code_row($DOM,$par_elem,$code_node,$depth,$parent);}
        else { $depth=$depth-1;}
        create_code_rows($DOM,$par_elem,$children,$depth+1,$code_node->codeText());
    }
}

function generate_codes($codeList)
{
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
    populate_parent_code_array($ns,$sp);
    populate_parent_code_array($section,$sp);
    
    populate_parent_code_array($ns,$ns);        
    populate_parent_code_array($section,$ns);
    
    populate_parent_code_array($section,$section);
    $DOM=new DOMDocument("1.0","utf-8");
    $table=$DOM->createElement("table");
    $tbody=$DOM->createElement("tbody");
    $table->appendChild($tbody);
    
    create_code_rows($DOM,$tbody,$top,1);
    create_code_rows($DOM,$tbody,$section,1);
    create_code_rows($DOM,$tbody,$ns,1);
    create_code_rows($DOM,$tbody,$sp,1);
    
    return $DOM->saveHTML($table);
}
?>
