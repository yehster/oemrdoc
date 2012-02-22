<?php
function findOrCreatelibreFile($em,$filename)
{
    $lf=$em->getRepository('library\doctrine\Entities\libre\libreFile')->find($filename);
    if($lf===null)
    {
        $lf=new \library\doctrine\Entities\libre\libreFile($filename);
        $em->persist($lf);
    }
    return $lf;
}
function generateXMLFromDocument($em,$filename,$path)
{
    $shell_cmd="sh " .$GLOBALS['doctrineroot']."/shell/io.sh". " ".$filename. " ".$path;
    $shell_cmd=escapeshellcmd($shell_cmd);
    $output=array();
    $ret_val="";
    $last_output=exec($shell_cmd,$output,$ret_val);
    if($ret_val!="0")
    {
        $lastSlash=strrpos($filename,"/");
        $shortFile=substr($filename,$lastSlash+1);
        $extPos=strrpos($shortFile,".DOC");
        $shortFile=substr($shortFile,0,$extPos);
        $errMsg="";
        foreach($output as $line)
        {
            $errMsg .= $line."\n";
        }
        $lf = findOrCreatelibreFile($em,$shortFile);
        $evt = new library\doctrine\Entities\libre\libreEventXML($lf,false,$errMsg);
        $em->persist($evt);
        $em->flush();
        
    }
    else
    {
        $shortFile=$last_output;
        $path=$output[count($output)-2];
        $lm = findOrCreatelibreFile($em,$shortFile);
        $evt = new library\doctrine\Entities\libre\libreEventXML($lm,true,$path);
        $em->persist($evt);
        $em->flush();
    }
    return $evt;
}

function matchErrorHandler($errno,$errstr)
{
    $evt=new library\doctrine\Entities\libre\libreEventPatientID($GLOBALS['matchPatientFile'],false,$errstr);
    $GLOBALS['em']->flush();
}

function verifyPatient($em,$patientName,$PID,$DOB, &$pat)
{
    echo $PID."\n";
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->findOneBy(array('pubpid'=>$PID));
    if($pat==null)
    {
        return "No match for patient ID:".$PID . " ". $patientName;
    }
    else
    {
    }
    return "";
}

function matchPatient($em,$libreFile,$XMLDom)
{
    
    try
    {
        $GLOBALS['matchPatientFile']=$libreFile;
        set_error_handler("matchErrorHandler");
        $pi=$XMLDom->getElementsByTagName("PatientIdentifiers")->item(0);
        $elemPID=$pi->getElementsByTagName("PID");
        $PID=$elemPID->item(0)->nodeValue;
        
        $elemDOB=$pi->getElementsByTagName("DateOfBirth");
        $DOB=$elemDOB->item(0)->nodeValue;
        
        $elemPatient=$pi->getElementsByTagName("Patient");
        $patient=$elemPatient->item(0)->nodeValue;
        echo $patient.":".$PID.":".$DOB."\n";
        restore_error_handler();
        $pat=null;
        $msg=verifyPatient($em, $patient, $PID, $DOB,$pat);
        if($msg=="")
        {
            $evt=new library\doctrine\Entities\libre\libreEventPatientID($libreFile,true,$PID);
            echo $pat->displayName() .":returned by reference\n";
            $em->flush();    
        }
        else {
            $evt=new library\doctrine\Entities\libre\libreEventPatientID($libreFile,false,$msg);
            $em->flush();    
            
        }
    }
    catch(Exception $e)
    {
        $evt=new library\doctrine\Entities\libre\libreEventPatientID($libreFile,false,$e->getMessage());
    }
    return $evt;
}
?>
