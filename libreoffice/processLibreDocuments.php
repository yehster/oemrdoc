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
    $GLOBALS['em']->persist($evt);
    $GLOBALS['em']->flush();
}

function verifyPatient($em,$patientName,$PID,$DOB, &$pat)
{
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->findOneBy(array('pubpid'=>$PID));
    if($pat==null)
    {
        return "No match for patient ID:".$PID . " ". $patientName. " ".$DOB;
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

        
    }
    else
    {
        $doc=$de->getItem()->getRoot();
    }
    return $doc;
}

function findOrCreateSection($em,$doc,$header,$pat,$auth)
{
    $items=$doc->getItems();
    foreach($items as $item)
    {
        $docEntry=$item->getEntry();
        if($docEntry->getType()=="Section")
        {
            if($docEntry->getText()==$header) return $docEntry;
        }
    }
    $retVal=new library\doctrine\Entities\Section(null,$pat,$auth);
    $retVal->setText($header,$auth);
    $di=new library\doctrine\Entities\DocumentItem($doc,null,$pat,$auth);
    $di->setSeq(count($items)+2);
    $di->setEntry($retVal);
    $doc->addItem($di);
    $em->persist($retVal);
    return $retVal;
}
function findOrCreateNarrative($em,$parent,$content,$pat,$auth)
{
    $items=$parent->getItem()->getItems();
    foreach($items as $item)
    {
        $docEntry=$item->getEntry();
        if($docEntry->getType()=="Narrative")
        {
            if($docEntry->getText()==$content) return $docEntry;
        }
    }
    
    $newNarrative=new library\doctrine\Entities\Narrative(null, $pat, $auth);
    $newNarrative->setText($content,$auth);
    $newItem=$parent->getItem()->addEntry($newNarrative);
    $em->persist($newNarrative);
    return $newNarrative;
}

function createLibreDocument($em,$libreFile,$DOM,$user,$pat)
{
    try
    {
        $auth=$user->getUsername();
        $doc=findOrCreateTranscriptionInfo($em,$libreFile,$DOM,$user,$pat);
        if($doc->isLocked())
        {
            $evt=new library\doctrine\Entities\libre\libreEventImport($libreFile,false,"can't modify locked document!");
            $em->persist($evt);
            $em->flush();        
            return;
        }
        $DOS=$DOM->getElementsByTagName("DateOfService")->item(0)->nodeValue;
        $dtDOS=  new \DateTime($DOS);
        $doc->setDateofservice($dtDOS);
        $sections=$DOM->getElementsByTagName("section");
        foreach($sections as $section)
        {
            if(!$section->hasAttribute("tag"))
            {
                $header=$section->getElementsByTagName("header")->item(0);
                $contents=$section->getElementsByTagName("content");
                $deSection=findOrCreateSection($em,$doc,$header->nodeValue,$pat,$auth);
                foreach($contents as $content)
                {
                    $nar=findOrCreateNarrative($em,$deSection,$content->nodeValue,$pat,$auth);
                }
            }
        }
        $evt=new library\doctrine\Entities\libre\libreEventImport($libreFile,true,$doc->getUUID());
        $em->persist($evt);
        $em->flush();        
    }
    catch(Exception $e)
    {
        error_log($e->getMessage());
    }
}
?>
