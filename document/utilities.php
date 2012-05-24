<?php
include_once("$doctrineroot/common/EditorConstants.php");
function addScript(DOMDocument $DOM,DOMElement $parent,$scriptName)
{
    $retval =$DOM->createElement("script");
    $retval->setAttribute("src",$scriptName);
    $parent->appendChild($retval);
    return $retval;
}

function addStyle(DOMDocument $DOM, DOMElement $parent,$sheet)
{
    

    $retval=$DOM->createElement("style","@import url('".$sheet."');");
    $retval->setAttribute("type","text/css");
    $retval->setAttribute("media","all");
    $parent->appendChild($retval);
}

function populateEditorDOM(DOMDocument $DOM,DOMElement $parent,$docItem,$depth)
{
    $docEntry = $docItem->getEntry();
    $newElem=createElement($DOM,$parent,$docEntry,$docItem,$depth);
    foreach($docItem->getItems() as $childDI)
    {
        populateEditorDOM($DOM,$newElem,$childDI,$depth+1);
    }
    return $newElem;
    
}
function createTagElem($DOM,$docEntry,$tag, $depth,$text="")
{
    $retVal=$DOM->createElement($tag,$text);
    $retVal->setAttribute(ATTR_ENTRY_TYPE,$docEntry->getType());
    $retVal->setAttribute("CODE",$docEntry->getCode());
    $retVal->setAttribute("UUID",$docEntry->getUUID());
    $retVal->setIdAttribute("UUID",true);
    $retVal->setAttribute("depth",$depth);
    return $retVal;
}
function hasSectionVocab($code,$code_type)
{
    $mappings=$GLOBALS['em']->getRepository('library\doctrine\Entities\VocabMapping')->findBy(array("target_code"=>$code,"target_code_type"=>$code_type));
    if(count($mappings)>0)
    {
        return true;
    }
    else
    {
        return false;
    }
}
function createElement($DOM,$parent,$docEntry,$docItem,$depth)
{
    

    $text=htmlentities($docEntry->getText());
    
    switch($docEntry->getType())
    {
        case TYPE_SECTION:
                $section=createTagElem($DOM,$docEntry,"SECTION",$depth," ");
                $parent->appendChild($section);

                $label=$DOM->createElement("span",$text);
                $label->setAttribute("class","label");
                $section->appendChild($label);
                
                $content=$DOM->createElement("span"," ");
                $content->setAttribute("class","content");
                $section->appendChild($content);
                
                if(hasSectionVocab($docEntry->getCode(),$docEntry->getCode_type()))
                {
                    $section->setAttribute("vocab","true");
                }
                $retVal=$content;
            break;
        case TYPE_NARRATIVE:
                $retVal=createTagElem($DOM,$docEntry,"textarea",$depth,$text);
                $parent->appendChild($retVal);
                if(is_null($docEntry->getMetadata()))
                {
                    $retVal->setAttribute("canDelete","true");
                }
            break;
        
        case TYPE_PROBLEM:
            $retVal=createTagElem($DOM,$docEntry,"span",$depth," ");
            $label=$DOM->createElement("span",$text);
            $label->setAttribute("class","problemLabel");
            $retVal->appendChild($label);
            $retVal->setAttribute("class","problem");
            $retVal->setAttribute("canDelete","true");
            $parent->appendChild($retVal);
            break;
        case TYPE_MEDICATION_ENTRY:
        case TYPE_MED_SIG:
        case TYPE_DOC_LINK:
        case TYPE_IMAGE_ENTRY:
        case TYPE_OBSERVATION:
        default:
            $retVal=createTagElem($DOM,$docEntry,"span",$depth,$text);
            $retVal->setAttribute("class","unknown");
            $parent->appendChild($retVal);

    }
    return $retVal;    
}

?>


