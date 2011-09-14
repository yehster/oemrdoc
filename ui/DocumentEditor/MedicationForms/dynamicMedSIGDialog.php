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
    
    
    $cancelButton=$DOM->createElement("button","cancel");
    $cancelButton->setAttribute("class","cancelSIG");
    $drugInfo->appendChild($cancelButton);
    
    //alternate drug info?

    $sigInput=$DOM->createElement("section");
    $sigInput->setAttribute("class","SIGInput");
    $retval->appendChild($sigInput);
    
    $attributes=$GLOBALS['em']->getRepository('library\doctrine\Entities\RXNORM\DrugAttribute')->findByrxcui($medEntry->getRXCUI());

    $dst=findAttribute($attributes,"DST");
    $ddf=findAttribute($attributes,"DDF");
    $drta=findAttribute($attributes,"DRTA");

    $sigQty=createInput($DOM,$sigInput,"SIGQty");
    $sigUnits=createInput($DOM,$sigInput,"SIGUnits");
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
    
    $sigRoute=createInput($DOM,$sigInput,"SIGRoute");
    $sigRoute->setAttribute("value",$drta);
    
    
    $sigFrequency=createInput($DOM,$sigInput,"SIGSchedule");
    
    
    return $retval;
}

?>
