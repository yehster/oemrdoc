<?php
/* 
 * creates a new document for the current user using a patient ID and a
 * Metadata object
 */
include_once("/var/www/openemr/interface/globals.php");
include_once('/var/www/openemr/library/doctrine/init-em.php');
$user = $_SESSION['authUser'];
if(isset($_SESSION['pid']))
{
    $patID=$_SESSION['pid'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
}
if(isset($_POST['metadataUUID']))
{
    $metadataUUID = $_POST['metadataUUID'];
}
if($pat!=null)
{
    if($metadataUUID!=null)
    {
        $md=$em->getRepository('library\doctrine\Entities\DocumentMetadata')->find($metadataUUID);
        if($md!=null)
        {
            $OEMREnc=null;
            $doc = new library\doctrine\Entities\Document($pat,$user,$md);
            if(isset($_SESSION['encounter']))
            {
                $encID=intval($_SESSION['encounter']);
                $OEMREnc=$em->getRepository('library\doctrine\Entities\OEMREncounter')->findOneBy(array('encounter'=>$encID,'patient'=>$patID));               
                $doc->setOEMREncounter($OEMREnc);
            }
            $em->persist($doc);
            $em->flush();
        }
    }
    echo $doc->getUUID();
}
?>
