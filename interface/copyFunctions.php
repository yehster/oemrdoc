<?php

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
        $targetParent->addEntry($copy);
        $em->persist($copy);
        $em->flush();
    }
    else
    {
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

    }
}
?>
