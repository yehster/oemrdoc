<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');

function lookupMedNames($em,$searchString)
{
    if(strlen($searchString)==0)
    {
        return;
    }
    $orderClause = "MATCHQUALITY('".$searchString."',mn.str)";
    $qb = $em->createQueryBuilder()
        ->select("mn,".$orderClause." as qual, mnu")
        ->from("library\doctrine\Entities\MedName","mn")
        ->leftJoin("mn.usage","mnu")
        ->where("mn.str like :startsWith");
    if(strlen($searchString)>1)
    {
        $qb->andWhere("mn.str like :contains");
        $qb->setParameter("contains","%".$searchString[1]."%");
    }        
       $qb->OrderBy("mnu.frequency","DESC")        
        ->addOrderBy("qual","DESC")
        ->addOrderBy("mn.str","ASC");

    if(strlen($searchString)==0)
    {
        $qb->setParameter("startsWith","%");    
    }
    else
    {
        $qb->setParameter("startsWith",$searchString[0]."%");    
    }


    $qry=$qb->getQuery();
    return $qry->getResult();
}

function lookupMedForms($em,$rel,$types,$id,$field)
{
    $qb = $em->createQueryBuilder()
        ->select("co")
        ->from("library\doctrine\Entities\RXNConcept","co")
        ->from("library\doctrine\Entities\RXNRelationship", "rel")
        ->where("(rel.".$field."1=:id)")
        ->andWhere("rel.RXCUI2 = co.RXCUI")
        ->andWhere("rel.RELA = :rel")
        ->andWhere("co.TTY in(".$types.")")
        ->orderBy("co.TTY");

    $qb->setParameter("id",$id);
    $qb->setParameter("rel",$rel);

    $qry=$qb->getQuery();
    $res=$qry->getResult();
    if(count($res)==0)
    {
        // If there are no results, then we need to check for synonyms

    }
    return $res;
}

function lookupMedFormsJoin($em,$rel,$rel2,$types,$id,$field)
{
    $qb = $em->createQueryBuilder()
        ->select("co,count(rel2) as cnt")
        ->from("library\doctrine\Entities\RXNConcept","co")
        ->from("library\doctrine\Entities\RXNRelationship", "rel")
        ->from("library\doctrine\Entities\RXNRelationship", "rel2")
        ->where("(rel.".$field."1=:id)")
        ->andWhere("rel.RXCUI2 = co.RXCUI")
        ->andWhere("co.RXCUI=rel2.RXCUI1")    
        ->andWhere("rel.RELA = :rel")
        ->andWhere("rel2.RELA = :rel2")
        ->andWhere("co.TTY in(".$types.")")
        ->groupBy("co")
        ->orderby("cnt");

    $qb->setParameter("id",$id);
    $qb->setParameter("rel",$rel);
    $qb->setParameter("rel2",$rel2);

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
//            $medForms= lookupMedForms($em,"has_ingredient","'SCDF','SBDF'",$rxcui,$rxaui);
         
            $medForms= lookupMedFormsJoin($em,"has_ingredient","ingredient_of","'SCDF','SBDF'",$rxcui,"RXCUI");
            $res= array_merge(lookupMedFormsJoin($em,"has_ingredient","ingredient_of","'SCDF','SBDF'",$rxaui,"RXAUI"),$medForms);
            for($idx=0;$idx<count($res);$idx++)
            {
                $curMed = $res[$idx][0];
                addMedName($DOM,$tbody,$curMed);
                $medSem = lookupMedForms($em,"isa","'SCD','SBD'",$curMed->getRXCUI(),"RXCUI");
                for($formIdx=0;$formIdx<count($medSem);$formIdx++)
                {
                    $curSem = $medSem[$formIdx];
                    addMedName($DOM,$tbody,$curSem);
                }
                $medSem = lookupMedForms($em,"isa","'SCD','SBD'",$curMed->getRXAUI(),"RXAUI");
                for($formIdx=0;$formIdx<count($medSem);$formIdx++)
                {
                    $curSem = $medSem[$formIdx];
                    addMedName($DOM,$tbody,$curSem);
                }

            }
            $medName=$em->getRepository("library\doctrine\Entities\MedName")->find($rxaui);
            $medName->getUsage()->increment();
            $em->flush();
         break;
}
echo $DOM->saveXML($table);
?>
