<?php
include('/var/www/openemr/library/doctrine/init-em.php');

$code=$_REQUEST['code'];
$info=$_REQUEST['info'];
$type=$_REQUEST['type'];
$parent=empty($_REQUEST['parent']) ? NULL : $_REQUEST['parent'];
$links=empty($_REQUEST['links']) ? "Not Set" : $_REQUEST['links'];


function stripCode($code,$info)
{
    $loc=strpos($info,$code);
    if($loc===false)
    {
        return $info;
    }
    else
    {
        return substr($info,0,$loc-1);    
    }
}

//error_log($code.":".$info.":".":".$links);
if($parent!=NULL)
{
    $parentICD9=$em->getRepository('library\doctrine\Entities\ICD9\ICD9Code')->findOneBy(array("code"=>$parent));
}
 else {
     $parentICD9=null;
}
$info=stripCode($code,$info);
   $codeICD9=$em->getRepository('library\doctrine\Entities\ICD9\ICD9Code')->findOneBy(array("code"=>$code));
   if($codeICD9==null)
   {
       if($type=="SECTION")
       {
           $codeICD9=new \library\doctrine\Entities\ICD9\ICD9Section($code,$info,$parentICD9);
//           error_log($info);
           $em->persist($codeICD9);
           $em->flush();
       }
       elseif ($type=="NS")
       {
           $codeICD9=new \library\doctrine\Entities\ICD9\ICD9NSCode($code,$info,$parentICD9);
           error_log($info);
           $em->persist($codeICD9);
           $em->flush();
           
       }
       elseif ($type=="SP")
       {
           $codeICD9=new \library\doctrine\Entities\ICD9\ICD9SPCode($code,$info,$parentICD9);
           error_log($info);
           $em->persist($codeICD9);
           $em->flush();
       }
   }
   else
   {
       if($parentICD9!=null)
       {
           $codeICD9->setParent($parentICD9);       
       }
       $em->flush();
   }

$em->close();



?>
