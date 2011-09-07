<?php
include_once('../init-em.php');
$lockedMessage="Cannot relock a document.";
require_once('util/verifyDocumentOperation.php');

try
{
    $em->beginTransaction();
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
