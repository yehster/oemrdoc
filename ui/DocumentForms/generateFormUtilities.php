<?php
include_once('/var/www/openemr/library/doctrine/ui/Editor/EditorConstants.php');


function findVocabMappings($em,$code,$code_type)
{
   $qb = $em->createQueryBuilder()
        ->select("v")
        ->from("library\doctrine\Entities\VocabMapping as v, library\doctrine\Entities\VocabOrdering as vo")
        ->where("v.target_code=:tc")
        ->andWhere("v.target_code_type=:tt")
        ->andWhere("vo.classification=v.classification")
        ->orderBy("vo.seq ASC, v.seq","ASC");

    $qb->setParameter("tc",$code);
    $qb->setParameter("tt",$code_type);
    $qry=$qb->getQuery();
    return $qry->getResult();

}

function generateSectionHeader($DOM,$parent,$entry)
{
    $tr=$DOM->createElement("TR");
    $parent->appendChild($tr);
    
    $th=$DOM->createElement("TH",$entry->getText());
    $tr->appendChild($th);
}

function addMappingRow($DOM,$tbody,$vm)
{
    $tr=$DOM->createElement("tr");
    $tbody->appendChild($tr);
    
    $tdLabel=$DOM->createElement("td",$vm->getText());
    $tr->appendChild($tdLabel);
}

function generateSectionEntries($em,$DOM,$entry,$tbody)
{
    $mappings=findVocabMappings($em,$entry->getCode(),$entry->getCode_type());
    for($i=0;$i<count($mappings);$i++)
    {
        $vm=$mappings[$i];
        addMappingRow($DOM,$tbody,$vm);
    }
}

function generateSectionForm($em,$DOM,$parent,$entry)
{
    $entryItem=$entry->getItem();
    
    if($entry->getType()===TYPE_SECTION)
    {
        $span=$DOM->createElement("SPAN");
        
        $formTable=$DOM->createElement("TABLE");
        $formTBODY=$DOM->createELEMENT("TBODY");
        $formTable->appendChild($formTBODY);
        generateSectionHeader($DOM, $formTBODY, $entry);
        generateSectionEntries($em,$DOM,$entry,$formTBODY);
        
        $span->appendChild($formTable);
        $parent->appendChild($span);

        }
    $newParent=$span;
    foreach($entryItem->getItems() as $childItem)
    {
        generateSectionForm($em,$DOM,$newParent,$childItem->getEntry());
    }
}
?>
