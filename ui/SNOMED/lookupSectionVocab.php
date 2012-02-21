<?php
include('/var/www/openemr/library/doctrine/init-session.php');

function getFormEntries($em,$c,$ct)
{
    $qb = $em->createQueryBuilder()
        ->select("fe")
        ->from("library\doctrine\Entities\VocabMapping","fe")
        ->where("fe.target_code=:tc")
        ->andWhere("fe.target_code_type=:tt")
        ->orderBy("fe.classification DESC,fe.modified","DESC");
    $qb->setParameter("tc",$c);
    $qb->setParameter("tt",$ct);
    $qb->orderBy("fe.classification DESC,fe.seq","ASC");
    $qry=$qb->getQuery();
    return $qry->getResult();
}

function createButton($DOM,$tr,$value,$class)
{
    $td = $DOM->createElement("td");
    $tr->appendChild($td);

    $but = $DOM->createElement("input");
    $td->appendChild($but);

    $but->setAttribute("value",$value);
    $but->setAttribute("type","button");
    $but->setAttribute("class",$class." modFE");
    
}

$options = array("","exclusive","multiple","option");
function createSelect($DOM,$tr,$name,$selected,$options)
{
    $td = $DOM->createElement("td");
    $tr->appendChild($td);

    $select=$DOM->createElement("select");
    $select->setAttribute("class",$name);
    $td->appendChild($select);
    for($idx=0;$idx<count($options);$idx++)
    {
        $opt=$options[$idx];
        $htmlOpt=$DOM->createElement("option",$opt);
        $select->appendChild($htmlOpt);
        if($opt==$selected)
        {
            $htmlOpt->setAttribute("selected"," ");
        }
    }

}

function addEntry($DOM,$table,$fe)
{
    global $options;
    $newTR = $DOM->createElement("tr");
    $newTR->setAttribute("class",$fe->getType()." ".$fe->getClassification());
    $newTR->setAttribute("uuid",$fe->getUUID());
    $newTR->setAttribute("source_code_type",$fe->getSource_code_type());
    $newTR->setAttribute("source_code",$fe->getSource_code());
    $table->appendChild($newTR);

    $tdText = $DOM->createElement("td",$fe->getText());
    $newTR->appendChild($tdText);

    createButton($DOM,$newTR,"del","del");
    createButton($DOM,$newTR,"up","up");
    createButton($DOM,$newTR,"dn","dn");
    if($fe->getType()=="FormEntry")
    {
        if($fe->getClassification()=="normal")
        {
            createButton($DOM,$newTR,"abn","abn");
        }
        elseif($fe->getClassification()=="abnormal")
        {
            createButton($DOM,$newTR,"nor","nor");
        }
    }
    elseif($fe->getType()=="Option")
    {
        createSelect($DOM,$newTR,"OptionSelect",$fe->getClassification(),$options);
    }
    elseif($fe->getType()=="VocabComponent")
    {
        $classTD=$DOM->createElement("td",$fe->getClassification());
        $newTR->appendChild($classTD);
    }

    


}

if(isset($_REQUEST['sectionUUID']))
{
    $sectionUUID = $_REQUEST['sectionUUID'];
    $section = $em->getRepository('library\doctrine\Entities\SectionHeading')->find($sectionUUID);
}

$DOM=new DOMDocument("1.0","utf-8");
$table=$DOM->createElement("table");
$table->setAttribute("class","formEntries");
$DOM->appendChild($table);
$entries = getFormEntries($em,$section->getCode(),$section->getCode_type());
for($idx=0;$idx<count($entries);$idx++)
{
    $entry=$entries[$idx];
    addEntry($DOM,$table,$entry);
}

echo $DOM->saveXML();
?>
