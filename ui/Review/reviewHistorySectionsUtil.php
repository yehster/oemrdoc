<?php
require_once("$doctrineroot/common/EditorConstants.php");
function findHistSection($sec,$rootUUID)
{
        $qb= $GLOBALS['em']->createQueryBuilder()
        ->select("sec")
        ->from('library\doctrine\Entities\Section',"sec")
        ->join('sec.item','item')
        ->andWhere("sec.uuid!=:cur")
        ->andWhere("sec.code=:code")
        ->andWhere("sec.patient=:pat")
        ->andWhere("sec.code_type=:ct")
        ->andWhere("item.root=:root");
    $qb->setParameter("cur",$sec->getUUID());
    $qb->setParameter("pat",$sec->getPatient());
    $qb->setParameter("ct",$sec->getCode_type());
    $qb->setParameter("code",$sec->getCode());
    $qb->setParameter("root",$rootUUID);

    $qryRes=$qb->getQuery()->getResult();

    return $qryRes;
}

function findReviewDoc($relDoc,$curDoc,$direction,$sections)
{
    $qb=$GLOBALS['em']->createQueryBuilder();
    $qb->select('doc')
       ->from('library\doctrine\Entities\Document',"doc")
       ->where('doc.patient=:pat')
       ->andWhere('doc.uuid!=:curDoc');
    if($direction<0)
    {
        $qb->andWhere('doc.modified<:relModified');
        $qb->orderBy('doc.modified',"DESC");
    }
    else
    {
        $qb->andWhere('doc.modified>:relModified');
        $qb->orderBy('doc.modified',"ASC");
        
        }
    $qb->setParameter("pat",$GLOBALS['pat']);
    $qb->setParameter("curDoc",$curDoc);
    $qb->setParameter("relModified",$relDoc->getModified()->format(DateTIME::ISO8601));
    
    $results=$qb->getQuery()->getResult();
    if(count($results)==0)
    {
    // No document found, so just quit here
        return;
    }
    // check to see if this document has any matching sections
    $retVal=array();
    $retSection=array();
    for($idx=0;$idx<count($results);$idx++)
    {
        //loop through the documents until we find one that matches
        foreach($sections as $sec)
        {
            $sectionResults=findHistSection($sec,$results[0]->getUUID());
            if(count($sectionResults)>0)
            {
                foreach($sectionResults as $sr)
                {
                    $retSection[]=$sr;
                }
            }
            else
            {
                $secItem=$sec->getItem();
                foreach($secItem->getItems() as $item)
                {
                    $entry=$item->getEntry();
                    if($entry->getType()==TYPE_SECTION)
                    {
                        $sectionResults=findHistSection($entry,$results[0]->getUUID());
                        foreach($sectionResults as $sr)
                        {
                            $retSection[]=$sr;
                        }                    
                    }
                }
            }
        }
        if(count($retSection)>0)
        {
            $retVal['doc']=$results[0];
            $retVal['sections']=$retSection;
            if(count($results)>($idx+1))
            {
                $retVal['seqDoc']=$results[$idx+1];
            }
            return $retVal;
        }
        else
        {
            // If we don't have any matching sections, check the previous document
            return findReviewDoc($results[0],$curDoc,$direction,$sections);
        }
    }
    return;
}

function findSections($sectionToks)
{
    $secList=$sectionToks[0];
    for($i=1;$i<count($sectionToks);$i++)
    {
        $secList=$secList.",".$sectionToks[i];
    }
    $qb=$GLOBALS['em']->createQueryBuilder();
    $qb->select('sec')
       ->from('library\doctrine\Entities\Section',"sec")
       ->where("sec.uuid in (:uuids)");
    $qb->setParameter("uuids",$secList);
    
    return $qb->getQuery()->getResult();
    
}

function displayHistoricalElements($DOM,$parent,$entry,$depth)
{
    $elemTop=$DOM->createElement("DIV");
    $elemTop->setAttribute("reviewuuid",$entry->getUUID());
    $elemTop->setAttribute("reviewType",$entry->getType());
    
    $cb=$DOM->createElement("INPUT");
    $cb->setAttribute("TYPE","checkbox");
    $cb->setAttribute("DEPTH",$depth);
    $cb->setAttribute("reviewuuid",$entry->getUUID());
    $cb->setAttribute("FUNC","review");
    $elemTop->appendChild($cb);
    
    $elemLabel=$DOM->createElement("SPAN",$entry->getText());
    $elemTop->appendChild($elemLabel);
    $parent->appendChild($elemTop);
    $item=$entry->getItem();
    foreach($item->getItems() as $it)
    {
        displayHistoricalElements($DOM,$elemTop,$it->getEntry(),$depth+1);
    }
}
?>
