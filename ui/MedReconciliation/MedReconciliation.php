<?php
include('/var/www/openemr/library/doctrine/init-em.php');
require_once("$doctrineroot/common/checkInfo.php");
require_once("$doctrineroot/ui/MedReconciliation/MedReconciliationUtil.php");

//echo "Hello World!";
// Find the latest unlocked MedRec created by the current user.
$mrDoc=findOrCreateMedReconciliation($em,$pat,$doctrineUser);

$url="/openemr/library/doctrine/ui/DocumentEditor/DocumentEditor.php?docUUID=".$mrDoc->getUUID();
?>
<script>
    window.onload=function() {window.location.href="<?php echo $url ?>";};
</script>