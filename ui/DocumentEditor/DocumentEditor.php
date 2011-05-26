<!DOCTYPE html>
<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
if(isset($_REQUEST['docUUID']))
{
    $docUUID = $_REQUEST['docUUID'];
    $doc = $em->getRepository('library\doctrine\Entities\Document')->find($docUUID);
    if($doc==null)
    {
        echo "Document:".$docUUID;
        return;
    }
}
    $DOM = new DOMDocument("1.0","utf-8");
    $Body=$DOM->createElement("BODY");
    
?>
<script src="jquery-1.6.1.min.js"></script>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title><?php echo $doc->getMetadata()->getLongDesc().":";
                $patient=$doc->getPatient();
                echo $patient->displayName();
                ?></title>
    </head>
    <?php
        echo $DOM->saveXML($Body);
     ?>
</html>