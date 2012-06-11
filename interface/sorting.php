<?php
require_once('/var/www/openemr/library/doctrine/init-session.php');
require_once('checkAuth.php');

if(isset($_REQUEST["task"]))
{
    $task=$_REQUEST["task"];
}
else
{
    header("HTTP/1.0 403 Forbidden");
    echo "No task specified!";
    return;
}

if(isset($_REQUEST["entryUUID"]))
{
    $entryUUID = $_REQUEST["entryUUID"];
    $entry =$em->getRepository('library\doctrine\Entities\DocumentEntry')->find($entryUUID);
}
if($entry==null)
{
    header("HTTP/1.0 403 Forbidden");
    echo "No valid Entry!";
    return;
}

function changeOrder($em,$entry,$direction)
{
    $docItem=$entry->getItem();
    $parentItem=$docItem->getParent();
    $items=$parentItem->getItems();
    $idx=$items->indexOf($docItem);
    $swapIdx=$idx+$direction;
    if(($swapIdx<0) || ($swapIdx>=count($items)))
    {
        return false;
    }
    $em->beginTransaction();
    $tmp=$items->get($swapIdx);
    $tmpSeq=$tmp->getSeq();
    $items->set($swapIdx,$docItem);
    $tmp->setSeq($docItem->getSeq());
    $docItem->setSeq($tmpSeq);
    $items->set($idx,$tmp);
    $em->flush();        
    $em->commit();    

    return true;
    
    
}

if($task=="UP")
{
    $direction=-1;
}
elseif($task=="DOWN")
{
    $direction=1;
}
else
{
    header("HTTP/1.0 403 Forbidden");
    echo "Invalid Direction Specifier!";
    return;  
}
if(!changeOrder($em,$entry,$direction))
{
    header("HTTP/1.0 403 Forbidden");
    echo "Failed Changing Order";
    return;  
}
    $docItem=$entry->getItem();
    $parentItem=$docItem->getParent();
 
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
    if($request==="doc")
    {
        $parentEntry=$parentItem->getEntry();
        require_once("refreshCheck.php");   
    }
}    
?>