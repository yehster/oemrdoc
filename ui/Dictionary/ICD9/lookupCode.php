<?php
include_once('/var/www/openemr/library/doctrine/init-session.php');
require_once('../problemsLayout.php');



if(isset($_REQUEST["codeText"]))
{
    $codeText= $_REQUEST["codeText"];    
}

?>
