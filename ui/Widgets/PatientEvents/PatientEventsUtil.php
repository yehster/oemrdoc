<?php

function generate_patient_events($em,$DOM,$parentElem,$pat)
{
    if(!isset($parentElem))
    {
        $parentElem=$DOM->createElement("section");
    }
    $event_type_select=$DOM->createElement("select");
    $parentElem->appendChild($event_type_select);
    
    $event_type_option=$DOM->createElement("option","Hello World");
    $event_type_select->appendChild($event_type_option);
    
    return $parentElem;
}
?>
