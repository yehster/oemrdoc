<?php
include('/var/www/openemr/library/doctrine/init-em.php');
function lookupPreferredTerms($em,$toks)
{
    $qb = $em->createQueryBuilder()
        ->select("pt")
        ->where("pt.str like :str0");
    for($idx=1;$idx<count($toks);$idx++)
    {
        $qb->andWhere("pt.str like :str".$idx);
    }
    $qb->from("library\doctrine\Entities\SNOMED\PreferredTerm","pt");

    for($idx=0;$idx<count($toks);$idx++)
    {
        $qb->setParameter("str".$idx,"%".$toks[$idx]."%");
    }
    $qry=$qb->getQuery();
    return $qry->getResult();
}

function addPT($DOM,$ParentElement,$pt)
{
    $newRow=$DOM->createElement("TR");
    $newRow->setAttribute("CLASS","SNOMED");
    $newRow->setAttribute("aui",$pt->getAUI());

    $ParentElement->appendChild($newRow);

    $tdStr=$DOM->createElement("TD",htmlentities($pt->getSTR()));
    $tdStr->setAttribute("CLASS","str");
    $newRow->appendChild($tdStr);

    $tdAUI=$DOM->createElement("TD",htmlentities($pt->getAUI()));
    $newRow->appendChild($tdAUI);

}

if(isset($_REQUEST['searchString']))
{
    $searchString = $_REQUEST['searchString'];
}

$tokens = array();
$idx=0;
$tok = strtok($searchString," ");
while($tok!==false)
{
    $tokens[$idx]=$tok;
    $idx++;
    $tok=strtok(" ");
}

$DOM= new DOMDocument("1.0","utf-8");
$table = $DOM->createElement("TABLE");
$DOM->appendChild($table);

$res = lookupPreferredTerms($em,$tokens);

for($idx=0;$idx<count($res);$idx++)
{
    $pt=$res[$idx];
    addPT($DOM,$table,$pt);
}

echo $DOM->saveXML();
?>
