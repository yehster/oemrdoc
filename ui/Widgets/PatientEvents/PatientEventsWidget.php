<?php
require_once('/var/www/openemr/library/doctrine/init-session.php');
require_once("$doctrineroot/common/checkInfo.php");
require_once('PatientEventsUtil.php');
require_once("$doctrineroot/queries/PatientEventQueries.php");

$DOM=new DOMDocument("1.0","utf-8");

$patientEventResults=PatientEvent($em,$pat);
if(count($patientEventResults)==1)
{
    $patientEvent=$patientEventResults[0];
}
else
{
    $patientEvent=null;
}
$eventsElem= generate_patient_events($em,$DOM,$null,$pat,$patientEvent);

echo $DOM->saveXML($eventsElem);

?>
<script>
    var oemr_pat_id=<?php echo $_REQUEST['patientID']; ?>;
    var oemr_webroot="<?php echo $doctrinewebroot; ?>";
</script>
<script src="<?php echo $doctrinewebroot?>/ui/Widgets/PatientEvents/PatientEventWidget.js"> </script>
