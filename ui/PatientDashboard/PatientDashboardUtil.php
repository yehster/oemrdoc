<?php

function cst_header($DOM,$parent)
{
    $tr=$DOM->createElement("tr");
    $parent->appendChild($tr);
    
    $thPatient=$DOM->createElement("th","Patient");
    $tr->appendChild($thPatient);

    $thStatus=$DOM->createElement("th","Status");
    $tr->appendChild($thStatus);
    
    $thTime=$DOM->createElement("th","Time");
    $tr->appendChild($thTime);
    
}

function find_events($em,$users)
{


    $beg=new \DateTime();
    $beg->setTime(0, 0, 0);
    $end=clone $beg;
    $end=$end->add(new DateInterval("P1D"));
    
    $qb = $em->createQueryBuilder()
        ->select("evt")
        ->from("library\doctrine\Entities\PatientEvent","evt")
        ->join("evt.status","st");
        $qb->Where("evt.time=(select max(esub.time) from library\doctrine\Entities\PatientEvent as esub where esub.patient=evt.patient and esub.time>:beg and esub.time<:end)");
        $qb->orderBy("st.priority","desc");
        $qb->addOrderBy("evt.time","asc");
        
    $qb->setParameter("beg",$beg);
    $qb->setParameter("end",$end);
    
    $qry=$qb->getQuery();
    $res=$qry->getResult(); 
    return $res;
}

function create_event($DOM,$parent,$event)
{
    $tr=$DOM->createElement("tr");
    $parent->appendChild($tr);
    
    $patient=$event->getPatient();
    $tdPatient=$DOM->createElement("td",$patient->displayName());
    $tdPatient->setAttribute("patID",$patient->getID());
    $tr->appendChild($tdPatient);
    
    $tdStatus=$DOM->createElement("td",$event->getStatus()->getText());
    $tr->appendChild($tdStatus);
    
    $tdTime=$DOM->createElement("td",$event->getTime()->format("h:i A"));
    $tr->appendChild($tdTime);
}
function create_status_table($em,$DOM,$users)
{
    $table=$DOM->createElement("table");
    $table->setAttribute("class","dashboard");
    
    $tbody=$DOM->createElement("tbody");
    $table->appendChild($tbody);
    
    cst_header($DOM,$tbody);
    
    $events=find_events($em,$users);
    foreach($events as $evt)
    {
        create_event($DOM,$tbody,$evt);
    }
    return $table;
}

?>
