<?php

include_once('/var/www/openemr/library/doctrine/init-em.php');
require_once('../../interface/checkAuth.php');
require_once('../../util/tokenize.php');
require_once('reviewHistorySectionsUtil.php');



if(isset($_REQUEST['relDocUUID']))
{
    
    // This is the uuid of the document relative to which we should search for other documents.
    $relDocUUID=$_REQUEST['relDocUUID'];
    $relDoc=$em->getRepository('library\doctrine\Entities\Document')->find($relDocUUID);
    if(!isset($relDoc))
    {
        header("HTTP/1.0 403 Forbidden"); 
        echo  "Can't Find Document";
        return;
    }
}
if(isset($_REQUEST['curDocUUID']))
{
    $curDocUUID=$_REQUEST['curDocUUID'];  
}
else
{
    header("HTTP/1.0 403 Forbidden"); 
    echo  "Current Document not specified";
    return;

}




if(isset($_REQUEST['sectionUUIDs']))
{
    $sectionUUIDs=$_REQUEST['sectionUUIDs'];
    $sectionToks=tokenize($sectionUUIDs,"|");
    $sections=findSections($sectionToks);
}

if(isset($_REQUEST['direction']))
{
    // Positive = next, negative = previous
    $direction=intval($_REQUEST['direction']);
    $reviewDoc=findReviewDoc($relDoc,$curDocUUID,$direction,$sections);
    
}

$DOM = new DOMDocument("1.0","utf-8");
$navSections=array();
if(isset($reviewDoc['seqDoc']))
{
    $navSections[$direction]=$reviewDoc['seqDoc'];
}
$navSearch=findReviewDoc($reviewDoc['doc'],$curDocUUID,-($direction),$sections);
if(isset($navSearch['doc']))
{
    $navSections[-($direction)]=$navSearch['doc'];
}

if(isset($navSections[-1]))
{
    $but=$DOM->createElement("BUTTON","PREV");
    $but->setAttribute("relDoc",$reviewDoc['doc']->getUUID());
    $but->setAttribute("direction","-1");
    echo $DOM->saveXML($but);
}
if(isset($navSections[1]))
{
    $but=$DOM->createElement("BUTTON","NEXT");
    $but->setAttribute("relDoc",$reviewDoc['doc']->getUUID());
    $but->setAttribute("direction","1");
    echo $DOM->saveXML($but);
}


echo "<DIV>".$reviewDoc['doc']->getModified()->format(DateTIME::ISO8601)."</DIV>";
foreach($reviewDoc['sections'] as $histSection)
{
    $curSection=$DOM->createElement("SECTION");
    displayHistoricalElements($DOM,$curSection,$histSection,1);
    echo $DOM->saveXML($curSection);
}

?>
