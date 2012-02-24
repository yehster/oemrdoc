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
    $GLOBALS['em']->persiet($evt);
    $GLOBALS['em']->flush();
}

function verifyPatient($em,$patientName,$PID,$DOB, &$pat)
{
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->findOneBy(array('pubpid'=>$PID));
    if($pat==null)
    {
        return "No match for patient ID:".$PID . " ". $patientName;
    }
    else
    {
        $dtDOB=  new \DateTime($DOB);
        if($dtDOB!=$pat->getDOB())
        {
            return "DOB doesn't match";
        }
        $pct=0;
        similar_text($patientName,$pat->normName(),$pct);
        if($pct<60)
        {
            return "Names don't match:".$PID.":".$patientName."(document):".$pat->normName()."(EMR)";
            $pat=null;
        }
    }
    return "";
}

function matchPatient($em,$libreFile,$XMLDom,&$pat)
{
    
    try
    {
        $GLOBALS['matchPatientFile']=$libreFile;
        $pi=$XMLDom->getElementsByTagName("PatientIdentifiers")->item(0);
        $elemPID=$pi->getElementsByTagName("PID");
        $PID=$elemPID->item(0)->nodeValue;
        
        $elemDOB=$pi->getElementsByTagName("DateOfBirth");
        $DOB=$elemDOB->item(0)->nodeValue;
        
        $elemPatient=$pi->getElementsByTagName("Patient");
        $patient=$elemPatient->item(0)->nodeValue;
        $msg=verifyPatient($em, $patient, $PID, $DOB,$pat);
        if($msg=="")
        {
            $evt=new library\doctrine\Entities\libre\libreEventPatientID($libreFile,true,$PID);
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

function identifyDictator($em,$libreFile,$DOM,&$user)
{
        $pi=$DOM->getElementsByTagName("DocumentInfo")->item(0);
        $elemDictator=$pi->getElementsByTagName("dictator")->item(0);
        $elemFirstName=$elemDictator->getElementsByTagName("FirstName")->item(0);
        $elemLastName=$elemDictator->getElementsByTagName("LastName")->item(0);
        $FirstName=$elemFirstName->nodeValue;
        $LastName=$elemLastName->nodeValue;
        echo $FirstName ." ". $LastName ."\n";
    
        $user=$em->getRepository('library\doctrine\Entities\User')->findOneBy(array('fname'=>$FirstName,'lname'=>$LastName));
        if($user==null)
        {
            $msg="Couldn't find user for:".$FirstName . " ". $LastName;
            $evt=new library\doctrine\Entities\libre\libreEventUserID($libreFile,false,$msg);
        }
        else
        {
            $evt=new library\doctrine\Entities\libre\libreEventUserID($libreFile,true,$user->getUsername());
        }
        return $evt;
}


function findOrCreateTranscriptionInfo($em,$libreFile,$DOM,$user,$pat)
{
    $auth=$user->getUsername();
    $de=$em->getRepository('library\doctrine\Entities\TranscriptionInfo')->findOneBy(array('patient'=>$pat->getID(),'author'=>$auth,'transcription_file'=>$libreFile->getFilename()));
    
    if($de==null)
    {
        $md=$em->getRepository('library\doctrine\Entities\DocumentType')->findOneBy(array('shortDesc'=>'Trans'));
        if($md!=null);
        $doc=new \library\doctrine\Entities\Document($pat,$auth,$md);
        $de=$doc->getItems()->get(0)->getEntry();
        $de->setTranscriptionFile($libreFile);
        $em->persist($doc);
        $em->flush();
        
    }
    echo $de->getUUID();
    
}
function createLibreDocument($em,$libreFile,$DOM,$user,$pat)
{
    $doc=findOrCreateTranscriptionInfo($em,$libreFile,$DOM,$user,$pat);
}
?>
