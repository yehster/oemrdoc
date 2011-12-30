<?php

function syncProblems($em,$document, $clear = false)
{
    if($clear)
    {
         $enc=$document->getOEMREncounter();
         if($enc!=null)
         {

             foreach($enc->getBillingEntries() as $be)
             {            
                 if($be->getCode_type()=="ICD9")
                 {
                     $enc->getBillingEntries()->removeElement($be);
                     $em->remove($be);
                     
                 }
             }
         }
         $em->flush();
    }
    $qb = $em->createQueryBuilder()
        ->select("pr")
        ->from("library\doctrine\Entities\Problem","pr")
        ->innerJoin("pr.item", "di")
        ->where("di.root=:root")
        ->orderBy("di.seq","asc");
   
    $qb->setParameter("root",$document);
    $qry=$qb->getQuery();
    $res=$qry->getResult();    
    $enc=$document->getOEMREncounter();
    $patient=$document->getPatient();
    $pid=$patient->getPID();
    $retval=array();
    foreach($res as $problem)
    {
        if($problem->getCode_type()=="ICD9")
        {
        
            $billingProblem=$em->getRepository("library\doctrine\Entities\OEMRBillingEntry")
                ->findOneBy(array("encounter"=>$enc->getEncounter(),
                                  "code"=>$problem->getCode(),
                                  "code_type"=>$problem->getCode_type(),
                                  "pid"=>$pid
                                  ));
        
            if($billingProblem==null)
            {
                $billingProblem=new library\doctrine\Entities\OEMRBillingEntry($enc,$GLOBALS['doctrineUser']);
                $billingProblem->setPID($pid);
                $billingProblem->setCode($problem->getCode());
                $billingProblem->setCode_type($problem->getCode_type());
                $em->persist($billingProblem);
                $enc->getBillingEntries()->add($billingProblem);
            }
            $billingProblem->setCode_text($problem->getText());
            $billingProblem->setProvider_id($enc->getProvider_id());
            $billingProblem->setAuthorized(1);
            $billingProblem->setActivity(1);           
            $billingProblem->setGroupname("Default");
            $billingProblem->setUnits(1);
            $billingProblem->setFee(0);
            $retval[]=$billingProblem;
            $em->flush();
            $em->refresh($enc);
        }

    }
    return $retval;
}

?>
