<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
include_once('/var/www/openemr/library/doctrine/ui/Review/ReviewSectionUtilities.php');

function findHistorical($em,$current)
{
    $qb= $em->createQueryBuilder()
        ->select("sec")
        ->from('library\doctrine\Entities\Section',"sec")
        ->where("sec.patient=:pat")
        ->andWhere("sec.uuid!=:cur")
        ->andWhere("sec.code=:code")
        ->andWhere("sec.code_type=:ct")
        ->orderBy("sec.modified","DESC");
    $qb->setParameter("cur",$current->getUUID());
    $qb->setParameter("pat",$current->getPatient());
    $qb->setParameter("code",$current->getCode());
    $qb->setParameter("ct",$current->getCode_type());

    $qryRes=$qb->getQuery()->getResult();
    if(count($qryRes)>0)
    {
        return $qryRes[0];
    }
}


if(isset($_REQUEST['currentSectionUUID']))
{
    $currentSectionUUID = $_REQUEST['currentSectionUUID'];
    $currentSection = $em->getRepository('library\doctrine\Entities\Section')->find($currentSectionUUID);

    $historicalSection = findHistorical($em,$currentSection);

    
}


if(isset($_REQUEST['historicalSectionUUID']))
{
    $historicalSectionUUID = $_REQUEST['historicalSectionUUID'];
    $historicalSection = $em->getRepository('library\doctrine\Entities\Section')->find($historicalSectionUUID);
}

if($historicalSection!=null)
{
    $DOM=new DOMDocument("1.0","utf-8");
    $topElem = generateReviewDOM($em,$DOM,$historicalSection);

}
echo $DOM->saveXML($topElem);


?>
