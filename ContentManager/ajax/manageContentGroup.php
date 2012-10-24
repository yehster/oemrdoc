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

if($task=='get_context_entries')
{
    loadContextEntries($contentGroup,$retval);
}

if(($task=='create_context_entry') || ($task=='create_content_entry'))
{
    if($task=='create_content_entry')
    {
        $createFunction="createContent_entry";
        $displayFunction="loadContentEntries";
    }
    else
    {
        $createFunction="createContext_entry";
        $displayFunction="loadContextEntries";
    }
    if(isset($_REQUEST['code']))
    {
        $code=$_REQUEST['code'];
    }
    else
    {
        header("HTTP/1.0 403 Forbidden");    
        echo "No code specified".$uuid;
        return;               
    }
    if(isset($_REQUEST['code_type']))
    {
        $code_type=$_REQUEST['code_type'];
    }
    else
    {
        header("HTTP/1.0 403 Forbidden");    
        echo "No code type specified".$uuid;
        return;               
    }

    if(isset($_REQUEST['display_text']))
    {
        $display_text=$_REQUEST['display_text'];
    }
    else
    {
        header("HTTP/1.0 403 Forbidden");    
        echo "No display text specified".$uuid;
        return;               
    }
    $new_entry=$contentGroup->$createFunction($code,$code_type,$display_text);
    $em->flush();
    $displayFunction($contentGroup,$retval);
    $retval['newEntryUUID']=$new_entry->getUUID();;
}

echo json_encode($retval);

?>
