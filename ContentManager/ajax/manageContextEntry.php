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
   $contextEntry =$em->getRepository('library\doctrine\Entities\ContentMapping\ContextEntry')->find($uuid);
   $contentGroup=$contextEntry->getContent_group();
}
if(empty($contextEntry))
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No context entry found with UUID:".$uuid;
    return;       
    
}
$retval=[];
if($task=="delete")
{
    $em->remove($contextEntry);
    $em->flush();
}
if($task=="move")
{   
    if(isset($_REQUEST['direction']))
    {
        $direction=$_REQUEST['direction'];
    }
    else
    {
        header("HTTP/1.0 403 Forbidden");    
        echo "No direction specified:".$uuid;
        return;               
    }
    $contextEntries=$contentGroup->getContext_entries();
    $entry_index=$contextEntries->indexOf($contextEntry);
    $num_entries=count($contextEntries);
    if($entry_index===false)
    {
        header("HTTP/1.0 403 Forbidden");    
        echo "Could Not Find Entry".$uuid;
        return;                      
    }
    $swap=-1;
    if($direction=='down')
    {
        if($entry_index <($num_entries-1))
        {
            $swap=$entry_index+1;
        }
    }
    else if($direction=='up')
    {
        if($entry_index > 0)
        {
            if($entry_index)
            $swap=$entry_index - 1;
        }
    }
    if($swap!=-1)
    {
        $otherEntry=$contextEntries->get($swap);
        $swapSeq=$otherEntry->getSeq();
        $otherEntry->setSeq($contextEntry->getSeq());
        $contextEntry->setSeq($swapSeq);
        $em->flush();
        $em->refresh($contentGroup);
    }
        
}

loadContextEntries($contentGroup,$retval);

echo json_encode($retval);
?>
