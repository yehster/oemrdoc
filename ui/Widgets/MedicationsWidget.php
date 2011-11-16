<?php
include('/var/www/openemr/library/doctrine/init-em.php');
require_once("checkInfo.php");
require_once('../../queries/MedicationQueries.php');
require_once('MedicationWidgetUtil.php');

$DOM=new DOMDocument("1.0","utf-8");
$list=ListMeds($DOM,$em,$pat);

echo $DOM->saveXML($list);

?>
