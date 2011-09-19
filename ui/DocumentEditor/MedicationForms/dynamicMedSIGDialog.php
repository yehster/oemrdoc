<?php
function createInput($DOM,$parent,$class)
{
    $input = $DOM->createElement("input");
    $input->setAttribute("type","text");
    $input->setAttribute("class",$class);
    
    $td=$DOM->createElement("td");
    $td->appendChild($input);
    
    $parent->appendChild($td);
    return $input;
}

function findAttribute($arrAtt,$atn)
{
    for($idx=0;$idx<count($arrAtt);$idx++)
    {
        if($arrAtt[$idx]->getATN()==$atn)
        {
            return $arrAtt[$idx]->getATV();
        }
    }
    return null;
}

function determineUnits($dst,$ddf)
{
    
    $loc=strpos($dst,'/');
        echo $dst;
    if($loc)
    {

        if(strpos($dst,"units"))
        {
            return "units";
        }
        elseif(strpos($dst,"mg"))
        {
            if(strpos($dst,"mL"))
            {
                return "mL";                    
            }
        }
        elseif($drta==="INH")
        {
                return "puffs";                                
        }
    }
    else
    {
        if($drta==="OPH")
        {
            return "gtt";
        }
        elseif($drta==="INH")
        {
            
        }
        else
        {
            return $ddf;
        }
    }    
}

function createInputs($DOM,$parent,$medSIG, $dst, $drta, $ddf)
{
    $tr=$DOM->createElement("tr");
    $parent->appendChild($tr);
    $parent=$tr;
    
    $sigQty=createInput($DOM,$parent,"qty");
    if(($medSIG->getQuantity()!=="") and ($medSIG->getQuantity()!=null))
    {
        $sigQty->setAttribute("value",$medSIG->getQuantity());
    }
    
    $sigUnits=createInput($DOM,$parent,"units");

    $sigRoute=createInput($DOM,$parent,"route");
    if(($medSIG->getRoute()==="") or ($medSIG->getRoute()==null))
    {
        $sigRoute->setAttribute("value",$drta);
        $medSIG->setRoute($drta);
        $GLOBALS['em']->flush();
    }
    else
    {
        $sigRoute->setAttribute("value",$medSIG->getRoute());
    }
    
    
    if(($medSIG->getUnits()==="") or ($medSIG->getUnits()==null))
    {
        $units=determineUnits($dst,$ddf);
        $medSIG->setUnits($units);
        $sigUnits->setAttribute("value",$units);
        $GLOBALS['em']->flush();
    }
    else
    {
        $sigUnits->setAttribute("value",$medSIG->getUnits());                
    }
    $sigSchedule=createInput($DOM,$parent,"schedule");
    if(($medSIG->getSchedule()!=="") && ($medSIG->getSchedule()!=null))
    {
        $sigSchedule->setAttribute("value",$medSIG->getSchedule());
    }

    
    
    $parent->setAttribute("entryUUID",$medSIG->getUUID());
   
}


function createInputHeader($DOM,$table)
{
    $tr=$DOM->createElement("tr");
    
    $table->appendChild($tr);
    
    $tr->appendChild($DOM->createElement("th","QTY"));
    $tr->appendChild($DOM->createElement("th","UNITS"));
    $tr->appendChild($DOM->createElement("th","ROUTE"));
    $tr->appendChild($DOM->createElement("th","SCHEDULE"));
    
}

function qtyOptions($DOM,$parent)
{
    $table=$DOM->createElement("table");
    $parent->appendChild($table);
    
    $table->setAttribute("class","SIGQtyOptions");
    
    for($idx=1;$idx<=5;$idx++)
    {
        $td=$DOM->createElement("td",$idx);
        $tr=$DOM->createElement("tr");
        $tr->appendChild($td);
        $table->appendChild($tr);
    }
}

function scheduleOptions($DOM,$parent)
{
    $table=$DOM->createElement("table");
    $parent->appendChild($table);
    
    $table->setAttribute("class","SIGScheduleOptions");
    
    $options = array("once a day","twice a day","three times a day","four times a day"
        ,"every other day"
        ,"in the morning"
        ,"in the evening"
        ,"at bedtime");
    for($idx=0;$idx<count($options);$idx++)
    {
        $td=$DOM->createElement("td",$options[$idx]);
        $tr=$DOM->createElement("tr");
        $tr->appendChild($td);
        $table->appendChild($tr);
    }
}


function createSIGControls($DOM,$table)
{
    $tr=$DOM->createElement("tr");
    $tr->setAttribute("class","SIGControls");
    $table->appendChild($tr);
    
    // createQTY options
    $tdQTY=$DOM->createElement("td");
    $tr->appendChild($tdQTY);
    
    qtyOptions($DOM,$tdQTY);

    // createUnits options
    $tdUnits=$DOM->createElement("td");
    $tr->appendChild($tdUnits);

    // createRoute options
    $tdRoute=$DOM->createElement("td");
    $tr->appendChild($tdRoute);
    
    // createSchedule Options
    // createUnits options
    $tdSchedule=$DOM->createElement("td");
    $tr->appendChild($tdSchedule);
    
    scheduleOptions($DOM,$tdSchedule);
}
function generateMedSIGDialog($DOM,$medEntry,$pat,$auth)
{
    // Display Drug Info at top, then the input fields
    $retval=$DOM->createElement("section");
    $retval->setAttribute("entrytype",$medEntry->getType());
    $retval->setAttribute("entryuuid",$medEntry->getUUID());
    
    $drugInfo=$DOM->createElement("section");
    $drugInfo->setAttribute("class","SIGdrugInfo");
    
    $retval->appendChild($drugInfo);
    
    $drugLabel=$DOM->createElement("span",$medEntry->getText());
    $drugInfo->appendChild($drugLabel);
    
    
    $closeButton=$DOM->createElement("button","close");
    $closeButton->setAttribute("class","closeSIG");
    $drugInfo->appendChild($closeButton);
    
    //alternate drug info?

    $sigInput=$DOM->createElement("section");
    $sigInput->setAttribute("class","SIGInput");
    $retval->appendChild($sigInput);
    
    $attributes=$GLOBALS['em']->getRepository('library\doctrine\Entities\RXNORM\DrugAttribute')->findByrxcui($medEntry->getRXCUI());

    $dst=findAttribute($attributes,"DST");
    $ddf=findAttribute($attributes,"DDF");
    $drta=findAttribute($attributes,"DRTA");

    $SIGs=$medEntry->getSIGs($pat,$auth);
    $sigTable=$DOM->createElement("table");
    $sigTable->setAttribute("class","SIGTable");
    $sigInput->appendChild($sigTable);
    
    createInputHeader($DOM,$sigTable);
    foreach($SIGs as $SIG)
    {
        createInputs($DOM,$sigTable,$SIG, $dst, $drta, $ddf);
    }
    createSIGControls($DOM, $sigTable);
    
    return $retval;
}

?>
