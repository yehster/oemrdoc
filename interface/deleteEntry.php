<?php
include_once('/var/www/openemr/library/doctrine/init-session.php');


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
        require_once("../ui/DocumentEditor/refreshSection.php");
        echo json_encode(array("uuid"=>$parentItem->getEntry()->getUUID(),
            "html"=>refreshSection($parentItem)));
        return;
    }
    elseif($request==='doc')
    {
        require_once("/var/www/openemr/library/doctrine/document/refresh_section.php");
        $retVal=array();
        if(isset($_REQUEST["requestTime"]))
        {
            $retVal['requestTime']=intval($_REQUEST["requestTime"]);  
        }
        $retVal['html']=refreshSection($parentItem);
        $retVal['uuid']=$parentItem->getEntry()->getUUID();
        echo json_encode($retVal);
        return;                

    }
}
?>
