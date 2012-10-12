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
   $contentEntry =$em->getRepository('library\doctrine\Entities\ContentMapping\ContentEntry')->find($uuid);
}
if(empty($contentEntry))
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No content entry found with UUID:".$uuid;
    return;       
    
}

?>
