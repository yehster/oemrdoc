<?php
require_once('/var/www/openemr/library/doctrine/init-em.php');
require_once("$doctrineroot/common/checkInfo.php");
require_once('PatientEventsUtil.php');


$DOM=new DOMDocument("1.0","utf-8");
$events=$DOM->createElement("section");
$eventsElem= generate_patient_events($em,$DOM,$events,$pat);

echo $DOM->saveXML($eventsElem);

?>
