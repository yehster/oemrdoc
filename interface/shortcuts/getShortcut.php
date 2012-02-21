<?php
require_once("/var/www/openemr/library/doctrine/init-session.php");
require_once("$doctrineroot/common/checkInfo.php");

if(isset($_REQUEST['shortcut']))
{
    $shortcut=$_REQUEST['shortcut'];
}
if($shortcut=="PATIENT")
{
    echo $pat->displayNarrative();
}

?>
