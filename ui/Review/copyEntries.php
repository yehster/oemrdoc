<?php
session_name("OpenEMR");
session_start();

include_once('/var/www/openemr/library/doctrine/init-em.php');
function findTargetParent($sourceParent,$targetItem,$maxDepth,$depth)
{
    if($sourceParent->similar($targetItem->getEntry()))
    {
        return $targetItem;
    }

    if($depth<$maxDepth)
    {

            $items=$targetItem->getItems();
            foreach($items as $item)
            {
             $retval = findTargetParent($sourceParent,$item,$maxDepth,$depth+1);
             if($retval!=null)
             {
                 return $retval;
             }
            }
    }
    return null;
    
}
function findCopy($targetParent,$source)
{
    $items=$targetParent->getItems();
    foreach($items as $item)
    {
        $docEntry=$item->getEntry();
        if($docEntry->similar($source))
        {
            return $docEntry;
        }
    }
    return null;
}

function targetExistsOrCopy($em,$targetParent,$source,$user)
{
    $copy=findCopy($targetParent,$source);
    if($copy==null)
    {
        $copy=$source->copy($user);
        echo "\n".$copy->getText();
        $targetParent->addEntry($copy);
        $em->persist($copy);
        $em->flush();
    }
    else
    {
        echo "\n".$copy->getText().":".get_class($copy);
        if(($copy->getType()=="Narrative") and ($copy->getCode()!=null))
        {
            /* we have a code tagged narrative section and need to decided if we should
                copy it to the existing section*/
            if($copy->getText()=="")
            {
                $copy->setText($source->getText(),$user);
                $em->flush();
            }
        }
    }
    return $copy;
}
function findOrCopy($em,$targetTop,$source,$depth,$user)
{
    if($depth==1)
    {
            if($source->similar($targetTop))
            {
                $targetParent=$targetTop;
                return $targetParent;
            }
    }
    if($depth>1)
    {
        $sourceParent=$source->getItem()->getParent()->getEntry();
        $targetItem=$targetTop->getItem();
        $targetParent=findTargetParent($sourceParent,$targetItem,$depth,1);
        if($targetParent!==null)
        {
            targetExistsOrCopy($em,$targetParent,$source,$user);
        }
    }
}
function copyEntries($em,$target,$sourceInfoArray,$user)
{
    foreach($sourceInfoArray as $sourceInfo)
    {
        $sourceUUID=strtok($sourceInfo,"|");
        $sourceEntry = $em->getRepository('library\doctrine\Entities\DocumentEntry')->find($sourceUUID);
        $depth=strtok("|");
        $copy=findOrCopy($em,$target,$sourceEntry,$depth,$user);

//        echo "\n".$sourceUUID."|".$sourceEntry->getText();
    }
}
$user = $_SESSION['authUser'];
if($user==null)
{
    echo "Error:no user set!";
    return;
}
if(isset($_SESSION['pid']))
{
    $patID=$_SESSION['pid'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
}
if($pat==null)
{
    echo "Error:no patient set!";
    return;
}

if(isset($_REQUEST['targetUUID']))
{
    $targetUUID = $_REQUEST['targetUUID'];
    $targetEntry = $em->getRepository('library\doctrine\Entities\DocumentEntry')->find($targetUUID);
}

if(isset($_REQUEST['copylist']))
{
    $copylist = $_REQUEST['copylist'];
}
echo get_class($targetEntry);

    $idx=0;
    $toks = array();
    $tok = strtok($copylist,"\n");
    while($tok!==false)
    {
        $toks[$idx]=$tok;
        $idx++;
        $tok=strtok("\n");
    }
    copyEntries($em,$targetEntry,$toks,$user);
    $em->flush();
?>
