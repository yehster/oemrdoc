<?php

function reviewRecurse($em,$DOM,$parent,$DocItem)
{
    $DocEntry=$DocItem->getEntry();
    $text=htmlentities($DocEntry->getText());
    $nextParent=$DOM->createElement("DIV",$text);
    $nextParent->setAttribute("entrytype",$DocEntry->getType());
    $parent->appendChild($nextParent);
    foreach($DocItem->getItems() as $childItem)
    {
            reviewRecurse($em,$DOM,$nextParent,$childItem);
    }
}
function generateReviewDOM($em,$DOM,$section)
{
    $retVal = $DOM->createElement("SPAN");
    $DOM->appendChild($retVal);
    reviewRecurse($em,$DOM,$retVal,$section->getItem());
    return $retVal;
}

?>
