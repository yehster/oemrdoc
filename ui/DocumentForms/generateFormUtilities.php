<?php
include_once('/var/www/openemr/library/doctrine/ui/Editor/EditorConstants.php');
require_once("generateFormQuant.php");

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
    
    $th=$DOM->createElement("TH",htmlentities($entry->getText()));
    $tr->appendChild($th);
    
    return $tr;
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
        case "quantitative":
            $tdControlRight=$DOM->createElement("td");
            $tr->appendChild($tdControlRight);            
            $input=$DOM->createElement("INPUT");
            $input->setAttribute("TYPE","TEXT");
// TO Validate numeric input.
//            $input->setAttribute("PATTERN",'[0-9]*[.][0-9]*');
            $input->setAttribute("entrytype",$classification);
            $tdControlRight->appendChild($input);
            
            $sel = AddUnitSelector($DOM,$tdControlRight,$vm->getProperty());
            if($sel!=null)
            {
                $sel->setAttribute("class","units");
             }

            break;
    }
}

function generateSectionEntries($em,$DOM,$entry,$tbody,$headerRow)
{
    $mappings=findVocabMappings($em,$entry->getCode(),$entry->getCode_type());
    $needsLabel=false;
    for($i=0;$i<count($mappings);$i++)
    {
        $vm=$mappings[$i][0];
        $seq=$mappings[$i]['seq']*100 + $vm->getSeq()*10;
        addMappingRow($DOM,$tbody,$vm,$seq);
        if(($needsLabel==false) && (($vm->getClassification()=="normal") || ($vm->getClassification()=="abnormal")))
        {
            $needsLabel=true;
        }
    }
        $ths=$headerRow->getElementsByTagName("TH");
        $th=$ths->item(0);
    
    if($needsLabel)
    {
        $th->setAttribute("colspan","1");

        $labelY=$DOM->createElement("TH","Y");
        $headerRow->insertBefore($labelY,$th);
        $labelN=$DOM->createElement("TH","N");
        $headerRow->appendChild($labelN);
    }
    else {
        $th->setAttribute("colspan","2");
        
    }
    if(count($mappings)>0)
    {
        return true;
    }
    else
    {
        return false;
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
        $headerRow = generateSectionHeader($DOM, $formTBODY, $entry);
        $hasEntries=generateSectionEntries($em,$DOM,$entry,$formTBODY,$headerRow);
        
        
        if($hasEntries)
        {
            $span->appendChild($formTable);
        }
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
    if($entry->getType()==TYPE_QUANTITATIVE)
    {
        $nodes=$DOMXPath->query("//tr[@code='".$entry->getvocabID()."']",$parent);
        foreach($nodes as $node)
        {        
            $node->setAttribute("entryuuid",$entry->getUUID());            
            $inputs=$node->getElementsByTagName("INPUT");
            foreach($inputs as $input)
            {
                $input->setAttribute("value",$entry->getValue());
            }
            $select=$node->getElementsByTagName("SELECT");
            foreach($select as $sel)
            {
                $options=$sel->getElementsByTagName("OPTION");
                foreach($options as $opt)
                {
                    $foundOpt=$opt->getAttribute("units")==htmlentities(htmlentities($entry->getUnits()));
                    if($foundOpt)
                    {
                        $opt->setAttribute("selected","true");
                    }
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
