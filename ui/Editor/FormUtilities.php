<?php
function getFormEntries($em,$c,$ct,$classification)
{
    $qb = $em->createQueryBuilder()
        ->select("fe")
        ->from("library\doctrine\Entities\FormEntry","fe")
        ->where("fe.target_code=:tc")
        ->andWhere("fe.target_code_type=:tt")
        ->andWhere("fe.classification=:cl")
        ->orderBy("fe.modified","DESC");
    $qb->setParameter("tc",$c);
    $qb->setParameter("tt",$ct);
    $qb->setParameter("cl",$classification);
    $qb->orderBy("fe.seq","ASC");
    $qry=$qb->getQuery();
    return $qry->getResult();
}

function getVocabMappings($em,$c,$ct)
{

        $qb = $em->createQueryBuilder()
        ->select("vm")
        ->from("library\doctrine\Entities\VocabComponent","vm")
        ->where("vm.target_code=:tc")
        ->andWhere("vm.target_code_type=:tt");
    $qb->setParameter("tc",$c);
    $qb->setParameter("tt",$ct);
    $qb->orderBy("vm.seq","ASC");
    $qry=$qb->getQuery();
    return $qry->getResult();
}

function addObservation($DOM,$ELEMParent,$obs,$className)
{
    $tr = $DOM->createElement("TR");
    $ELEMParent->appendChild($tr);
    $tr->setAttribute("CLASS",$className);
    $obsText = htmlentities($obs->getText());
    $tr->setAttribute("ObsText",$obsText);
    $tr->setAttribute("ID",$obs->getSource_code());
    $tr->setIdAttribute("ID",true);
    $obsDesc = $DOM->createElement("TD",$obsText);

    $tdButYes = $DOM->createElement("TD");

    $butYes = $DOM->createElement("INPUT");
    $butYes->setAttribute("TYPE","RADIO");
    $butYes->setAttribute("NAME",htmlentities($obs->getText()));
    $butYes->setAttribute("VALUE","YES");
    $butYes->setAttribute("CLASS",$obs->getClassification()." RB");

    $tdButYes->appendChild($butYes);

    $tdButNo = $DOM->createElement("TD");

    $butNo = $DOM->createElement("INPUT");
    $butNo->setAttribute("TYPE","RADIO");
    $butNo->setAttribute("NAME",  htmlentities($obs->getText()));
    $butNo->setAttribute("VALUE","NO");
    $butNo->setAttribute("CLASS",$obs->getClassification()." RB");

    $tdButNo->appendChild($butNo);

    $tdButNo->setAttribute("CLASS",$className);
    $obsDesc->setAttribute("CLASS",$className);
    $tdButYes->setAttribute("CLASS",$className);

    $tr->appendChild($tdButYes);
    $tr->appendChild($obsDesc);
    $tr->appendChild($tdButNo);


}

$ratioUnits = array("/min");
$pressureUnits = array("mm[Hg]");
$tempUnits = array("&deg;F","&deg;C");
$massUnits = array("lbs","kg");
$lengthUnits = array("inches","cm");
$units = array("NRat"=>$ratioUnits, "Pres"=>$pressureUnits,"Temp"=>$tempUnits,"Mass"=>$massUnits,"Len"=>$lengthUnits);
    

function AddUnitSelector($DOM,$ELEMParent,$property)
{
    global $units;
    $uarray=$units[$property];
    if(count($uarray)>0)
    {
        $sel = $DOM->createElement("SELECT");
        $ELEMParent->appendChild($sel);
        for($idx=0;$idx<count($uarray);$idx++)
        {
            $opt = $DOM->createElement("OPTION",$uarray[$idx]);
            $sel->appendChild($opt);
        }
    }
    return $sel;
}

function addVocabMapping($DOM,$ELEMParent,$vm,$className)
{
    $tr = $DOM->createElement("TR");
    $ELEMParent->appendChild($tr);
    $tr->setAttribute("CLASS",$className);
    $td = $DOM->createElement("TD");
    $td->setAttribute("COLSPAN","3");
    $tr->appendChild($td);
    
    $text = $DOM->createElement("TEXT",$vm->getText());
    $td->appendChild($text);
    
    $spanRight=$DOM->createElement("SPAN");
    $spanRight->setAttribute("style","float:right;");
    $td->appendChild($spanRight);
    
    $input = $DOM->createElement("INPUT");
    $input->setAttribute("TYPE","TEXT");
    $input->setAttribute("code",$vm->getSource_code());
    $input->setAttribute("code_type",$vm->getSource_code_type());
    $input->setAttribute("class",$vm->getClassification());
    $input->setAttribute("size","6");
    $spanRight->appendChild($input);
    
    $sel = AddUnitSelector($DOM,$spanRight,$vm->getProperty());
    if($sel!=null)
    {
        $sel->setAttribute("class","units");
    }
    
    
}
function AddObsHeader($DOM,$ELEMParent,$header,$class)
{
    $tr= $DOM->createElement("TR");
    $ELEMParent->appendChild($tr);
    $tr->setAttribute("CLASS",$class);
    
    $thYes = $DOM->createElement("TH","Yes");
    $thHeader = $DOM->createElement("TH",$header);
    $thNo = $DOM->createElement("TH","No");
    
    $tr->appendChild($thYes);
    $tr->appendChild($thHeader);    
    $tr->appendChild($thNo);

    $thYes->setAttribute("CLASS",$class);
    $thHeader->setAttribute("CLASS",$class." formHeader");
    $thNo->setAttribute("CLASS",$class);
}
function createObservationTable($em,$DOM,$ELEMParent,$header,$code,$code_type)
{
    $class="ObservationTable";
    $table = $DOM->createElement("TABLE");
    $table->setAttribute("CLASS",$class);
    $ELEMParent->appendChild($table);
    $tbody=  $DOM->createElement("TBODY");
    $table->appendChild($tbody);


    AddObsHeader($DOM,$tbody,$header,$class);
    
    createVocabMapRows($em,$DOM,$tbody,$code,$code_type,$class);
    createObservationRows($em,$DOM,$tbody,$code,$code_type,"normal",$class." normal");

    createObservationRows($em,$DOM,$tbody,$code,$code_type,"abnormal",$class." abnormal");
    return $table;

}
function createObservationRows($em,$DOM,$table,$c,$ct,$classification,$htmlClass)
{

    $observations = getFormEntries($em,$c,$ct,$classification);
    for($obsIdx=0;$obsIdx<count($observations);$obsIdx++)
        {
            $curObs = $observations[$obsIdx];
            addObservation($DOM,$table,$curObs,$htmlClass);
        }
}

