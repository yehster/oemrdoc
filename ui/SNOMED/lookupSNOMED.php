<?php
include('/var/www/openemr/library/doctrine/init-em.php');
function lookupPreferredTerms($em,$searchStr)
{
    $qb = $em->createQueryBuilder()
        ->select("pt")
        ->where("pt.str like :str")
        ->from("library\doctrine\Entities\SNOMED\PreferredTerm","pt");

    $qb->setParameter("str","%".$searchStr."%");
    $qry=$qb->getQuery();
    return $qry->getResult();
}

function addPT($DOM,$ParentElement,$pt)
{
    $newRow=$DOM->createElement("TR");
    $ParentElement->appendChild($newRow);

    $tdStr=$DOM->createElement("TD",htmlentities($pt->getSTR()));
    $newRow->appendChild($tdStr);
}

if(isset($_REQUEST['searchString']))
{
    $searchString = $_REQUEST['searchString'];
}

$DOM= new DOMDocument("1.0","utf-8");
$table = $DOM->createElement("TABLE");
$DOM->appendChild($table);

$res = lookupPreferredTerms($em,$searchString);

for($idx=0;$idx<count($res);$idx++)
{
    $pt=$res[$idx];
    addPT($DOM,$table,$pt);
}

echo $DOM->saveXML();
echo "yo";
?>
