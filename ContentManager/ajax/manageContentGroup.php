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
    loadContentEntries($contentGroup,$retval);
}

if($task=='create_content_entry')
{
    if(isset($_REQUEST['content_code']))
    {
        $content_code=$_REQUEST['content_code'];
    }
    else
    {
        header("HTTP/1.0 403 Forbidden");    
        echo "No content code specified".$uuid;
        return;               
    }
    if(isset($_REQUEST['content_code_type']))
    {
        $content_code_type=$_REQUEST['content_code_type'];
    }
    else
    {
        header("HTTP/1.0 403 Forbidden");    
        echo "No content code type specified".$uuid;
        return;               
    }

    if(isset($_REQUEST['content_display_text']))
    {
        $content_display_text=$_REQUEST['content_display_text'];
    }
    else
    {
        header("HTTP/1.0 403 Forbidden");    
        echo "No content display text specified".$uuid;
        return;               
    }
    $new_content_entry=$contentGroup->createContent_entry($content_code,$content_code_type,$content_display_text);
    $em->flush();
    loadContentEntries($contentGroup,$retval);
    $retval['contentEntryUUID']=$new_content_entry->getUUID();;
}

echo json_encode($retval);

?>
