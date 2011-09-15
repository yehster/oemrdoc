<?php
require_once('/var/www/openemr/library/doctrine/init-em.php');
require_once('/var/www/openemr/library/doctrine/ui/Editor/EditorUtilities.php');
require_once('checkAuth.php');

if(isset($_REQUEST["task"]))
{
    $task=$_REQUEST["task"];
}
else
{
    header("HTTP/1.0 403 Forbidden");
    echo "No task specified!";
    return;
}

if(isset($_REQUEST["parentUUID"]))
{
    $parentUUID = $_REQUEST["parentUUID"];
    $parentEntry =$em->getRepository('library\doctrine\Entities\DocumentEntry')->find($parentUUID);
}

?>
