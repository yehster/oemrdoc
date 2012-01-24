<?php
include('/var/www/openemr/library/doctrine/init-em.php');
function findorCreateExamFinding($em,$c,$ct,$txt,$class)
{
    $qb = $em->createQueryBuilder()
        ->select("ef")
        ->from("library\doctrine\Entities\ExamFinding","ef")
        ->where("ef.text = ?1 AND ef.code=?2 and ef.code_type=?3")
        ->orderBy("ef.modified","DESC");
    $qb->setParameter(1,$txt);
    $qb->setParameter(2,$c);
    $qb->setParameter(3,$ct);

    $qry=$qb->getQuery();    try
    {
        $res=$qry->getSingleResult();
    }
    catch(Exception $e)
    {
        $res = new library\doctrine\Entities\ExamFinding($c,$ct,$txt);
        $res->setClassification($class);
        echo $txt.PHP_EOL;
        $em->persist($res);
        $em->flush();

    }
    return $res;
}
function FindExamSection($em,$code,$codeType)
{
    $qb = $em->createQueryBuilder()
        ->select("sh")
        ->from("library\doctrine\Entities\SectionHeading","sh")
        ->where("sh.code=?1 and sh.code_type=?2")
        ->orderBy("sh.modified","DESC");
    $qb->setParameter(1,$code);
    $qb->setParameter(2,$codeType);
    $qry=$qb->getQuery();
    return $qry->getSingleResult();
}

function CreateFindings($em,$code,$codeType,$findings,$class)
{
    $sh=FindExamSection($em,$code,$codeType);
    echo $sh->getshortDesc().PHP_EOL;
    foreach ($findings as $value)
    {
        findorCreateExamFinding($em,$code,$codeType,$value,$class);
    }
}
$generalExamFindings = Array("Well Developed",
                             "No Acute Distress");
$genExamFindingsAbn = Array("Ill Appearing");
CreateFindings($em,'32434-3','LOINC',$generalExamFindings,"normal");
CreateFindings($em,'32434-3','LOINC',$genExamFindingsAbn,"abnormal");


$eyeExamFindings = Array("PERRLA",
                         "Extraoccular Movements Intact",
                         "Normal Eyelids",
                         "Normal Conjunctiva");
CreateFindings($em,'10197-2','LOINC',$eyeExamFindings,"normal");

$cvExamFindings = Array("Regular Rate and Rhythm",
                        "No Murmurs, Rubs or Gallops",
                        "No Jugular Venous Distention");
CreateFindings($em,'11421-5','LOINC',$cvExamFindings,"normal");

$respExamFindings = Array("Normal Respiratory Effort","Clear to Auscultation Bilaterally"
                         );
CreateFindings($em,'11412-4','LOINC',$respExamFindings,"normal");


$em->flush();
?>
