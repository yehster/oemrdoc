<?php
include_once('../init-session.php');
$lockedMessage="Cannot remove a locked document.";
require_once('util/verifyDocumentOperation.php');
try
{
    $em->beginTransaction();
    $doc->removeDocument($user);
    $em->flush();
    $em->commit();
}
catch(exception $e)
{
    $em->rollback();
    throw $e;
}
?>
