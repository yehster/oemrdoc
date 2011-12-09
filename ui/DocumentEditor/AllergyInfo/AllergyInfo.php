<?php

function isAllergySection($docEntry)
{
    return (($docEntry->getCode()=='A8380263') || ($docEntry->getCode()=='A7873398'));
}

function createAllergyReview($DOM,$parent,$entry)
{
    $review=$DOM->createElement("SECTION"," ");
    $review->setAttribute("class","infoReview");
    $review->setAttribute("infoUUID",$entry->getUUID());
    $parent->appendChild($review);
    $script=$DOM->createElement("SCRIPT","updateInfoReviewSection('".$entry->getPatient()->getID()."','".$entry->getUUID()."');");
    $parent->appendChild($script);
    
}
?>
