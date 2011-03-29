<?php
include_once("/var/www/openemr/interface/globals.php");
include_once('/var/www/openemr/library/doctrine/init-em.php');
$user = $_SESSION['authUser'];
if(isset($_SESSION['pid']))
{
    $patID=$_SESSION['pid'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
    $qb = $em->createQueryBuilder()
        ->select("d")
        ->from("library\doctrine\Entities\Document","d")
        ->where("d.patient = ?1")
        ->orderBy("d.modified","DESC");
    $qb->setParameter(1,$pat);
    $qry=$qb->getQuery();
    $res=$qry->getResult();

    $DOM = new DOMDocument("1.0","utf-8");
    foreach($res as $value)
            {
                $editorLink=$DOM->createElement("A",$value->getModified()->format(DATE_COOKIE));
                $link='/openemr/library/doctrine/ui/DocumentEditor.php?docUUID='.$value->getuuid();
                $editorLink->setAttribute("HREF",$link);
                $DOM->appendChild($editorLink);
                $editorLink=$DOM->createElement("A","Editor:".$value->getModified()->format(DATE_COOKIE));
                $link='/openemr/library/doctrine/ui/Editor/Editor.php?docUUID='.$value->getuuid();
                $editorLink->setAttribute("HREF",$link);
                $editorLink->setAttribute("target","_new");
                $DOM->appendChild($editorLink);
                $DOM->appendChild($DOM->createElement("BR"));
                }
    echo $DOM->saveXML();

}

?>

<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        ?>
    </body>
</html>
