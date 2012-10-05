<?php
require_once('/var/www/openemr/library/doctrine/init-session.php');
require_once("$doctrineroot/DOMUtilities/DOMManager.php");

session_write_close();
$DOMManager=new DOMManager("html");
$html=$DOMManager->getRoot();

$head=$DOMManager->addElement($html,"head");
$encoding=$DOMManager->addElement($head,"meta",null, ["charset"=>"utf-8"]);

$DOMManager->addStyle($head,"css/ContentManager.css");

$title=$DOMManager->addElement($head,"title","Content Mapping Tool");


$DOMManager->addScript($head,"/openemr/library/js/jquery-1.7.1.min.js");
$DOMManager->addScript($head,"js/MappingManager.js");
$DOMManager->addScript($head,"js/groupManagement.js");

$body = $DOMManager->addElement($html,"body");
$DIVChoices=$DOMManager->addElement($body,"div"," ",["id"=>"choices"]);
$codeChoice=$DOMManager->addElement($DIVChoices,"div","Code:");
$codeChoiceDisplay=$DOMManager->addElement($codeChoice,"span"," ",["id"=>"codeChoice"]);

$sectionChoice=$DOMManager->addElement($DIVChoices,"div","Section:");
$sectionChoiceDisplay=$DOMManager->addElement($sectionChoice,"span"," ",["id"=>"sectionChoice"]);

$groupChoice=$DOMManager->addElement($DIVChoices,"div","Group:");
$groupChoiceDisplay=$DOMManager->addElement($groupChoice,"span"," ",["id"=>"groupChoice"]);


$DIVcodeLookup = $DOMManager->addElement($body,"div"," ", ["id"=>"codes","class"=>"topDiv"]);
$codeLabel=$DOMManager->addElement($DIVcodeLookup,"span","Codes:",["class"=>"label"]);
$INPUTcodeLookup = $DOMManager->addElement($DIVcodeLookup,"input"," ", ["id"=>"codesLookup","type"=>"text"]);
$DIVRadio=$DOMManager->addElement($DIVcodeLookup,"div");
$RadioChoices=$DOMManager->addRadio($DIVRadio,["SNOMED","LOINC","IEMR"],["name"=>"codeType"],0,["class"=>"radioLabel"]);

$DIVCodeResults = $DOMManager->addElement($DIVcodeLookup,"div"," ",["id"=>"codesResults","class"=>"results"]);

$DIVsectionLookup = $DOMManager->addElement($body,"div"," ", ["id"=>"sections","class"=>"topDiv"]);
$INPUTsectionLookup = $DOMManager->addElement($DIVsectionLookup,"input"," ", ["id"=>"sectionLookup","type"=>"text"]);
$SELECTsectionChoice = $DOMManager->addSelect($DIVsectionLookup,["Review of Systems","Physical Exam","Context","Family"],["id"=>"sectionSelect"],2);
$DIVsectionResults = $DOMManager->addElement($DIVsectionLookup,"div"," ",["id"=>"sectionResults","class"=>"results"]);

$DIVGroups = $DOMManager->addElement($body,"div"," ", ["id"=>"groups","class"=>"topDiv"]);
$CreateGroup= $DOMManager->addElement($DIVGroups,"button","Create Group",["id"=>"createGroup","disabled"=>"true"]);
$DIVgroupResults = $DOMManager->addElement($DIVGroups,"div"," ",["id"=>"groupResults","class"=>"results"]);



?>
<!DOCTYPE html>
<?php echo $DOMManager->saveHTML();?>