<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
include_once('../ui/Editor/EditorUtilities.php');

session_name("OpenEMR");
session_start();

if(isset($_REQUEST['docEntryUUID']))
{
    $docEntryUUID = $_REQUEST['docEntryUUID'];
    $docEntry=$em->getRepository('library\doctrine\Entities\DocumentEntry')->find($docEntryUUID);
}
if($docEntry!==null)
{
    $em->remove($docEntry);
    $em->flush();
}
if(isset($_REQUEST['refresh']))
{
    $request=$_REQUEST['refresh'];
    if($request==="YES")
    {
        $parentItem=$docEntry->getItem()->getParent();
        $docEntryDOM =  new DOMDocument("1.0","utf-8");
        $body=$docEntryDOM->createElement("BODY");
        $DOMNode= generateEditorDOM($docEntryDOM,$body,$parentItem,2);
        echo $parentItem->getEntry()->getUUID();
        echo $docEntryDOM->saveXML($DOMNode);
        return;
    }
}
?>
