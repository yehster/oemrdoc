<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');

if(isset($_REQUEST['currentSectionUUID']))
{
    $currentSectionUUID = $_REQUEST['currentSectionUUID'];
    $currentSection = $em->getRepository('library\doctrine\Entities\Section')->find($currentSectionUUID);


    
}


if(isset($_REQUEST['historicalSectionUUID']))
{
    $historicalSectionUUID = $_REQUEST['historicalSectionUUID'];
    $historicalSection = $em->getRepository('library\doctrine\Entities\Section')->find($historicalSectionUUID);
}

?>
