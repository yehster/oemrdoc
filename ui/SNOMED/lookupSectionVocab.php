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
    $qb->orderBy("fe.seq","ASC");
    $qry=$qb->getQuery();
    return $qry->getResult();
}

function addEntry($DOM,$table,$fe)
{
    $newTR = $DOM->createElement("tr");
    $newTR->setAttribute("class","FormEntry");
    $table->appendChild($newTR);

    $tdText = $DOM->createElement("td",$fe->getText());
    $newTR->appendChild($tdText);

}

if(isset($_REQUEST['sectionUUID']))
{
    $sectionUUID = $_REQUEST['sectionUUID'];
    $section = $em->getRepository('library\doctrine\Entities\SectionHeading')->find($sectionUUID);
}

$DOM=new DOMDocument("1.0","utf-8");
$table=$DOM->createElement("table");
$DOM->appendChild($table);
$entries = getFormEntries($em,$section->getCode(),$section->getCode_type());
for($idx=0;$idx<count($entries);$idx++)
{
    $entry=$entries[$idx];
    addEntry($DOM,$table,$entry);
}

echo $DOM->saveXML();
?>
