<?php
include_once('/var/www/openemr/library/doctrine/init-session.php');
include_once('libreStatusUtils.php');

$filesList=$_REQUEST['files'];
$arrFiles=preg_split("/,/",$filesList,-1,PREG_SPLIT_NO_EMPTY);
$DOM = new DOMDocument("1.0","utf-8");
$body = $DOM->createElement("body");
$DOM->appendChild($body);

$table = $DOM->createElement("table");
$body->appendChild($table);

$tbody=$DOM->createElement("tbody");
$table->appendChild($tbody);

$libreFiles=findLibreFiles($em,$arrFiles);

foreach($libreFiles as $lf)
{
    $newRow=$DOM->createElement("tr");
    $tbody->appendChild($newRow);
    $tbody->setAttribute("id","libreStatus");
    $newData=$DOM->createElement("td",$lf->getFilename());
    $newRow->appendChild($newData);
 
    $newRow->setAttribute("id",$lf->getFilename());
    
    $levent = $lf->getEvents()->first();
    if($levent!=null)
    {
        $elemInfo=$DOM->createElement("td",$levent->getMessage()); 
        $newRow->appendChild($elemInfo);
        
        $elemType=$DOM->createElement("td",$levent->getType()); 
        $newRow->appendChild($elemType);
        
        $elemSuccess=$DOM->createElement("td",$levent->successful() ? "Successful" : "Failed"); 
        $newRow->appendChild($elemSuccess);
        
    }
}
/*for($idx=0;$idx<count($arrFiles);$idx++)
{
    $curFile = $arrFiles[$idx];
    $newRow=$DOM->createElement("tr");
    $tbody->appendChild($newRow);
    $newData=$DOM->createElement("td",$curFile);
    $newRow->appendChild($newData);
}
*/
 echo $DOM->saveXML($body);
 
error_log("Processing libreStatus");
?>
