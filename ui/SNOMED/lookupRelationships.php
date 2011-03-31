<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');

if(isset($_REQUEST['aui']))
{
    $aui = $_REQUEST['aui'];
    $sourceCode=$aui;
    $sourceType="SNOMED";
    $concept=$em->getRepository('library\doctrine\Entities\SNOMED\Concept')->find($aui);
}

$relationships=$concept->getRelationships1();
echo print_r($relationships);
echo $concept->getAUI();

echo $aui;
?>
