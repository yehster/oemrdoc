<?php

function generate_event_option($DOM,$parent,$id,$text)
{
    $option=$DOM->createElement("option",$text);
    $option->setAttribute("value",$id);
    
    $parent->appendChild($option);
    return $option;
}

function generate_event_options($em,$DOM,$parent,$patientEvent)
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
        $opt=generate_event_option($DOM,$parent,$es->getID(),$es->getText());
        if(isset($patientEvent))
        {
            if($es->getID()==$patientEvent->getStatus()->getID())
            {
                $opt->setAttribute("selected","true");
            }
        }
    }
        
}

function generate_patient_events($em,$DOM,$parentElem,$pat,$patientEvent)
{
    if(!isset($parentElem))
    {
        $parentElem=$DOM->createElement("span");
        $parentElem->setAttribute("id","patient_events_widget");
    }
    $event_type_select=$DOM->createElement("select");
    
    $event_type_select->setAttribute("class","patient_event_select");
    $parentElem->appendChild($event_type_select);

    
    generate_event_options($em,$DOM,$event_type_select,$patientEvent);
    
    if(isset($patientEvent))
    {
        $eventTime=$DOM->createElement("span",$patientEvent->getTime()->format("h:i:s a"));
        $parentElem->appendChild($eventTime);
    }
    return $parentElem;
}
?>
