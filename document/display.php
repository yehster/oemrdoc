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

DOMUtilities\addScript($DOM,$head,"/openemr/library/js/jquery-1.9.1.min.js");
DOMUtilities\addStyle($DOM,$head,"display.css");
DOMUtilities\addStyle($DOM,$head,"icd9/icd9problems.css");
DOMUtilities\addStyle($DOM,$head,"medications/medications.css");
DOMUtilities\addStyle($DOM,$head,"context/context.css");

$body=$DOM->createElement("body");
$body->setAttribute("documentUUID",$doc->getUUID());
$html->appendChild($body);

$docInfo=$DOM->createElement("section");
$docDescription=$DOM->createElement("section",$doc->getMetadata()->getLongDesc()." : ".$patient->displayName());
$docInfo->appendChild($docDescription);

$docDate=$DOM->createElement("div");
$docDateLabel=$DOM->createElement("span","Date of Service:");
$docDate->appendChild($docDateLabel);

$docDateInput=$DOM->createElement("input");
$DOSString=$doc->getDateofservice()->format("m/d/y");
$docDateInput->setAttribute("value",$DOSString);
$docDate->appendChild($docDateInput);


$docInfo->appendChild($docDate);
        
$body->appendChild($docInfo);

$MainSpan=$DOM->createElement("section");
$MainSpan->setAttribute("id","main");
$body->appendChild($MainSpan);

foreach($doc->getItems() as $docItem)
{
    populateEditorDOM($DOM,$MainSpan,$docItem,1);
}    



DOMUtilities\addScript($DOM,$body,"debugTools.js");
DOMUtilities\addScript($DOM,$body,"icd9/icd9problems.js");
DOMUtilities\addScript($DOM,$body,"medications/medications.js");
DOMUtilities\addScript($DOM,$body,"context/context.js");
DOMUtilities\addScript($DOM,$body,"sortItems/sortItems.js");
DOMUtilities\addScript($DOM,$body,"displayEvents.js");
DOMUtilities\addScript($DOM,$body,"displayReady.js");
?>
<!DOCTYPE html>
<?php echo $DOM->saveHTML($html);?>
