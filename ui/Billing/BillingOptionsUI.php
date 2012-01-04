<?php

function BillingControl($DOM,$parent,$type,$name,$label)
{
    $div=$DOM->createElement("DIV");
    $parent->appendChild($div);
    $lblControl=$DOM->createElement("SPAN",$label);
    $div->appendChild($lblControl);
    $retval=$DOM->createElement("INPUT");
    $retval->setAttribute("type",$type);
    $retval->setAttribute("name",$name);
    $div->appendChild($retval);
    return $retval;
}
function BillingOptionsUI($DOM,$parent)
{

    
    BillingControl($DOM,$parent,"text","onsetDate","Date of Onset");
    BillingControl($DOM,$parent,"checkbox","empRelated","Employment related");
    BillingControl($DOM,$parent,"checkbox","autoRelated","Auto Accident");
    BillingControl($DOM,$parent,"text","autoState","Place(state)");
    BillingControl($DOM,$parent,"checkbox","accidentRelated","Other Accident");
    
}
?>
