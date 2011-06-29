<?php
require_once("manageParentEntry.php");

if(isset($_REQUEST['rxcui']))
{
    $rxcui = $_REQUEST['rxcui'];
}
if(isset($_REQUEST['rxaui']))
{
    $rxauiID = $_REQUEST['rxaui'];
    $rxaui = $em->getRepository('library\doctrine\Entities\RXNConcept')->find($rxauiID);
}

if(isset($_REQUEST["text"]))
{
    $text = $_REQUEST["text"];
}

switch ($task)
{
    case "create":
        if(is_null($parentEntry))
        {
                header("HTTP/1.0 403 Forbidden");
                echo "No parent specified on create!";
                return;
        }
        $med = new library\doctrine\Entities\MedicationEntry(null,$pat,$user);
        $med->setText($text,$user);
        $med->setRXCUI($rxcui);
        $med->setRXAUI($rxaui);

        
        $newItem=$parentEntry->getItem()->addEntry($med);
        $em->persist($med);
        $em->flush();

        
        break;
}

require_once("refreshCheck.php");
?>
