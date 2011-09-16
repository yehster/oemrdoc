<?php
function createInput($DOM,$parent,$class)
{
    $retval = $DOM->createElement("input");
    $retval->setAttribute("type","text");
    $retval->setAttribute("class",$class);
    
    
    $parent->appendChild($retval);
    return $retval;
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

    
    
    $sigSave=$DOM->createElement("BUTTON","save");
    $parent->appendChild($sigSave);
    $parent->setAttribute("entryUUID",$medSIG->getUUID());
   
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
    foreach($SIGs as $SIG)
    {
        createInputs($DOM,$sigInput,$SIG, $dst, $drta, $ddf);
    }
    return $retval;
}

?>
