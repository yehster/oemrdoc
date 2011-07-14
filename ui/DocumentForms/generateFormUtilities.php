<?php
include_once('/var/www/openemr/library/doctrine/ui/Editor/EditorConstants.php');


function findVocabMappings($em,$code,$code_type)
{
   $qb = $em->createQueryBuilder()
        ->select("v, vo.seq as seq")
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
    $th->setAttribute("COLSPAN","3");
    $tr->appendChild($th);
}

function addMappingRow($DOM,$tbody,$vm,$seq)
{
    $tr=$DOM->createElement("tr");
    $classification=$vm->getClassification();
    $tr->setAttribute("classification",$classification);
    $tr->setAttribute("code",$vm->getSource_code());
    $tr->setAttribute("code_type",$vm->getSource_code_type());
    $tr->setAttribute("seq",$seq);
    
    $tbody->appendChild($tr);
    
    $tdControl=$DOM->createElement("td");
    $tdLabel=$DOM->createElement("td",$vm->getText());
    $tdLabel->setAttribute("type","label");
    $tr->appendChild($tdControl);
    $tr->appendChild($tdLabel);
    
    switch ($classification)
    {
        case "abnormal":
        case "normal":
            $tdControlRight=$DOM->createElement("td");
            $tr->appendChild($tdControlRight);
            $cbRight=$DOM->createElement("INPUT");
            $cbRight->setAttribute("TYPE","checkbox");
            $cbRight->setAttribute("location","right");
            $cbRight->setAttribute("val","N");
            $cbRight->setAttribute("entrytype",$classification);
            $tdControlRight->appendChild($cbRight);
        case "multiple":
        case "exclusive":
            $checkbox=$DOM->createElement("INPUT");
            $checkbox->setAttribute("TYPE","checkbox");
            $checkbox->setAttribute("entrytype",$classification);
            $checkbox->setAttribute("location","left");
            $checkbox->setAttribute("val","Y");
            $tdControl->appendChild($checkbox);
            break;
    }
}

function generateSectionEntries($em,$DOM,$entry,$tbody)
{
    $mappings=findVocabMappings($em,$entry->getCode(),$entry->getCode_type());
    for($i=0;$i<count($mappings);$i++)
    {
        $vm=$mappings[$i][0];
        $seq=$mappings[$i]['seq']*100 + $vm->getSeq()*10;
        addMappingRow($DOM,$tbody,$vm,$seq);
    }
}

function generateSectionForm($em,$DOM,$DOMXPath,$parent,$entry)
{
    $entryItem=$entry->getItem();
    
    if($entry->getType()===TYPE_SECTION)
    {
        $span=$DOM->createElement("SPAN");
        
        $formTable=$DOM->createElement("TABLE");
        $formTable->setAttribute("entryuuid",$entry->getUUID());
        $formTBODY=$DOM->createELEMENT("TBODY");
        $formTable->appendChild($formTBODY);
        $formTable->setAttribute("type","form");
        generateSectionHeader($DOM, $formTBODY, $entry);
        generateSectionEntries($em,$DOM,$entry,$formTBODY);
        
        $span->appendChild($formTable);
        $parent->appendChild($span);

        }
    if($entry->getType()===TYPE_NOMINATIVE)
    {
        $nodes=$DOMXPath->query("//tr[@code='".$entry->getvocabID()."']",$parent);
        foreach($nodes as $node)
        {
            $input=$node->getElementsByTagName("INPUT");
            $input->item(0)->setAttribute("checked","true");
            $node->setAttribute("entryuuid",$entry->getUUID());
        }
    }
    if($entry->getType()===TYPE_OBSERVATION)
    {
        if($entry->getValue()=="Y")
        {
            $toCheck="left";
        }
        elseif($entry->getValue()=="N")
        {
            $toCheck="right";
        }
        $nodes=$DOMXPath->query("//tr[@code='".$entry->getvocabID()."']",$parent);
        foreach($nodes as $node)
        {        
            $node->setAttribute("entryuuid",$entry->getUUID());            
            $inputs=$node->getElementsByTagName("INPUT");
            foreach($inputs as $input)
            {
                
                if($input->getAttribute("location")==$toCheck)
                {
                    $input->setAttribute("checked","true");
                }
            }
        }
    }
    $newParent=$span;
    foreach($entryItem->getItems() as $childItem)
    {
        generateSectionForm($em,$DOM,$DOMXPath,$newParent,$childItem->getEntry());
    }
}
?>
