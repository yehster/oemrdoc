<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
    $DOM = new DOMDocument("1.0","utf-8");
    $spanDoctrine=$DOM->createElement("SPAN");
    $spanDoctrine->setAttribute("id","doctrineInfo");
    
        $sel=$DOM->createElement("SELECT");
        $sel->setAttribute("ID","doctrineType");
        $spanDoctrine->appendChild($sel);
            $qry = $em->createQuery("SELECT dt FROM library\doctrine\Entities\DocumentType dt");
            $res = $qry->getResult();
            foreach($res as $value)
            {
                $div=$DOM->createElement("OPTION",$value->getlongDesc());
                $div->setAttribute("uuid",$value->getuuid());
                $div->setAttribute("class","Metadata");
                $sel->appendChild($div);
            }
        $createButton=$DOM->createElement("BUTTON","Create Document");
        $createButton->setAttribute("onClick","
            uuid=$('#doctrineType option:selected').attr('uuid');
                   $.post('/openemr/library/doctrine/interface/createDocument.php', 
                   {metadataUUID: \"\"+uuid+\"\"}, 
                   function(data){
            if(data.length >0) {
            window.location='/openemr/library/doctrine/ui/DocumentEditor/DocumentEditor.php?docUUID='+data;
            }});
;");
        $spanDoctrine->appendChild($createButton);
        $spanDoctrine->appendChild($DOM->createElement("BR"));
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
                $editorLink=$DOM->createElement("A",
                $value->getMetadata()->getText()." ".
                        $value->getModified()->format(DATE_COOKIE)."(".
                        $value->getAuthor().")");
                $link='/openemr/library/doctrine/ui/Editor/Editor.php?docUUID='.$value->getuuid();
                $link='/openemr/library/doctrine/ui/DocumentEditor/DocumentEditor.php?docUUID='.$value->getuuid();
                
                $editorLink->setAttribute("HREF",$link);
//                $editorLink->setAttribute("target","_new");
                $spanDoctrine->appendChild($editorLink);
                $spanDoctrine->appendChild($DOM->createElement("BR"));
                }
}

echo $DOM->saveXML($spanDoctrine);
?>
