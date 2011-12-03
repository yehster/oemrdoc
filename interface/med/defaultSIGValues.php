<?php

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

function determineUnits($dst,$ddf,$drta)
{
    
    $loc=strpos($dst,'/');
    doctrine_log("DST:".$dst);
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

function setDefaultSIGValues($em,$sig,$med)
{
    $attributes=$em->getRepository('library\doctrine\Entities\RXNORM\DrugAttribute')->findByrxcui($med->getRXCUI());
    
    $dst=findAttribute($attributes,"DST");
    $ddf=findAttribute($attributes,"DDF");
    $drta=findAttribute($attributes,"DRTA");
    
    $sig->setUnits(determineUnits($dst,$ddf,$drta));
    $sig->setRoute($drta);
    
    $sig->setSchedule("Daily");
    $sig->setQuantity("1");
    
}
?>
