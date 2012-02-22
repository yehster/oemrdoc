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

function matchPatient($em,$libreFile,$XMLDom)
{
    
}
?>
