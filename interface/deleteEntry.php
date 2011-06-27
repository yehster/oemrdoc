<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');

session_name("OpenEMR");
session_start();

if(isset($_REQUEST['entryUUID']))
{
    $docEntryUUID = $_REQUEST['entryUUID'];
    $docEntry=$em->getRepository('library\doctrine\Entities\DocumentEntry')->find($docEntryUUID);
}
if(!is_null($docEntry))
{
    try
    {
        $parentItem=$docEntry->getItem()->getParent();
        $em->remove($docEntry->getItem());
        $em->remove($docEntry);
        $em->flush();
    }
    catch(Exception $e)
    {
        header("HTTP/1.0 403 Forbidden");
        echo $e->getMessage();
        return;

    }
}
 else {
        header("HTTP/1.0 403 Forbidden");
        echo $docEntryUUID." entry does not exist!";
        return;

}
if(isset($_REQUEST['refresh']))
{
    $request=$_REQUEST['refresh'];
    if($request==="YES")
    {
        echo $parentItem->getEntry()->getUUID();
        require_once("../ui/DocumentEditor/refreshSection.php");
        refreshSection($parentItem);
        return;
    }
}
?>
