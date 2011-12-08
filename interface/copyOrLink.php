<?php
require_once("manageParentEntry.php");
$user = $_SESSION['authUser'];
if($user==null)
{
    header("HTTP/1.0 403 Forbidden");
    echo "Error:no user set!";
    return;
}

if(isset($_REQUEST['copySourceUUID']))
{
    $copySourceUUID = $_REQUEST["copySourceUUID"];
    $copySource =$em->getRepository('library\doctrine\Entities\DocumentEntry')->find($copySourceUUID);
}

if($copySource->getItem()->getRoot()!=$parentEntry->getItem()->getRoot())
{
    echo "copy";
    $copy = $copySource->copy($user);
    $parentEntry->getItem()->addEntry($copy);
    if($copySource->getType()=="MedicationEntry")
    {
        foreach($copySource->getSIGs() as $SIG)
        {
            $copy->getItem()->addEntry($SIG->copy($user));
        }
    }
    $em->flush();
    
}
else
{
    echo "link";
    $link = new library\doctrine\Entities\DocumentLink($md,$pat,$user,$copySource);
    $parentEntry->getItem()->addEntry($link);
    $em->flush();
}
?>
