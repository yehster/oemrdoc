<?php
require_once('/var/www/openemr/library/doctrine/init-em.php');
require_once('/var/www/openemr/library/doctrine/interface/checkAuth.php');

require_once("dynamicMedSIGDialog.php");
if(isset($_REQUEST["medEntryUUID"]))
{
    $medEntryUUID = $_REQUEST["medEntryUUID"];
    $medEntry =$em->getRepository('library\doctrine\Entities\DocumentEntry')->find($medEntryUUID);
    
}
$DOM = new DOMDocument("1.0","utf-8");
$dialog = generateMedSIGDialog($DOM,$medEntry,$pat,$user);

echo $DOM->saveXML($dialog);

?>
 