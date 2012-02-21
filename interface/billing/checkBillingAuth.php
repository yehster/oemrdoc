<?php
require_once('/var/www/openemr/library/doctrine/init-session.php');
$user = $_SESSION['authUser'];
if($user==null)
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No user set!";
    return;
}

$doctrineUser=$em->getRepository('library\doctrine\Entities\User')->findOneBy(array("username"=>$user));

if($doctrineUser==null)
{
    header("HTTP/1.0 403 Forbidden");    
    echo "Invalid User!";
    return;    
}


?>
