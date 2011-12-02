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


$optQty=$DOM->createElement("div","Qty");
$optQty->setAttribute("class","sigOption");
$optQty->setAttribute("func","qty");



$optSchedule=$DOM->createElement("div","schedule");
$optSchedule->setAttribute("class","sigOption");
$optSchedule->setAttribute("func","schedule");

echo $DOM->saveXML($optQty);
echo $DOM->saveXML($optSchedule);

?>
