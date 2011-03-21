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

function findMaxSeqFE($em,$targetType,$targetCode,$cls)
{
   $qb = $em->createQueryBuilder()
        ->select("MAX(fe.seq) as maxSeq")
        ->from("library\doctrine\Entities\FormEntry","fe")
        ->Where("fe.target_code=:tc")
        ->andWhere("fe.target_code_type=:tt")
        ->andWhere("fe.classification=:cl");
    $qb->setParameter("tc",$targetCode);
    $qb->setParameter("tt",$targetType);
    $qb->setParameter("cl",$cls);
    $qry=$qb->getQuery();
    $res=$qry->getResult();
    $val=$res[0];
    $retval=$val['maxSeq'];
    if($retval==null)
    {
        $retval=0;
    }
    return $retval;
}

function findSEQSwapFE($em,$FE,$mode)
{

   $qb = $em->createQueryBuilder()
        ->select("fe")
        ->from("library\doctrine\Entities\FormEntry","fe")
        ->Where("fe.target_code=:tc")
        ->andWhere("fe.target_code_type=:tt")
        ->andWhere("fe.classification=:cl");
    if($mode=="dn")
    {
        $qb->andWhere("fe.seq>:seqNum");
        $qb->orderBy("fe.seq","ASC");
    }
    elseif($mode=="up")
    {
        $qb->andWhere("fe.seq<:seqNum");
        $qb->orderBy("fe.seq","DESC");
    }
    $qb->setParameter("tc",$FE->getTarget_code());
    $qb->setParameter("tt",$FE->getTarget_code_type());
    $qb->setParameter("cl",$FE->getClassification());
    $qb->setParameter("seqNum",$FE->getSeq());
    $qry=$qb->getQuery();
    $res=$qry->getResult();
    if(count($res)>0)
    {
        return $res[0];
    }
    else
    {
        return null;
    }
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

if(isset($_REQUEST['mode']))
{
    $mode=$_REQUEST['mode'];
}

if(isset($_REQUEST['type']))
{
    $type=$_REQUEST['type'];
}


if(isset($_REQUEST['uuid']))
{
    $uuid=$_REQUEST['uuid'];
}

if($mode=="create")
{
    echo $sourceType.":".$sourceCode.":".$targetType.":".$targetCode.":".$text.":".$classification;
    if(($text!==null) && ($targetCode!==null) && ($targetType!==null) && ($sourceType!==null) && ($sourceCode!==null))
    {
     $fe=findOrCreateFormEntry($em,$text,$sourceType,$sourceCode,$targetType,$targetCode);
     $fe->setClassification($classification);
     if(isset($_REQUEST['seq']))
     {
         $seq=$_REQUEST['seq'];
     }
     else
     {
         $seq=findMaxSeqFE($em, $targetType, $targetCode,$classification);
         if($seq==null)
         {
             $seq=0;
         }
         $seq++;
     }
     $fe->setSeq($seq);
     $em->flush();
    }
}
if($mode=="del")
{
    if($type=="FormEntry")
    {
        $fe=$em->getRepository('library\doctrine\Entities\FormEntry')->find($uuid);
        $classification=$fe->getClassification();
        // TODO: update sequence
        $em->remove($fe);
        $em->flush();
        echo "delete successful";
    }
}
if(($mode=="up") || ($mode=="dn"))
{
    if($type=="FormEntry")
    {
        $fe=$em->getRepository('library\doctrine\Entities\FormEntry')->find($uuid);
        $swapSeqFE=findSEQSwapFE($em,$fe,$mode);
        if($swapSeqFE!==null)
        {
            $tmpSwap=$swapSeqFE->getSeq();
            $swapSeqFE->setSeq($fe->getSeq());
            $fe->setSeq($tmpSwap);
            $em->flush();
        }
        echo "updated sequence.";
    }
}
if(($mode=="abn") || ($mode=="nor"))
{
    if($mode=="abn")
    {
        $newClass="abnormal";
    }
    else
    {
        $newClass="normal";
    }
    if($type=="FormEntry")
    {
        $fe=$em->getRepository('library\doctrine\Entities\FormEntry')->find($uuid);
        $nextSeq=findMaxSeqFE($em, $fe->getTarget_code_type(), $fe->getTarget_code(), $newClass)+1;
        $fe->setClassification($newClass);
        $fe->setSeq($nextSeq);
        echo $newClass.":".$maxSeq;
        $em->flush();
    }
}
?>
