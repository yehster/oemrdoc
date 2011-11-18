<?php
include('/var/www/openemr/library/doctrine/init-em.php');
require_once("../../../common/checkInfo.php");

$DOM=new DOMDocument("1.0","utf-8");
$sel=$DOM->createElement("SELECT");

   $qb = $em->createQueryBuilder()
        ->select("fso")
        ->from("library\doctrine\Entities\OEMR\OEMRFSOption","fso")
        ->orderBy("fso.fs_category", "desc")
        ->addOrderBy("fso.fs_option","asc");

    $qry=$qb->getQuery();
    $res=$qry->getResult();
    $opt=$DOM->createElement("option","Choose Visit Level");
    $sel->appendChild($opt);
    for($idx=0;$idx<count($res);$idx++)
    {
        $curFSO=$res[$idx];
        $opt=$DOM->createElement("option",$curFSO->getOption()."-".$curFSO->getCategory());
        $opt->setAttribute("value",$curFSO->getCode());
        $sel->appendChild($opt);
    }


echo $DOM->saveXML($sel);

?>
