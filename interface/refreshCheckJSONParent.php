<?php

if(isset($_REQUEST['refresh']))
{
    $request=$_REQUEST['refresh'];
    if($request==="YES")
    {
        $item=$parentEntry->getItem();
        require_once("$doctrineroot/ui/DocumentEditor/refreshSection.php");
        echo json_encode(array("uuid"=>$parentEntry->getUUID(),
            "html"=>refreshSection($item)));
        return;
    }
}  

?>
