<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
include_once('../ui/Editor/EditorUtilities.php');

session_name("OpenEMR");
session_start();


function findObservationOrCreate($em,$PE,$vocabID,$pat,$user)
{
    $parItem=$PE->getItem();
    $qb = $em->createQueryBuilder()
        ->select("obs")
        ->from("library\doctrine\Entities\Observation","obs")
        ->join("obs.item","i")
        ->where("obs.vocabID=:voc")
        ->andwhere("i.parent=:par")    ;
    $qb->setParameter("voc",$vocabID);
    $qb->setParameter("par",$parItem);

    $qryRes=$qb->getQuery()->getResult();
    if(count($qryRes)===0)
    {
        $res = new library\doctrine\Entities\Observation(null,$pat,$user);
        $newItem=$PE->getItem()->addEntry($res);
        $em->persist($newItem);
        $res->setvocabID($vocabID);
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
if(isset($_REQUEST['vocabID']))
{
    $vocabID = $_REQUEST['vocabID'];
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

            $newItem=$parentEntry->getItem()->addEntry($med);
            $em->persist($newItem);
            $em->persist($med);
            $em->flush();
            $result = $med->getUUID();
    }
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
        $obs = findObservationOrCreate($em,$parentEntry,$vocabID,$pat,$user);
    }
    $obs->setValue($val);
    $obs->setText($content,$auth);
    $em->persist($obs);
    $em->flush();
    $result = $obs->getUUID();
}

if($EntryType=="problem")
{
    if(isset($_REQUEST['code']))
    {
        $code = $_REQUEST['code'];
    }
    if($task=="create")
    {
        if($codeType==null)
        {
            $codeType = "ICD9";
        }
        if($pat!=null)
        {
            if($parentEntry!=null)
            {
                $prob = new library\doctrine\Entities\Problem(null,$pat,$user);
                $prob->setCode($code,$codeType);
                $prob->setText($content,$user);
                $newItem=$parentEntry->getItem()->addEntry($prob);
                $em->persist($newItem);
                $em->persist($prob);
                $em->flush();
                $result = $prob->getUUID();
                $newEntry = $prob;
            }
            else
            {
                echo "error:"." No Parent Entry.";
                return;
            }
        }
        else
        {
            echo  "error:"." No Patient.";
            return;
        }
    }
}

if($EntryType=="narrative")
{
    if($task=="create")
    {
        $newNarrative=new library\doctrine\Entities\Narrative(null, $pat, $user);
        $newNarrative->setText($content,$user);
        $newItem=$parentEntry->getItem()->addEntry($newNarrative);
        $em->persist($newItem);
        $em->persist($newNarrative);
        $em->flush();

        echo $newNarrative->getUUID();

    }
    if($task=="update")
    {
        if(isset($_REQUEST['docEntryUUID']))
        {
            $docEntryUUID = $_REQUEST['docEntryUUID'];
            $docEntry = $em->getRepository('library\doctrine\Entities\Narrative')->find($docEntryUUID);
            $docEntry->setText($content,$user);
            $em->flush();
        }
    }
}

if(isset($_REQUEST['refresh']))
{
    $request=$_REQUEST['refresh'];
    if($request==="YES")
    {
        $docEntryDOM =  new DOMDocument("1.0","utf-8");
        $body=$docEntryDOM->createElement("BODY");
        $DOMNode= generateEditorDOM($docEntryDOM,$body,$parentEntry->getItem(),1);
        echo $docEntryDOM->saveXML($DOMNode);
        return;
    }
    elseif($request==="ENTRY")
    {
        $docEntryDOM =  new DOMDocument("1.0","utf-8");
        $DOMNode=generateDOM($docEntryDOM,$newItem);
        echo $docEntryDOM->saveXML($DOMNode);
        return;

    }
}
echo $result;

?>
