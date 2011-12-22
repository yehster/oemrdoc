<?php

function createEncounter($em,$pat,$providerID,$sensitivity,$DOS,$doc,$username)
{

    $em->beginTransaction();
    
    
    $sequencesSQL="update sequences set id=LAST_INSERT_ID(id+1)";
    $conn = $em->getConnection();
    $conn->executeQuery($sequencesSQL);

    $encounter=$conn->lastInsertId();
    error_log("Sequence".$id);
    
    $OEMREnc=new library\doctrine\Entities\OEMREncounter($pat,$DOS,$encounter,$providerID,$sensitivity);

    // Fix hard coded cat id and other info
    $OEMREnc->setCatid(5);
    $OEMREnc->setReason("Autogenerated for document:".$doc->getUUID());
    $OEMREnc->setFacility_id(3);
    $OEMREnc->setBilling_facility(3);
    
    $em->persist($OEMREnc);
    $em->flush();

    $Enc=$em->getRepository('library\doctrine\Entities\OEMREncounter')->findOneBy(array('encounter'=>$encounter,'patient'=>$pat->getID()));
    $em->refresh($Enc);
    $fid=$Enc->getID();

    error_log(count($res));
    error_log("Encounter Form ID".$fid);
    $OEMRForm= new library\doctrine\Entities\OEMRForm($pat,$username,$encounter,$fid,"New Patient Encounter","newpatient");
    $OEMRForm->setEncounter($encounter);
    $em->persist($OEMRForm);
    
    $em->flush();

    $doc->setOEMREncounter($Enc);
    $em->flush();
    $em->commit();
    
}
?>
