<?php
include_once('../init-em.php');
function HTTPError($msg)
{
    header("HTTP/1.0 403 Forbidden");
    echo $msg;
    return;
}
$user = $_SESSION['authUser'];
if($user==null)
{
    HTTPError("Error:no user set!");
    return;
}
if(isset($_SESSION['pid']))
{
    $patID=$_SESSION['pid'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
}
if(isset($_REQUEST['docUUID']))
{
    $docUUID = $_REQUEST['docUUID'];
    $doc = $em->getRepository('library\doctrine\Entities\Document')->find($docUUID);
    if($doc==null)
    {
        HTTPError("Document Not Found:".$docUUID);
        return;
    }
    if($doc->getPatient()!==$pat)
    {
        HTTPError("Document patient does not match current patient:");
        return;
    }
}
echo $patID.':'.$docUUID;
try
{
    $em->beginTransaction();
    $doc->lock($authUser);
    $em->flush();
    $em->commit();
}
catch(exception $e)
{
    $em->rollback();
    throw $e;
}
?>
