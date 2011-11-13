<?php

if(isset($_REQUEST['refresh']))
{
    $request=$_REQUEST['refresh'];
    if($request==="YES")
    {
        require_once("/var/www/openemr/library/doctrine/ui/DocumentEditor/refreshSection.php");
        echo refreshSection($parentEntry->getItem());
        return;
    }
}

?>
