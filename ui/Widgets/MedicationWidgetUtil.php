<?php
require_once("$doctrineroot/queries/MedicationQueries.php");

function echoDoctrineMeds()
{
    $DOM=new \DOMDocument("1.0","utf-8");
    $patID=$_SESSION['pid'];
    $pat=$GLOBALS['em']->getRepository('library\doctrine\Entities\Patient')->find($patID);    
    $medList = ListMeds($DOM,$GLOBALS['em'],$pat);
    echo $DOM->saveXML($medList);
}
function ListMeds($DOM,$em,$pat)
{
    $meds=findMedications($em,$pat);

    $list=$DOM->createElement("ul");

    for($idx=0;$idx<count($meds);$idx++)
    {
            $cur=$meds[$idx];
            $medLI=$DOM->createElement("li",$cur->getText());
            $sigs=$cur->getSIGs();
            foreach($sigs as $sig)
            {
                $sigInfo=$DOM->createElement("DIV",$sig->getText());
                $medLI->appendChild($sigInfo);
            }
            $stat=$cur->getStatus();
            $history=$cur->getStatusHistory();
            $info=count($history);
            $medLI->appendChild($DOM->createElement("SPAN",$info));
            $list->appendChild($medLI);
    }
    return $list;
    
}
?>
