<?php

$ratioUnits = array("/min");
$pressureUnits = array("mm[Hg]");
$tempUnits = array("&deg;F","&deg;C");
$massUnits = array("lbs","kg");
$lengthUnits = array("inches","cm");
$units = array("NRat"=>$ratioUnits, "Pres"=>$pressureUnits,"Temp"=>$tempUnits,"Mass"=>$massUnits,"Len"=>$lengthUnits);
    

function AddUnitSelector($DOM,$ELEMParent,$property)
{
    global $units;
    if(array_key_exists ($property,$units))
    {
        $uarray=$units[$property];
    }
    else 
    {
        return;
    }
    if(count($uarray)>0)
    {
        $sel = $DOM->createElement("SELECT");
        $ELEMParent->appendChild($sel);
        for($idx=0;$idx<count($uarray);$idx++)
        {
            $opt = $DOM->createElement("OPTION",$uarray[$idx]);
            $opt->setAttribute("units",htmlentities($uarray[$idx]));
            $sel->appendChild($opt);
        }
    }
    if(isset($sel))
    {
        return $sel;    
    }
}

?>
