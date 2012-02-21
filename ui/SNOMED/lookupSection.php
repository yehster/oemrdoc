<?php
include('/var/www/openemr/library/doctrine/init-session.php');
if(isset($_REQUEST['searchString']))
{
    $searchString = $_REQUEST['searchString'];
}

function lookupSectionsTerms($em,$searchStr)
{
    $qb = $em->createQueryBuilder()
        ->select("sh")
        ->where("sh.longDesc like :str0");
    $qb->from("library\doctrine\Entities\SectionHeading","sh");

    $qb->setParameter("str0","%".$searchStr."%");
    $qry=$qb->getQuery();
    return $qry->getResult();
}

function addSection($DOM,$table,$section)
{
    $newTR = $DOM->createElement("TR");
    $table->appendChild($newTR);

    $newTR->setAttribute("code",$section->getCode());
    $newTR->setAttribute("aui",$section->getCode());
    $newTR->setAttribute("code_type",$section->getCode_type());
    $newTR->setAttribute("class","section");
    $newTR->setAttribute("uuid",$section->getUUID());


    $textTD = $DOM->createElement("TD",htmlentities($section->getText()));
    $newTR->appendChild($textTD);

}

$DOM= new DOMDocument("1.0","utf-8");
$table = $DOM->createElement("TABLE");
$DOM->appendChild($table);

if($searchString=="")
{
    $searchString="Physical Exam:";
}
$res = lookupSectionsTerms($em,$searchString);
for($idx=0;$idx<count($res);$idx++)
{
    addSection($DOM,$table,$res[$idx]);
}


echo $DOM->saveXML($table);

?>
