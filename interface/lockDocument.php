<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
$lockedMessage="Cannot relock a document.";
require_once('util/verifyDocumentOperation.php');
require_once("$doctrineroot/interface/med/syncMedsForLock.php");
require_once("$doctrineroot/interface/info/syncInfoForLock.php");


try
{
    $em->beginTransaction();
    
    syncMedsForLock($em,$doc,$user);
    syncInfoForLock($em,$doc,$user);
    $doc->lock($user);
    $em->flush();
    $em->commit();
}
catch(exception $e)
{
    $em->rollback();
    throw $e;
}

?>
