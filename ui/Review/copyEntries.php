<?php
session_name("OpenEMR");
session_start();

include_once('/var/www/openemr/library/doctrine/init-em.php');
function findTargetParent($sourceParent,$targetEntry,$maxDepth,$depth)
{
    if($sourceParent->similar($targetEntry))
    {
        return $targetEntry;
    }

    if($depth<$maxDepth)
    {
        $items=$targetEntry->getItem()->getItems();
        foreach($items as $item)
        {
            $childEntry=$item->getEntry();
            $retval = findTargetParent($sourceParent,$childEntry,$maxDepth,$depth+1);
            if($retval!=null)
            {
                return $retval;
            }
        }
    }
    return null;
    
}
function findOrCopy($targetTop,$source,$depth)
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
        $targetParent=findTargetParent($sourceParent,$targetTop,$depth,1);
        if($targetParent!==null)
        {
            echo '\nfound:'.$targetParent->getUUID().":".$targetParent->getText().":source:".$source->getText()."\n";
        }
    }
}
function copyEntries($em,$target,$sourceInfoArray)
{
    foreach($sourceInfoArray as $sourceInfo)
    {
        $sourceUUID=strtok($sourceInfo,"|");
        $sourceEntry = $em->getRepository('library\doctrine\Entities\DocumentEntry')->find($sourceUUID);
        $depth=strtok("|");
        $copy=findOrCopy($target,$sourceEntry,$depth);

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
    copyEntries($em,$targetEntry,$toks);

?>
