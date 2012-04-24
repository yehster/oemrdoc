<!DOCTYPE html>
<style type="text/css" media="all">
   @import "PatientDashboard.css";
</style>
<html>
    <head>
        <title>Dashboard</title>
    </head>    
    <body>        
<script src="/openemr/library/js/jquery-1.7.1.min.js"></script>        
<?php
require_once("/var/www/openemr/library/doctrine/init-session.php");
require_once("$doctrineroot/common/checkUser.php");
require_once("$doctrineroot/ui/PatientDashboard/PatientDashboardUtil.php");

$DOM = new DOMDocument("1.0","utf-8");

$users=null;
$patient_status_table=create_status_table($em,$DOM,$users);

echo $DOM->saveXML($patient_status_table);
?>

<script src="<?php echo $doctrinewebroot?>/ui/PatientDashboard/PatientDashboard.js"></script>
    </body>
</html>
