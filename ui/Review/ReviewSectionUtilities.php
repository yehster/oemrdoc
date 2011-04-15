<?php

function createEntry($DOM,$parent,$DocEntry,$depth,$type,$text)
{
    $newEntry = $DOM->createElement($type,$text);
    $newEntry->setAttribute("reviewtype",$DocEntry->getType());
    $newEntry->setAttribute("uuid",$DocEntry->getUUID());
    $newEntry->setAttribute("depth",$depth);
    $parent->appendChild($newEntry);

    return $newEntry;
}

function createCheckbox($DOM,$parent,$DocEntry,$depth)
{
    $retVal=createEntry($DOM,$parent,$DocEntry,$depth,"INPUT","");
    $retVal->setAttribute("TYPE","CHECKBOX");
    $retVal->setAttribute("checked","true");
    return $retVal;
}
function reviewRecurse($em,$DOM,$parent,$DocItem,$depth)
{
    $DocEntry=$DocItem->getEntry();
    $text=htmlentities($DocEntry->getText());
    $nextParent=createEntry($DOM,$parent,$DocEntry,$depth,"div","");
    $checkBox=createCheckbox($DOM,$nextParent,$DocEntry,$depth);
    $info=createEntry($DOM,$nextParent,$DocEntry,$depth,"span",$text);

    foreach($DocItem->getItems() as $childItem)
    {
            reviewRecurse($em,$DOM,$nextParent,$childItem,$depth+1);
    }
}
function generateReviewDOM($em,$DOM,$section)
{
    $retVal = $DOM->createElement("SPAN");
    $DOM->appendChild($retVal);
    reviewRecurse($em,$DOM,$retVal,$section->getItem(),1);
    return $retVal;
}

?>
