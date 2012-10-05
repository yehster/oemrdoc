<?php
require_once('/var/www/openemr/library/doctrine/init-session.php');
require_once('contentGroupUtilities.php');

if(isset($_REQUEST['task']))
{
    $task=$_REQUEST['task'];
}
 else 
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No task specified";
    return;       
}

if(isset($_REQUEST['document_code']))
{
    $document_code=$_REQUEST['document_code'];
}
 else 
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No document code specified";
    return;       
}

if(isset($_REQUEST['document_code_type']))
{
    $document_code_type=$_REQUEST['document_code_type'];
}
 else 
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No document code type specified";
    return;       
}

$retval=array();
if($task=='search')
{
    $groups=findContentGroups($document_code,$document_code_type);
    $retval['result_count']=count($groups);
    $descriptions=[];
    $uuids=[];
    foreach($groups as $group)
    {
        $uuids[]=$group->getUUID();
        $descriptions[]=$group->getDescription();
    }
    $retval['uuids']=$uuids;
    $retval['descriptions']=$descriptions;
    echo json_encode($retval);
}
else if($task="create")
{
    if(isset($_REQUEST['description']))
    {
        $description=$_REQUEST['description'];
    }
    else
    {
        $description="New Group for:".$document_code;
    }
    $cb=$_SESSION['authUser'];
    $newGroup=new library\doctrine\Entities\ContentMapping\ContentGroup($cb,$document_code,$document_code_type,$description);
    $em->persist($newGroup);
    $em->flush();
}
?>
