<?php
include('/var/www/openemr/library/doctrine/init-session.php');

function lookupLOINCTerms($em,$toks)
{
    $qb = $em->createQueryBuilder()
        ->select("pt")
        ->where("pt.LONG_COMMON_NAME like :str0");
    for($idx=1;$idx<count($toks);$idx++)
    {
        $qb->andWhere("pt.LONG_COMMON_NAME like :str".$idx);
    }
    $qb->from("library\doctrine\Entities\LOINC\LOINCEntry","pt");

    for($idx=0;$idx<count($toks);$idx++)
    {
        $qb->setParameter("str".$idx,"%".$toks[$idx]."%");
    }
    $qry=$qb->getQuery();
    return $qry->getResult();
}

function addLOE($DOM,$ParentElement,$pt)
{
    $newRow=$DOM->createElement("TR");
    $newRow->setAttribute("CLASS","LOINC");
    $newRow->setAttribute("LOINC_NUM",$pt->getLOINC_NUM());
    $newRow->setAttribute("SCALE_TYPE",$pt->getSCALE_TYP());
    $newRow->setAttribute("PROPERTY",$pt->getPROPERTY());
    
    $ParentElement->appendChild($newRow);

    $tdStr=$DOM->createElement("TD",htmlentities($pt->getLONG_COMMON_NAME()));
    $tdStr->setAttribute("CLASS","str");
    $newRow->appendChild($tdStr);

    $tdLOINC_NUM=$DOM->createElement("TD",htmlentities($pt->getLOINC_NUM()));
    $newRow->appendChild($tdLOINC_NUM);

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


$res = lookupLOINCTerms($em,$tokens);

for($idx=0;$idx<count($res);$idx++)
{
    $pt=$res[$idx];
    addLOE($DOM,$table,$pt);
}

echo $DOM->saveXML();

?>
