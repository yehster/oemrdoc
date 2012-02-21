<?php
include('/var/www/openemr/library/doctrine/init-session.php');
require_once("$doctrineroot/common/checkInfo.php");
require_once('MedicationWidgetUtil.php');

$DOM=new DOMDocument("1.0","utf-8");
$list=ListMeds($DOM,$em,$pat);

echo $DOM->saveXML($list);

?>
