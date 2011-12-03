<?php

require_once('/var/www/openemr/library/doctrine/init-em.php');
require_once('/var/www/openemr/library/doctrine/interface/checkAuth.php');

$DOM = new DOMDocument("1.0","utf-8");
if(isset($_REQUEST['medSIGUUID']))
{
    $medSIG=$em->getRepository('library\doctrine\Entities\MedicationSIG')->find($_REQUEST['medSIGUUID']);
}
if(is_null($medSIG))
{
    header("HTTP/1.0 403 Forbidden");      
    echo "No SIG specified!";
    return;
}


$optQty=$DOM->createElement("div");
$optQty->setAttribute("class","sigOption");
$optQty->setAttribute("func","qty");

$tblQty=$DOM->createElement("table");
$optQty->appendChild($tblQty);
$tbodyQty=$DOM->createElement("tbody");
$tblQty->appendChild($tbodyQty);
for($idx=1;$idx<5;$idx++)
{
    $tr=$DOM->createElement("tr");
    $tbodyQty->appendChild($tr);
    $td=$DOM->createElement("td",$idx);
    $tr->appendChild($td);
}


    $scheduleOptions = array(array("daily","twice a day","three times a day","four times a day")
        ,array("as needed")
        ,array("every 3 hours","every 4 hours","every 6 hours","every 8 hours","every 12 hours","every other day")
        ,array("in the morning","in the evening","at bedtime","before meals","after meals")
        );

$optSchedule=$DOM->createElement("div");
$optSchedule->setAttribute("class","sigOption");
$optSchedule->setAttribute("func","schedule");

$schTable=$DOM->createElement("table");
$optSchedule->appendChild($schTable);
$schTbody=$DOM->createElement("tbody");
$schTable->appendChild($schTbody);
foreach($scheduleOptions as $schRow)
{
    $schTR=$DOM->createElement("tr");
    $schTbody->appendChild($schTR);
    foreach($schRow as $schOpt)
    {
        $schTD=$DOM->createElement("td",$schOpt);
        $schTR->appendChild($schTD);
    }
}



echo $DOM->saveXML($optQty);
echo $DOM->saveXML($optSchedule);

?>
