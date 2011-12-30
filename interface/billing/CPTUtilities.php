<?php

$OVC=array("99201","99202","99203","99204","99205","99211","99212","99213","99214","99215");
function codesInGroup($code,$codeType)
{
    // hard code this for now.
    if(strpos($code,"992")==0)
    {
        $c=substr($code,3,1);
        if(($c=="0")||($c=="1"))
        {
            $retval="";
            foreach($GLOBALS['OVC'] as $c)
            {
                $retval=$retval.","."'".$c."'";
            }
            $retval=substr($retval,1);
        }
        
    }
    else {
        $retval="'".$code."'";
    }
    
    return "(".$retval.")";
}

/*
 * determines if there is a billing entry within a code group. (e.g. an established patient or new patient entry) and returns it if so.
 */
function findCPTinGroup($em,$document,$code,$codeType)
{
    $patient=$document->getPatient();
    $pid=$patient->getPID();
    $enc=$document->getOEMREncounter();    
    $codeList=codesInGroup($code,$codeType);
    
    $qb = $em->createQueryBuilder()
        ->select("cpt")
        ->from("library\doctrine\Entities\OEMRBillingEntry","cpt")
        ->where("cpt.encounter=:enc")
        ->andWhere("cpt.code_type=:ct")
        ->andWhere("cpt.pid=:pat")
        ->andWhere("cpt.code in ".$codeList);

    $qb->setParameter("enc",$enc->getEncounter());
    $qb->setParameter("ct",$codeType);
    $qb->setParameter("pat",$pid);
    
    $qry=$qb->getQuery();
    $res=$qry->getResult();    
    return $res;
    
}

function codeTypeID($ct)
{
    if($ct=="CPT4")
    {
        return 1;
    }
    elseif($ct="HCPCS")
    {
        return 3;
    }
}

function billForCPT($em,$document, $codeVal, $codeType,$justify)
{
    $code=findCPTinGroup($em,$document,$codeVal,$codeType);
    $enc=$document->getOEMREncounter();    
    $patient=$document->getPatient();
    $pid=$patient->getPID();
    
    if(count($code)==0)
    {
                $cpt=new library\doctrine\Entities\OEMRBillingEntry($enc,$GLOBALS['doctrineUser']);
                $cpt->setPID($pid);
                $cpt->setCode($codeVal);
                $cpt->setCode_type($codeType);
                $em->persist($cpt);
    }
    elseif(count($code)==1)
    {
        $cpt=$code[0];
        $cpt->setCode($codeVal);
    }
        $dct_code=$em->getRepository('library\doctrine\Entities\Code')->findOneBy(array('code'=>$codeVal,'code_type'=>codeTypeID($codeType)));
        $price=$em->getRepository('library\doctrine\Entities\OEMR\OEMRPrice')->findOneBy(array('pr_id'=>$dct_code->getId(),'pr_level'=>$patient->getPricelevel(),'pr_selector'=>''));
            $cpt->setProvider_id($enc->getProvider_id());
            $cpt->setAuthorized(1);
            $cpt->setActivity(1);
            $cpt->setUnits(1);
            $cpt->setGroupname("Default");
            $cpt->setCode_text($dct_code->getCodeText());
            $cpt->setFee($price->getPrice());

    if($cpt->getJustify()=="")
    {
        $cptJustifyString="";
        foreach($justify as $prob)
        {
            $cptJustifyString.=$prob->getCode().":";
        }
        $cpt->setJustify($cptJustifyString);    
    }
    else
    {
        $curCPT=$cpt->getJustify();
        $cptJustifyString="";
        foreach($justify as $prob)
        {
            $diagString=$prob->getCode().":";
            if(strpos($curCPT,$diagString)!==false)
            {
                $cptJustifyString.=$diagString;
            }
        }
        $cpt->setJustify($cptJustifyString);            
    }
    $em->flush();

    
}

?>
