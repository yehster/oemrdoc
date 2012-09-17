<?php
require_once('/var/www/openemr/library/doctrine/init-session.php');
require_once("$doctrineroot/DOMUtilities/DOMManager.php");

session_write_close();
$DOMManager=new DOMManager("html");
$html=$DOMManager->getRoot();

$head=$DOMManager->addElement($html,"head");

$DOMManager->addStyle($head,"css/ContentManager.css");

$title=$DOMManager->addElement($head,"title","Content Mapping Tool");


$DOMManager->addScript($head,"/openemr/library/js/jquery-1.7.1.min.js");
$DOMManager->addScript($head,"js/MappingManager.js");

$body = $DOMManager->addElement($html,"body");
$DIVChoices=$DOMManager->addElement($body,"div"," ",array("id"=>"choices"));

$DIVcodeLookup = $DOMManager->addElement($body,"div"," ", array("id"=>"codes","class"=>"topDiv"));
$codeLabel=$DOMManager->addElement($DIVcodeLookup,"span","Codes:",array("class"=>"label"));
$INPUTcodeLookup = $DOMManager->addElement($DIVcodeLookup,"input"," ", array("id"=>"codesLookup","type"=>"text"));
$DIVRadio=$DOMManager->addElement($DIVcodeLookup,"div");
$RadioChoices=$DOMManager->addRadio($DIVRadio,array("SNOMED","LOINC","IEMR"),array("name"=>"codeType"),0,array("class"=>"radioLabel"));

$DIVCodeResuts = $DOMManager->addElement($DIVcodeLookup,"div"," ",array("id"=>"codesResults","class"=>"results"));

$DIVsectionLookup = $DOMManager->addElement($body,"div"," ", array("id"=>"sections","class"=>"topDiv"));
$INPUTsectionLookup = $DOMManager->addElement($DIVsectionLookup,"input"," ", array("id"=>"sectionLookup","type"=>"text"));


?>
<!DOCTYPE html>
<?php echo $DOMManager->saveHTML();?>