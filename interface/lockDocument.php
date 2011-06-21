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
$OEMRUser=$em->getRepository("library\doctrine\Entities\OEMRUser")->findOneBy(array("username"=>$user));
if($OEMRUser===null)
{
    HTTPError("Error:user not found");
    return;    
}
if(!$OEMRUser->passwordHashMatches($_POST['password']))
{
    HTTPError("Incorrect User/Password");
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
        HTTPError("Document patient does not match current patient");
        return;
    }
    if($doc->isLocked())
    {
        HTTPError("Cannot relock a document.");
        return;
        
    }
}
echo $patID.':'.$docUUID;
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
