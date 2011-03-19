<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
function findOrCreateFormEntry($em,$text,$sourceType,$sourceCode,$targetType,$targetCode)
{
   $qb = $em->createQueryBuilder()
        ->select("fe")
        ->from("library\doctrine\Entities\FormEntry","fe")
        ->where("fe.source_code=:sc")
        ->andWhere("fe.source_code_type=:st")
        ->andWhere("fe.target_code=:tc")
        ->andWhere("fe.target_code_type=:tt");
    $qb->setParameter("sc",$sourceCode);
    $qb->setParameter("st",$sourceType);
    $qb->setParameter("tc",$targetCode);
    $qb->setParameter("tt",$targetType);
    $qry=$qb->getQuery();
    $res=$qry->getResult();
    if(count($res)==0)
    {
        $retVal=new library\doctrine\Entities\FormEntry($text,$sourceCode,$sourceType,$targetCode,$targetType);
        $em->persist($retVal);
    }
    else
    {
        $retVal=$res[0];
    }
    return $retVal;
}

if(isset($_REQUEST['aui']))
{
    $aui = $_REQUEST['aui'];
    $sourceCode=$aui;
    $sourceType="SNOMED";
}

if(isset($_REQUEST['sourceCode']))
{
    $sourceCode=$_REQUEST['sourceCode'];
}


if(isset($_REQUEST['sourceType']))
{
    $sourceType=$_REQUEST['sourceType'];
}

if(isset($_REQUEST['targetCode']))
{
    $targetCode=$_REQUEST['targetCode'];
}

if(isset($_REQUEST['targetType']))
{
    $targetType=$_REQUEST['targetType'];
}

if(isset($_REQUEST['text']))
{
    $text=$_REQUEST['text'];
}
if(isset($_REQUEST['classification']))
{
    $classification=$_REQUEST['classification'];
}

echo $sourceType.":".$sourceCode.":".$targetType.":".$targetCode.":".$text.":".$classification;

if(($text!==null) && ($targetCode!==null) && ($targetType!==null) && ($sourceType!==null) && ($sourceCode!==null))
{
    $fe=findOrCreateFormEntry($em,$text,$sourceType,$sourceCode,$targetType,$targetCode);
    $fe->setClassification($classification);
    $em->flush();
}

echo "<BR>SUCCESS!";
?>
