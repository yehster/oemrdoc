<?php
require_once('/var/www/openemr/library/doctrine/init-session.php');
require_once('checkAuth.php');
function HTTPError($msg)
{
    header("HTTP/1.0 403 Forbidden");
    echo $msg;
    return;
}
if(isset($_REQUEST["docUUID"]))
{
    $docUUID = $_REQUEST["docUUID"];
    $doc =$em->getRepository('library\doctrine\Entities\Document')->find($docUUID);

}
if(isset($_REQUEST['dateofservice']))
{
    try
    {
        $date = new \DateTime($_REQUEST['dateofservice']);
    }
    catch(Exception $e)
    {
        HTTPError("invalid date format");
        return;
    }
}
$doc->setDateofservice($date);
$em->flush();

?>
