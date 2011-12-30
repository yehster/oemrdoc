<?php


function verifyJustify($OEMRBE,$justifyCode)
{
    $enc=$OEMRBE->getEncounter();
    $justify="";
    foreach($enc->getBillingEntries() as $be)
    {
         if($be->getCode_type()=="ICD9")
         {    
             if(($be->getCode()==$justifyCode) ||
                 (strpos($OEMRBE->getJustify(),$be->getCode())!==false))
             {
                 $justify.=$be->getCode().":";
             }
         }
    } 
    return $justify;
}
?>
