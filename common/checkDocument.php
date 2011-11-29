<?php
if(isset($_REQUEST['docUUID']))
{
    $docUUID = $_REQUEST['docUUID'];
    $doc = $em->getRepository('library\doctrine\Entities\Document')->find($docUUID);
    if($doc==null)
    {
        header("HTTP/1.0 403 Forbidden");    
        echo "Document:".$docUUID;
        return;
    }
}
?>
