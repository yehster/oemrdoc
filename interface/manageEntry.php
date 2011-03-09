<?php
include_once("/var/www/openemr/interface/globals.php");
include_once('/var/www/openemr/library/doctrine/init-em.php');

function findObservationOrCreate($em,$PE,$metadataUUID,$pat,$user)
{
    $parItem=$PE->getItem();
    $qb = $em->createQueryBuilder()
        ->select("obs")
        ->from("library\doctrine\Entities\Observation","obs")
        ->join("obs.item","i")
        ->where("obs.metadataUUID=:mdu")
        ->andwhere("i.parent=:par")    ;
    $qb->setParameter("mdu",$metadataUUID);
    $qb->setParameter("par",$parItem);

    $qryRes=$qb->getQuery()->getResult();
    if(count($qryRes)===0)
    {
        $res = new library\doctrine\Entities\Observation(null,$pat,$user);
        $PE->getItem()->addEntry($res);
        $res->setMetadataUUID($metadataUUID);
    }
    else
    {
        $res = $qryRes[0];
    }
    return $res;

}

$user = $_SESSION['authUser'];
if(isset($_SESSION['pid']))
{
    $patID=$_SESSION['pid'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
}
if(isset($_REQUEST['parentEntryUUID']))
{
    $parentEntryUUID = $_REQUEST['parentEntryUUID'];
    $parentEntry = $em->getRepository('library\doctrine\Entities\DocumentEntry')->find($parentEntryUUID);
}
if(isset($_REQUEST['EntryType']))
{
    $EntryType = $_REQUEST['EntryType'];
}
if(isset($_REQUEST['task']))
{
    $task = $_REQUEST['task'];
}
if(isset($_REQUEST['content']))
{
        $content = $_REQUEST['content'];
}
if(isset($_REQUEST['metadataUUID']))
{
    $metadataUUID = $_REQUEST['metadataUUID'];
}
/****************************************************
 * MedicationEntry
 */
if($EntryType=="med")
{

    if(isset($_REQUEST['rxcui']))
    {
        $rxcui = $_REQUEST['rxcui'];
    }
    if(isset($_REQUEST['rxaui']))
    {
        $rxauiID = $_REQUEST['rxaui'];
        $rxaui = $em->getRepository('library\doctrine\Entities\RXNConcept')->find($rxauiID);

    }
    if($task=="create")
    {

        $med = new library\doctrine\Entities\MedicationEntry(null,$pat,$user);
        $med->setText($content,$user);
        $med->setRXCUI($rxcui);
        $med->setRXAUI($rxaui);

        $parentEntry->getItem()->addEntry($med);
        $em->persist($med);
        $em->flush();
        echo $med->getUUID();
        return;
    }
    echo "error:".$task.":".$EntryType;
}
if($EntryType=="observation")
{
    if(isset($_REQUEST['val']))
    {
        $val = $_REQUEST['val'];
    }
    if(isset($_REQUEST['observationUUID']))
    {
        $observationUUID=$_REQUEST['observationUUID'];
    }
    $obs=$em->getRepository('library\doctrine\Entities\Observation')->find($observationUUID);
    if($obs===null)
    {
        $obs = findObservationOrCreate($em,$parentEntry,$metadataUUID,$pat,$user);
    }
    $obs->setValue($val);
    $obs->setText($content,$auth);
    $em->persist($obs);
    $em->flush();
    echo $obs->getUUID();
    return;

}

?>
