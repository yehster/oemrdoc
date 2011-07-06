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
    $th->setAttribute("COLSPAN","2");
    $tr->appendChild($th);
}

function addMappingRow($DOM,$tbody,$vm)
{
    $tr=$DOM->createElement("tr");
    $classification=$vm->getClassification();
    $tr->setAttribute("classification",$classification);
    $tr->setAttribute("code",$vm->getSource_code());
    $tr->setAttribute("code_type",$vm->getSource_code_type());
    
    $tbody->appendChild($tr);
    
    $tdControl=$DOM->createElement("td");
    $tdLabel=$DOM->createElement("td",$vm->getText());
    $tr->appendChild($tdControl);
    $tr->appendChild($tdLabel);
    
    switch ($classification)
    {
        case "abnormal":
        case "normal":
            $checkbox=$DOM->createElement("INPUT");
            $checkbox->setAttribute("TYPE","checkbox");
            $tdControl->appendChild($checkbox);
            break;
    }
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
        $formTable->setAttribute("type","form");
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
