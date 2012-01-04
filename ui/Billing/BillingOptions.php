<?php
require_once("BillingOptionsUI.php");
$DOM = new DOMDocument("1.0","utf-8");

$sectBilling=$DOM->createElement("section");
BillingOptionsUI($DOM,$sectBilling);

echo $DOM->saveXML($sectBilling);
?>
