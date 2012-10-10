<?php

class VocabInfo
{
    function __construct($description,$code_type,$code)
    {
        $this->description=$description;
        $this->code_type=$code_type;
        $this->code=$code;
    }
    
    public $description;
    
    public $code_type;
    
    public $code;
}

function findVocab($searchString,$vocab_type)
{
    $retval=array();
    $toks=explode(" ",$searchString);
    $shortKW=array();
    $longKW=array();
    foreach($toks as $kw)
    {
        if(strlen($kw)>3)
        {
            $longKW[]="+".$kw."*";
        }
        else
        {
            $shortKW[]=$kw;
        }
    }
    $longKWs=implode(" ",$longKW);
    if($vocab_type=="LOINC")
    {
        $entityType="library\doctrine\Entities\LOINC\LOINCEntry";
        $searchField="pt.LONG_COMMON_NAME";
        $descFunc="getLONG_COMMON_NAME";
        $idFunc="getLOINC_NUM";        
    }
    else if($vocab_type=="SNOMED")
    {
        $entityType="library\doctrine\Entities\SNOMED\PreferredTerm";
        $searchField="pt.str";
        $descFunc="getSTR";
        $idFunc="getAUI";
        
    }
    else if($vocab_type=="IEMR")
    {
        $entityType="library\doctrine\Entities\IEMR\IEMRCode";
        $searchField="pt.description";
        $descFunc="getDescription";
        $idFunc="getCode";
        
    }
        $qb = $GLOBALS['em']->createQueryBuilder()
                ->select("pt,MATCH_AGAINST(".$searchField.",'".$longKWs."') as rel");
            if(count($longKW)>0)
            {
                $qb->where("MATCH_AGAINST(".$searchField.",'".$longKWs."') > 0.1");                
            }
            $qb->from($entityType,"pt");
            
            for($idx=0;$idx<count($shortKW);$idx++)
            {
                $qb->andWhere($searchField." like :skw".$idx);
                $qb->setParameter("skw".$idx,"%".$shortKW[$idx]."%");
            }
        $qb->orderBy("rel","desc");
        $qb->setMaxResults(1000);

        $qry=$qb->getQuery();
        error_log($qry->getDql());
        error_log($qry->getSql());
        $res=$qry->getResult();
    foreach($res as $snResult)
    {
        $retval[]=new VocabInfo($snResult[0]->$descFunc(),$vocab_type,$snResult[0]->$idFunc());
    }
    return $retval;
}


?>
