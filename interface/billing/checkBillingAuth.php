<?php
require_once('/var/www/openemr/library/doctrine/init-em.php');
$user = $_SESSION['authUser'];
if($user==null)
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No user set!";
    return;
}


?>
