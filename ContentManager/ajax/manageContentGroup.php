<?php
require_once('/var/www/openemr/library/doctrine/init-session.php');

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
if(isset($_REQUEST['uuid']))
{
   $uuid= $_REQUEST['uuid'];
   $contentGroup =$em->getRepository('library\doctrine\Entities\ContentMapping\ContentGroup')->find($uuid);
}
if(empty($contentGroup))
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No content group found with UUID:".$uuid;
    return;       
    
}
$retval = [];
if($task=="rename")
{
    if(isset($_REQUEST['newName']))
    {
        $newName=$_REQUEST['newName'];
    }
    $contentGroup->setDescription($newName);
    $retval['newValue']=$contentGroup->getDescription();
    $em->flush();
}
if($task=='get_content_entries')
{
    
}

echo json_encode($retval);

?>