function createVocabMapRows($em,$DOM,$table,$c,$ct,$htmlClass)
{
    $vms = getVocabMappings($em,$c,$ct);
    for($vmIdx=0;$vmIdx<count($vms);$vmIdx++)
    {
        $curVM = $vms[$vmIdx];
        addVocabMapping($DOM,$table,$curVM,$htmlClass );
    }
}

function createSectionTextBox($DOM,$parent,$docEntry)
{
    $textBox = $DOM->createElement("TEXTAREA");
    $textBox->setAttribute("CLASS","newNarrative");
    $textBox->setAttribute("searchID","newNar".$docEntry->getUUID());
    $textBox->setIdAttribute("searchID",true);

    $textBox->setAttribute("sectionuuid",$docEntry->getUUID());
    $textBox->setAttribute("rows","1");


    $td = $DOM->createElement("td");
    $td->appendChild($textBox);
    $td->setAttribute("COLSPAN","3");
    $tr = $DOM->createElement("TR");
    $tr->appendChild($td);

    $parent->appendChild($tr);

}

function createDocEntryTable($em,$DOM,$ELEMParent,$docEntry)
{
    $item=$docEntry->getItem();

    $header = htmlentities($docEntry->getText());
    $loc = strpos($header,":");
    if( ($loc>0) && ($docEntry->getType()==="Section"))
    {
        //TODO: fix hack to determine if section has form items.
        $header = htmlentities($docEntry->getText());
        $loc = strpos($header,":");
        if($loc>0)
        {
            $header = substr($header,$loc+1);
        }
        $code = $docEntry->getCode();
        $code_type=$docEntry->getCode_type();
        $DIVSection = $DOM->createElement("DIV");
        $DIVSection->setAttribute("CLASS","FormSection");
        $DIVSection->setAttribute("SectionID",$docEntry->getUUID());
 
        $ELEMParent->appendChild($DIVSection);
        $obsTable =createObservationTable($em,$DOM,$DIVSection,$header,$code,$code_type);
        $obsTable->setAttribute("SectionID",$docEntry->getUUID());
        createSectionTextBox($DOM,$obsTable,$docEntry);
        $ELEMParent=$DIVSection;
    }

    if($docEntry->getType()=="Observation")
    {
        // Update the radio buttons based on the state of the observations
        $obsRow=$DOM->getElementById($docEntry->getvocabID());
        if($obsRow!==null)
        {
            $obsRow->setAttribute("ObservationID",$docEntry->getUUID());
            $inputs=$obsRow->getElementsByTagName("INPUT");
            for($inpIdx=0;$inpIdx<$inputs->length;$inpIdx++)
            {
                $inp=$inputs->item($inpIdx);
                if($inp->getAttribute("VALUE")==$docEntry->getValue())
                {
                    $inp->setAttribute("Checked","");
                }
            }
        }
    }

    if($docEntry->getType()=="Narrative")
    {
        $par = $docEntry->getParentEntry();
        if($par->getType()=="Section")
        {
            $searchID="newNar".$par->getUUID();
            $textArea=$DOM->getElementById($searchID);
            $textClass=$textArea->getAttribute("CLASS");
            if($textClass=="newNarrative")
            {
                $textArea->nodeValue=htmlentities($docEntry->getText());
                $textArea->setAttribute("CLASS","FormNarrative");
                $textArea->setAttribute("ID",$docEntry->getUUID());
            }


//            $textArea->nodeValue=htmlentities(print_r($textArea));

        }
    }

    $childCount= $item->getItems()->count();
    if($childCount>0)
    {

        for($childIdx=0;$childIdx<$childCount;$childIdx++)
        {
            // modify this to spread sections into columns.
            $childItem = $item->getItems()->get($childIdx);
            $childEntry = $childItem->getEntry();
            createDocEntryTable($em,$DOM,$ELEMParent,$childEntry);
        }
    }


}
?>
