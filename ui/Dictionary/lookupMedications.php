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

function lookupMedForms($em,$rel,$types,$rxcui,$rxaui=null)
{
    $qb = $em->createQueryBuilder()
        ->select("co")
        ->from("library\doctrine\Entities\RXNConcept","co")
        ->from("library\doctrine\Entities\RXNRelationship", "rel")
        ->where("(rel.RXCUI1=:cui)")
        ->andWhere("rel.RXCUI2 = co.RXCUI")
        ->andWhere("rel.RELA = :rel")
        ->andWhere("co.TTY in(".$types.")")
        ->orderBy("co.TTY");

    $qb->setParameter("cui",$rxcui);
//    $qb->setParameter("aui",$rxaui);
    $qb->setParameter("rel",$rel);

    $qry=$qb->getQuery();
    $res=$qry->getResult();
    if(count($res)==0)
    {
        // If there are no results, then we need to check for synonyms

    }
    return $res;
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
$maxRes=20;

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
     case "MEDSEMANTIC":
            $medForms= lookupMedForms($em,"has_ingredient","'SCDF','SBDF'",$rxcui,$rxaui);
            for($idx=0;$idx<count($medForms);$idx++)
            {
                $curMed = $medForms[$idx];
                addMedName($DOM,$tbody,$curMed);
                $medSem = lookupMedForms($em,"isa","'SCD','SBD'",$curMed->getRXCUI(),$curMed->getRXAUI());
                for($formIdx=0;$formIdx<count($medSem);$formIdx++)
                {
                    $curSem = $medSem[$formIdx];
                    addMedName($DOM,$tbody,$curSem);
                }
            }
         break;
}
echo $DOM->saveXML($table);
?>
