<?php
require_once("manageParentEntry.php");
require_once('copyFunctions.php');

if(isset($_REQUEST['copylist']))
{
    $copylist = $_REQUEST['copylist'];
    
}
else
{
    header("HTTP/1.0 403 Forbidden");
    echo "No copylist specified!";
    return;

}

    $idx=0;
    $toks = array();
    $tok = strtok($copylist,"\n");
    while($tok!==false)
    {
        $toks[$idx]=$tok;
        $idx++;
        $tok=strtok("\n");
    }
    copyEntries($em,$parentEntry,$toks,$user);
    $em->flush();

    require_once("refreshCheck.php");

?>
