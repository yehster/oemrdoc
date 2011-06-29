<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');

function lookupMedNames($em,$searchString)
{
    $orderClause = "MATCHQUALITY('".$searchString."',mn.str)";
    $qb = $em->createQueryBuilder()
        ->select("mn,".$orderClause." as qual")
        ->from("library\doctrine\Entities\MedName","mn")
        ->where("mn.str like :startsWith")
        ->andWhere("mn.str like :contains")
        ->orderBy("qual DESC, mn.str");

    $qb->setParameter("startsWith",$searchString[0]."%");
    $qb->setParameter("contains","%".$searchString[1]."%");

    $qry=$qb->getQuery();
    return $qry->getResult();
}

function addMedName($DOM,$tbody,$mn)
{
    $newRow = $DOM->createElement("TR");
    $tbody->appendChild($newRow);


    $nameCell = $DOM->createElement("TD",  htmlentities($mn->getSTR()));
    $nameCell->setAttribute("RXCUI",$mn->getRXCUI());
    $nameCell->setAttribute("RXAUI",$mn->getRXAUI());
    $nameCell->setAttribute("TTY",$mn->getTTY());
    $nameCell->setAttribute("TYPE","NAME");
    
    $newRow->appendChild($nameCell);
}

if(isset($_REQUEST["searchString"]))
{
    $searchString= $_REQUEST["searchString"];    
}

if(isset($_REQUEST["task"]))
{
    $task= $_REQUEST["task"];    
}

if(isset($_REQUEST['rxcui']))
{
    $rxcui = $_REQUEST['rxcui'];
}
if(isset($_REQUEST['rxaui']))
{
    $rxaui = $_REQUEST['rxaui'];
}
$DOM= new DOMDocument("1.0","utf-8");
$table = $DOM->createElement("TABLE");
$tbody = $DOM->createElement("TBODY");
$table->appendChild($tbody);
$maxRes=30;

if(strlen($searchString)>0)
{
    switch ($task)
    {
     case "MEDSEARCH":
         
            $medNames = lookupMedNames($em,$searchString);
            for($idx=0;$idx<$maxRes and $idx<count($medNames);$idx++)
            {
             $curMed = $medNames[$idx][0];
             addMedName($DOM,$tbody,$curMed);
            }
            break;
    }
}
echo $DOM->saveXML($table);
?>
