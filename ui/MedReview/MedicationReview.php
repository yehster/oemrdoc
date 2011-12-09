<?php
include('/var/www/openemr/library/doctrine/init-em.php');
require_once("$doctrineroot/common/checkInfo.php");
require_once('MedicationReviewUtil.php');

if(isset($_REQUEST['sectionUUID']))
{
    $sectionUUID = $_REQUEST["sectionUUID"];
    $section =$em->getRepository('library\doctrine\Entities\DocumentEntry')->find($sectionUUID);
}

$DOM=new DOMDocument("1.0","utf-8");
$review=ReviewMeds($em,$DOM,$pat,$section);

echo $DOM->saveXML($review);

?>
<script src="/openemr/library/doctrine/ui/MedReview/MedicationReview.js"></script>
