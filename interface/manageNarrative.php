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

    if(isset($_REQUEST['refresh']))
{
    $request=$_REQUEST['refresh'];
    if($request==="YES")
    {
        require_once("../ui/DocumentEditor/refreshSection.php");
        refreshSection($parentEntry->getItem());
        return;
    }
}
    
?>
