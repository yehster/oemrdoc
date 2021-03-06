<?php
session_name("OpenEMR");
session_start();

include_once('/var/www/openemr/library/doctrine/init-session.php');
include_once('../ui/Editor/EditorUtilities.php');



function findObservationOrCreate($em,$PE,$vocabID,$pat,$user)
{
    return findOrCreateVocabDocEntry($em,$PE,$vocabID,$pat,$user,"Observation");
}

function findOrCreateVocabDocEntry($em,$PE,$vocabID,$pat,$user,$type,$seq=-1)
{
    $parItem=$PE->getItem();
    $objType="library\doctrine\Entities\\".$type;
    $qb = $em->createQueryBuilder()
        ->select("obs")
        ->from($objType,"obs")
        ->join("obs.item","i")
        ->where("obs.vocabID=:voc")
        ->andwhere("i.parent=:par")    ;
    $qb->setParameter("voc",$vocabID);
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

function findNominativeOrCreate($em,$PE,$vocabID,$pat,$user)
{
    return findOrCreateVocabDocEntry($em,$PE,$vocabID,$pat,$user,"Nominative");
}

$user = $_SESSION['authUser'];
if($user==null)
{
    header("HTTP/1.0 403 Forbidden");
    echo "Error:no user set!";
    return;
}
if(isset($_SESSION['pid']))
{
    $patID=$_SESSION['pid'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
}

if(isset($_REQUEST['seq']))
{
    $seq=intval($_REQUEST['seq']);
}

if($pat==null)
{
    header("HTTP/1.0 403 Forbidden");    
    echo "Error:no patient set!";
    return;
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
        $obs=$em->getRepository('library\doctrine\Entities\Observation')->find($observationUUID);
    }
    
    if($obs===null)
    {
        $obs = findObservationOrCreate($em,$parentEntry,$vocabID,$pat,$user);
    }
    $obs->setValue($val);
    $obs->setText($content,$user);
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
                $em->persist($prob);
                $em->flush();
                $result = $prob->getUUID();
                $newEntry = $prob;
            }
            else
            {
                header("HTTP/1.0 403 Forbidden");
                echo "error:"." No Parent Entry.";
                return;
            }
        }
        else
        {
            header("HTTP/1.0 403 Forbidden");
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


if($EntryType=="nominative")
{
    if(isset($_REQUEST['val']))
    {
        $val = $_REQUEST['val'];
    }
    if(isset($_REQUEST['nominativeUUID']))
    {
        $nominativeUUID=$_REQUEST['nominativeUUID'];
        $nom=$em->getRepository('library\doctrine\Entities\Nominative')->find($nominativeUUID);

    }
    if($nom===null)
    {
        $nom = findNominativeOrCreate($em,$parentEntry,$vocabID,$pat,$user);
    }
    
    if($task=="update")
    {
        $nom->setText($content,$user);
        $em->persist($nom);
        $em->flush();
        
    }
    elseif($task=="delete")
    {
        $em->remove($nom);
        $em->flush();
    }
    $result = $nom->getUUID();
}

if($EntryType=="quantitative")
{
    if(isset($_REQUEST['val']))
    {
        $val = $_REQUEST['val'];
    }
    if(isset($_REQUEST['units']))
    {
        $units=urldecode($_REQUEST['units']);
    }
    if(isset($_REQUEST['quantUUID']))
    {
        $quaUUID=$_REQUEST['quantUUID'];
        $qua=$em->getRepository('library\doctrine\Entities\Nominative')->find($quaUUID);

    }
    if($qua===null)
    {
        $qua = findOrCreateVocabDocEntry($em,$parentEntry,$vocabID,$pat,$user,"QuantitativeEntry",$seq);
    }
    
    if($task=="update")
    {
        $qua->setText($content,$user);
        $qua->setValue(floatval($val),$user);
        $qua->setUnits($units);
        if(isset($seq))
        {
            $item=$qua->getItem();
            if($item!=null)
            {
                $item->setSeq($seq);                      
            }
        }
        $em->persist($qua);
        $em->flush();
        
    }
    elseif($task=="delete")
    {
        $em->remove($nom);
        $em->flush();
    }
    $result = $qua->getUUID();
}

if(isset($_REQUEST['refresh']))
{
    $request=$_REQUEST['refresh'];
    if($request==="YES")
    {
        $docEntryDOM =  new DOMDocument("1.0","utf-8");
        $body=$docEntryDOM->createElement("BODY");
        $DOMNode= generateEditorDOM($docEntryDOM,$body,$parentEntry->getItem(),2);
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

$em->close();
?>
