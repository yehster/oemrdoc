<?php

function reviewRecurse($em,$DOM,$parent,$DocItem,$depth)
{
    $DocEntry=$DocItem->getEntry();
    $text=htmlentities($DocEntry->getText());
    $nextParent=$DOM->createElement("DIV",$text);
    $nextParent->setAttribute("reviewtype",$DocEntry->getType());
    $nextParent->setAttribute("depth",$depth);

    $parent->appendChild($nextParent);
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
