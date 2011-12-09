<?php
require_once("/var/www/openemr/library/doctrine/interface/manageParentEntry.php");

$user = $_SESSION['authUser'];
if($user==null)
{
    header("HTTP/1.0 403 Forbidden");
    echo "Error:no user set!";
    return;
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

echo "Hello World!"
?>
