<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
include_once('../ui/DocumentEditor/DocumentEditorUtilities.php');

session_name("OpenEMR");
session_start();

if(isset($_REQUEST['entryUUID']))
{
    $docEntryUUID = $_REQUEST['entryUUID'];
    $docEntry=$em->getRepository('library\doctrine\Entities\DocumentEntry')->find($docEntryUUID);
    $parentItem=$docEntry->getItem()->getParent();
}
if(!is_null($docEntry))
{
    try
    {
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
if(isset($_REQUEST['refresh']))
{
    $request=$_REQUEST['refresh'];
    if($request==="YES")
    {
        $docEntryDOM =  new DOMDocument("1.0","utf-8");
        $span=$docEntryDOM->createElement("SPAN");
        $DOMNode= populateEditorDOM($docEntryDOM,$span,$parentItem,2);
        echo $parentItem->getEntry()->getUUID();
        echo $docEntryDOM->saveXML($DOMNode);
        return;
    }
}
?>
