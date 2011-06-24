<script src="/openemr/library/js/jquery-1.6.1.min.js" type="text/javascript"></script>
<script type="text/javascript">
    function createDoc() {

        uuid=$(this).attr("uuid");
       $.post("/openemr/library/doctrine/interface/createDocument.php", {metadataUUID: ""+uuid+""}, function(data){
            if(data.length >0) {
            window.location.reload()
            }});

//   $('#suggestions').hide();
}
    function setupEvents()
    {
        $("div.Metadata").live({click: createDoc});
    }
    window.onload=setupEvents;

</script>
<?php

include_once('/var/www/openemr/library/doctrine/init-em.php');
    $DOM = new DOMDocument("1.0","utf-8");
            $qry = $em->createQuery("SELECT dt FROM library\doctrine\Entities\DocumentType dt");
            $res = $qry->getResult();
            foreach($res as $value)
            {
                $div=$DOM->createElement("DIV",$value->getShortDesc());
                $div->setAttribute("uuid",$value->getuuid());
                $div->setAttribute("class","Metadata");
                $DOM->appendChild($div);
            }
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



    foreach($res as $value)
            {
                $editorLink=$DOM->createElement("A","Editor:".$value->getModified()->format(DATE_COOKIE));
                $link='/openemr/library/doctrine/ui/Editor/Editor.php?docUUID='.$value->getuuid();
                $link='/openemr/library/doctrine/ui/DocumentEditor/DocumentEditor.php?docUUID='.$value->getuuid();
                
                $editorLink->setAttribute("HREF",$link);
//                $editorLink->setAttribute("target","_new");
                $DOM->appendChild($editorLink);
                $DOM->appendChild($DOM->createElement("BR"));
                }
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

        <div id="results"></div>
        <?php
            echo $DOM->saveXML();
        ?>
    </body>
</html>
