<?php

function generate_event_option($DOM,$parent,$id,$text)
{
    $option=$DOM->createElement("option",$text);
    $option->setAttribute("value",$id);
    
    $parent->appendChild($option);
    return $option;
}

function generate_event_options($em,$DOM,$parent)
{
        $qb = $em->createQueryBuilder()
        ->select("es")
        ->from("library\doctrine\Entities\PatientEventStatus","es");
        $qb->orderBy("es.seq","asc");

    $qry=$qb->getQuery();
    $res=$qry->getResult();        
    
    generate_event_option($DOM,$parent,0,"&nbsp;");
    foreach($res as $es)
    {
        generate_event_option($DOM,$parent,$es->getID(),$es->getText());
    }
        
}

function generate_patient_events($em,$DOM,$parentElem,$pat)
{
    if(!isset($parentElem))
    {
        $parentElem=$DOM->createElement("section");
    }
    $event_type_select=$DOM->createElement("select");
    
    $event_type_select->setAttribute("class","patient_event_select");
    $parentElem->appendChild($event_type_select);

    generate_event_options($em,$DOM,$event_type_select);
    
    return $parentElem;
}
?>
