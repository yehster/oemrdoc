<?php


function ListMeds($DOM,$em,$pat)
{
    $meds=findMedications($em,$pat);

    $list=$DOM->createElement("ul");

    for($idx=0;$idx<count($meds);$idx++)
    {
            $cur=$meds[$idx];
            $medLI=$DOM->createElement("li",$cur->getText());
            $list->appendChild($medLI);
    }
    return $list;
    
}
?>
