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

function createInputs($DOM,$parent,$medSIG, $dst, $drta, $ddf)
{
    $sigQty=createInput($DOM,$parent,"SIGQty");
    $sigUnits=createInput($DOM,$parent,"SIGUnits");

    $sigRoute=createInput($DOM,$parent,"SIGRoute");
    $sigRoute->setAttribute("value",$drta);
    
    
    $sigFrequency=createInput($DOM,$parent,"SIGSchedule");
     $loc=strpos($dst,'/');
        echo $dst;
        if($loc)
    {

        if(strpos($dst,"units"))
        {
            $sigUnits->setAttribute("value","units");        
        }
        elseif(strpos($dst,"mg"))
        {
            if(strpos($dst,"mL"))
            {
                $sigUnits->setAttribute("value","mL");                    
            }
        }
        elseif($drta==="INH")
        {
                $sigUnits->setAttribute("value","puffs");                                
        }
    }
    else
    {
        if($drta==="OPH")
        {
            $sigUnits->setAttribute("value","gtt");
        }
        elseif($drta==="INH")
        {
            
        }
        else
        {
            $sigUnits->setAttribute("value",$ddf);
        }
    }
    
    
    $sigSave=$DOM->createElement("BUTTON","save");
    $parent->appendChild($sigSave);
   
}

function generateMedSIGDialog($DOM,$medEntry)
{
    // Display Drug Info at top, then the input fields
    $retval=$DOM->createElement("section");
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

    
    createInputs($DOM,$sigInput,$medSIG, $dst, $drta, $ddf);
    return $retval;
}

?>
