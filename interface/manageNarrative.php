<?php
require_once("manageParentEntry.php");
if(isset($_REQUEST['content']))
{
        $content = $_REQUEST['content'];
}

    if($task=="create")
    {
        $newNarrative=new library\doctrine\Entities\Narrative(null, $pat, $user);
        $newNarrative->setText($content,$user);
        $newItem=$parentEntry->getItem()->addEntry($newNarrative);
        $em->persist($newNarrative);
        $em->flush();

        echo $newNarrative->getUUID();

    }
    if($task=="update")
    {
        if(isset($_REQUEST['docEntryUUID']))
        {
            $docEntryUUID = $_REQUEST['docEntryUUID'];
            $docEntry = $em->getRepository('library\doctrine\Entities\Narrative')->find($docEntryUUID);
            $docEntry->setText($content,$user);
            $em->flush();
        }
    }

require_once("refreshCheck.php");    
?>
