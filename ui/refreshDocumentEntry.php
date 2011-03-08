<?php
include('/var/www/openemr/library/doctrine/init-em.php');
include_once('DocumentUtilities.php');
/* This page when passed a document uuid will load the document and provide the
 * user interface for editing
*/
        if(isset($_REQUEST['docEntryUUID']))
        {
                $docEntryUUID = $_REQUEST['docEntryUUID'];
                $docEntry = $em->getRepository('library\doctrine\Entities\DocumentEntry')->find($docEntryUUID);
        }
        $docEntryDOM =  new DOMDocument("1.0","utf-8");
        generateDOM($docEntryDOM,$docEntry->getItem());
        echo $docEntryDOM->saveXML();
?>
