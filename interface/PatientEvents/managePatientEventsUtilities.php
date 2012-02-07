<?php

function create_patient_event($em,$author,$pat,$for_user,$event_type)
{
    $new_event= new library\doctrine\Entities\PatientEvent($pat,$author,$event_type);
    $em->persist($new_event);
    $em->flush();
    return $new_event;
}
?>
