<?php
require_once("manageParentEntry.php");

    if($task=="create")
    {
        $newNarrative=new library\doctrine\Entities\Narrative(null, $pat, $user);
        $newNarrative->setText($content,$user);
        $newItem=$parentEntry->getItem()->addEntry($newNarrative);
        $em->persist($newNarrative);
        $em->flush();

        echo $newNarrative->getUUID();

    }

require_once("refreshCheck.php");    
?>
