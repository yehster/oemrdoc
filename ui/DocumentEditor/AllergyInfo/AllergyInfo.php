<?php

function isAllergySection($docEntry)
{
    return (($docEntry->getCode()=='A8380263') || ($docEntry->getCode()=='A7873398'));
}

function createAllergyReview($DOM,$parent,$entry)
{
    $review=$DOM->createElement("SPAN","hello world!");
    $parent->appendChild($review);
}
?>
