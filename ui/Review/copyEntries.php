<?php
session_name("OpenEMR");
session_start();

include_once('/var/www/openemr/library/doctrine/init-em.php');
function copyEntry($target,$sourceInfo)
{
    $sourceUUID=strtok($sourceInfo,"|");
    echo "\n".$sourceUUID;
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
    foreach($toks as $sourceInfo)
    {
        copyEntry($targetEntry,$sourceInfo);
    }

?>
