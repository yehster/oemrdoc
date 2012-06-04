<?php
include_once('/var/www/openemr/library/doctrine/init-session.php');
require_once('utilities.php');
session_write_close();
if(isset($_REQUEST['docUUID']))
{
    $docUUID = $_REQUEST['docUUID'];
    $doc = $em->getRepository('library\doctrine\Entities\Document')->find($docUUID);
    if($doc==null)
    {
            header("HTTP/1.0 403 Forbidden");    
            echo "No document found:".$docUUID;
            return;
    }
}

$patient=$doc->getPatient();

$DOM=new DOMDocument("1.0","utf-8");
$html=$DOM->createElement("html");
$head=$DOM->createElement("head");

$html->appendChild($head);


$title=$DOM->createElement("title",$doc->getMetadata()->getLongDesc()." : ".$patient->displayName());
$head->appendChild($title);

addScript($DOM,$head,"/openemr/library/js/jquery-1.7.1.min.js");
addStyle($DOM,$head,"display.css");
addStyle($DOM,$head,"icd9/icd9problems.css");

$body=$DOM->createElement("body");
$html->appendChild($body);

$docInfo=$DOM->createElement("section",$doc->getMetadata()->getLongDesc()." : ".$patient->displayName());
$body->appendChild($docInfo);

$MainSpan=$DOM->createElement("section");
$MainSpan->setAttribute("id","main");
$body->appendChild($MainSpan);

foreach($doc->getItems() as $docItem)
{
    populateEditorDOM($DOM,$MainSpan,$docItem,1);
}    



addScript($DOM,$body,"debugTools.js");
addScript($DOM,$body,"icd9/icd9problems.js");
addScript($DOM,$body,"displayEvents.js");
addScript($DOM,$body,"displayReady.js");
?>
<!DOCTYPE html>
<?php echo $DOM->saveHTML($html);?>
