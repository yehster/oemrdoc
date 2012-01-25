<?php
require_once("../manageParentEntry.php");

if($task=="CREATE")
{
        $newImageEntry=new library\doctrine\Entities\ImageEntry(null, $pat, $user);
        $newImageEntry->setText("Hello World!",$user);
        $newItem=$parentEntry->getItem()->addEntry($newImageEntry);
        $em->persist($newImageEntry);
        $em->flush();
        
}
require_once("../refreshCheckJSONParent.php"); 
?>
