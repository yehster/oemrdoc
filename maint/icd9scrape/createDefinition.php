<?php
include('/var/www/openemr/library/doctrine/init-em.php');

$code=$_REQUEST['code'];
$info=$_REQUEST['info'];
$seq=intval($_REQUEST['seq']);

$def=$em->getRepository('library\doctrine\Entities\ICD9\ICD9Definition')->findOneBy(array("code"=>$code,"seq"=>$seq));
$dct_code=$em->getRepository('library\doctrine\Entities\ICD9\ICD9Code')->findOneBy(array("code"=>$code));


error_log($code.":".$seq.":".$info);

if($def==null)
{
    $def=new library\doctrine\Entities\ICD9\ICD9Definition($code,$seq,$info,$dct_code);
    $em->persist($def);
    $em->flush();
}
?>
