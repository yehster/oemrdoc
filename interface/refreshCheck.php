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
    elseif($request==="doc") {       
        require_once("/var/www/openemr/library/doctrine/document/refresh_section.php");
        $retVal=array();
        if(isset($_REQUEST["requestTime"]))
        {
            $retVal['requestTime']=intval($_REQUEST["requestTime"]);  
        }
        $retVal['html']=refreshSection($parentEntry->getItem());
        $retVal['uuid']=$parentEntry->getUUID();
        echo json_encode($retVal);
        return;                
    }
}

?>
