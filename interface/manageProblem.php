<?php
include_once("/var/www/openemr/interface/globals.php");
include_once('/var/www/openemr/library/doctrine/init-em.php');
$user = $_SESSION['authUser'];
if(isset($_SESSION['pid']))
{
    $patID=$_SESSION['pid'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
}
if(isset($_REQUEST['sectionUUID']))
{
    $docEntryUUID = $_REQUEST['sectionUUID'];
    $docEntry = $em->getRepository('library\doctrine\Entities\DocumentEntry')->find($docEntryUUID);
}
if(isset($_REQUEST['task']))
{
    $task = $_REQUEST['task'];
}
if(isset($_REQUEST['content']))
{
    $content = $_REQUEST['content'];
}
if(isset($_REQUEST['code']))
{
    $code = $_REQUEST['code'];
}
if($task=="create")
{
        if($codeType==null)
        {
            $codeType = "ICD9";
        }
        if($pat!=null)
        {
            if($docEntry!=null)
            {
                $prob = new library\doctrine\Entities\Problem(null,$pat,$user);
                $prob->setCode($code,$codeType);
                $prob->setText($content,$user);
                $docEntry->getItem()->addEntry($prob);
                $em->persist($prob);
                $em->flush();
                echo $prob->getUUID();
                return;
            }
        }
}
echo "error:";

?>
