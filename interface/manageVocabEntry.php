<?php
require_once("manageParentEntry.php");

function classToType($class)
{
    switch($class)
    {
        case "normal":
        case "abnormal":
            return "Observation";
        case "multiple":
        case "exclusive":
            return "Nominative";
        case "quantitative":
            return "QuantitativeEntry";
    }
}

function findOrCreateVocab($em,$code,$parent,$code_type,$classification,$seq)
{

    $parItem=$parent->getItem();
    $type=classToType($classification);
    $objType="library\doctrine\Entities\\".$type;
    $qb = $em->createQueryBuilder()
        ->select("obs")
        ->from($objType,"obs")
        ->join("obs.item","i")
        ->where("obs.vocabID=:voc")
        ->andwhere("i.parent=:par")    ;
    $qb->setParameter("voc",$code);
    $qb->setParameter("par",$parItem);

    $qryRes=$qb->getQuery()->getResult();
    if(count($qryRes)===0)
    {
        $res = new $objType(null,$pat,$user);
        $newItem=$PE->getItem()->addEntry($res);
        if($seq!=-1)
        {
            $newItem->setSeq($seq);
        }
        $res->setvocabID($vocabID);
    }
    else
    {
        $res = $qryRes[0];
    }
    return $res;

}
if(isset($_REQUEST["value"]))
{
    $value=$_REQUEST["value"];
}

if(isset($_REQUEST["classification"]))
{
    $classification=$_REQUEST["classification"];
}

if(isset($_REQUEST["code"]))
{
    $code=$_REQUEST["code"];
}
else
{
    header("HTTP/1.0 403 Forbidden");
    echo "No Code Set";
    return;
}

if(isset($_REQUEST["codeType"]))
{
    $code_type=$_REQUEST["codeType"];
}

if(isset($_REQUEST["entryUUID"]))
{
    $entryUUID=$_REQUEST["entryUUID"];
    if($entryUUID!="undefined")
    {
        
    }
    else 
    {
        $entry=findOrCreateVocab($em,$code,$parent,$code_type,$classification,$seq);
    }
}
if($task==="update")
{    
    echo $value;
}
?>
