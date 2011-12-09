<?php
require_once("$doctrineroot/queries/MedicationQueries.php");

function createButton($DOM,$parent,$function,$label)
{
    $retval=$DOM->createElement("button",$label);
    $retval->setAttribute("func",$function);
    $parent->appendChild($retval);
    return $retval;
}
function statusOptions($DOM,$parent)
{
    createButton($DOM,$parent,"DISCONTINUE","dc'd");
    createButton($DOM,$parent,"CONFIRM","Confirmed");
}

function ControlsForMed($DOM,$parent,$med,$rootUUID)
{
    $tr=$DOM->createElement("tr");
    $tr->setAttribute("meduuid",$med->getUUID());
    $parent->appendChild($tr);
    $tdMedInfo=$DOM->createElement("td",$med->getText());
    $tr->appendChild($tdMedInfo);

    $SIG=$med->getSIGs();
    $tdSIG=$DOM->createElement("td",$SIG[0]->getText());
    $tr->appendChild($tdSIG);
    
    if($med->getItem()->getRoot()->getUUID()!=$rootUUID)
    {
        $tdStatusOptions=$DOM->createElement("td");
        statusOptions($DOM,$tdStatusOptions);
        $tr->appendChild($tdStatusOptions);
    }
}

function ReviewMeds($em,$DOM,$pat,$section)
{
    $retVal=$DOM->createElement("SECTION");
    $retVal->setAttribute("class","MedicationReview");
    
    $table=$DOM->createElement("TABLE");
    $retVal->appendChild($table);
    $tbody=$DOM->createElement("TBODY");
    $table->appendChild($tbody);
    $meds=findMedications($em,$pat);
    if($section!=null)
    {
        $parentItem=$section->getItem();
        $parentUUID=$parentItem->getUUID();
        $rootUUID=$parentItem->getRoot()->getUUID();
    }
    else
    {
        $parentItem==null;
    }
    foreach($meds as $curMed)
    {
        if(($parentItem==null) || ($parentUUID!==$curMed->getItem()->getParent()->getUUID()))
        {
        ControlsForMed($DOM,$tbody,$curMed,$rootUUID);
        }
    }
    return $retVal;
}
?>
