<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
include_once('/var/www/openemr/library/doctrine/ui/Review/ReviewSectionUtilities.php');

function findHistorical($em,$current,$dom=null,$parent=null)
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
        $retVal= $qryRes[0];
    }
    if(count($qryRes)>1)
    {
        if($dom!=null)
        {

            $link=$dom->createElement("INPUT");
            $parent->appendChild($link);
            $link->setAttribute("value","Prev");
            $link->setAttribute("type","button");

            $link->setAttribute("uuid",$qryRes[1]->getUUID());
            $link->setAttribute("class","reviewLink");
            }
    }
    return $retVal;
}
function findNav($em,$historical,$current,$dom,$parent,$dir)
{
    $qb= $em->createQueryBuilder()
        ->select("sec")
        ->from('library\doctrine\Entities\Section',"sec")
        ->where("sec.patient=:pat")
        ->andWhere("sec.uuid!=:his")
        ->andWhere("sec.uuid!=:cur")
        ->andWhere("sec.code=:code")
        ->andWhere("sec.code_type=:ct");
    if($dir)
    {
       $qb->andWhere("sec.modified>:mod")
                       ->orderBy("sec.modified","ASC");

    }
    else
    {
       $qb->andWhere("sec.modified<:mod")
               ->orderBy("sec.modified","DESC");

    }
    $qb->setParameter("his",$historical->getUUID());
    $qb->setParameter("cur",$current);
    $qb->setParameter("pat",$historical->getPatient());
    $qb->setParameter("code",$historical->getCode());
    $qb->setParameter("ct",$historical->getCode_type());
    $qb->setParameter("mod",$historical->getModified()->format(DateTIME::ISO8601));
    $qryRes=$qb->getQuery()->getResult();
    if(count($qryRes)>0)
    {
            $link=$dom->createElement("INPUT");
            $parent->appendChild($link);
            $link->setAttribute("type","button");
            $link->setAttribute("uuid",$qryRes[0]->getUUID());
            $link->setAttribute("class","reviewLink");
        if($dir)
        {
            $link->setAttribute("value","Next");
        }
        else
        {
            $link->setAttribute("value","Prev");
            
        }
    }

}

$DOM=new DOMDocument("1.0","utf-8");
if(isset($_REQUEST['currentSectionUUID']))
{
    $currentSectionUUID = $_REQUEST['currentSectionUUID'];
    $currentSection = $em->getRepository('library\doctrine\Entities\Section')->find($currentSectionUUID);

    $nav=$DOM->createElement("DIV");
    $DOM->appendChild($nav);
    $historicalSection = findHistorical($em,$currentSection,$DOM,$nav);
    echo $DOM->saveXML($nav);
    
}


if(isset($_REQUEST['historicalSectionUUID']))
{
    $historicalSectionUUID = $_REQUEST['historicalSectionUUID'];
    $historicalSection = $em->getRepository('library\doctrine\Entities\Section')->find($historicalSectionUUID);
    $sectionUUID=$_REQUEST['SectionUUID'];
    $nav=$DOM->createElement("DIV");
    $DOM->appendChild($nav);
    findNav($em,$historicalSection,$sectionUUID,$DOM,$nav,0);
    findNav($em,$historicalSection,$sectionUUID,$DOM,$nav,1);
    echo $DOM->saveXML($nav);
}

if($historicalSection!=null)
{
    $DOM=new DOMDocument("1.0","utf-8");
    $topElem = generateReviewDOM($em,$DOM,$historicalSection);

}
echo $DOM->saveXML($topElem);


?>
