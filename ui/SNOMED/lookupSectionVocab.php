<?php
include('/var/www/openemr/library/doctrine/init-em.php');

function getFormEntries($em,$c,$ct)
{
    $qb = $em->createQueryBuilder()
        ->select("fe")
        ->from("library\doctrine\Entities\FormEntry","fe")
        ->where("fe.target_code=:tc")
        ->andWhere("fe.target_code_type=:tt")
        ->orderBy("fe.classification DESC,fe.modified","DESC");
    $qb->setParameter("tc",$c);
    $qb->setParameter("tt",$ct);
    $qb->orderBy("fe.classification DESC,fe.seq","ASC");
    $qry=$qb->getQuery();
    return $qry->getResult();
}

function createButton($DOM,$tr,$value,$class)
{
    $td = $DOM->createElement("td");
    $tr->appendChild($td);

    $but = $DOM->createElement("input");
    $td->appendChild($but);

    $but->setAttribute("value",$value);
    $but->setAttribute("type","button");
    $but->setAttribute("class",$class." modFE");
    
}

function addEntry($DOM,$table,$fe)
{
    $newTR = $DOM->createElement("tr");
    $newTR->setAttribute("class","FormEntry ".$fe->getClassification());
    $newTR->setAttribute("uuid",$fe->getUUID());
    $newTR->setAttribute("aui",$fe->getSource_code());
    $table->appendChild($newTR);

    $tdText = $DOM->createElement("td",$fe->getText());
    $newTR->appendChild($tdText);

    createButton($DOM,$newTR,"del","del");
    createButton($DOM,$newTR,"up","up");
    createButton($DOM,$newTR,"dn","dn");
    if($fe->getClassification()=="normal")
    {
        createButton($DOM,$newTR,"abn","abn");
    }
    elseif($fe->getClassification()=="abnormal")
    {
        createButton($DOM,$newTR,"nor","nor");
    }

    


}

if(isset($_REQUEST['sectionUUID']))
{
    $sectionUUID = $_REQUEST['sectionUUID'];
    $section = $em->getRepository('library\doctrine\Entities\SectionHeading')->find($sectionUUID);
}

$DOM=new DOMDocument("1.0","utf-8");
$table=$DOM->createElement("table");
$table->setAttribute("class","formEntries");
$DOM->appendChild($table);
$entries = getFormEntries($em,$section->getCode(),$section->getCode_type());
for($idx=0;$idx<count($entries);$idx++)
{
    $entry=$entries[$idx];
    addEntry($DOM,$table,$entry);
}

echo $DOM->saveXML();
?>
