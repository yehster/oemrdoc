<?php
include_once("$doctrineroot/common/EditorConstants.php");
include_once("$doctrineroot/DOMUtilities/DOMUtilities.php");
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

                $controls=$DOM->createElement("span"," ");
                $controls->setAttribute("class","controls");
                $section->appendChild($controls);
                
                
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
                $wrapper=createTagElem($DOM,$docEntry,"section",$depth," ");
                $parent->appendChild($wrapper);
                
                $retVal=$DOM->createElement("textarea",$text);
                $retVal->setAttribute("entrytype",$docEntry->getType());
                $wrapper->appendChild($retVal);

                $controls=$DOM->createElement("span"," ");
                $controls->setAttribute("class","controls");
                $wrapper->appendChild($controls);           

                if(is_null($docEntry->getMetadata()))
                {
                    $wrapper->setAttribute("canDelete","true");
                }
            break;
        
        case TYPE_PROBLEM:
            $container=createTagElem($DOM,$docEntry,"span",$depth," ");
            $container->setAttribute("class","problem");
            $container->setAttribute("canDelete","true");

            $label=$DOM->createElement("span",$text);
            $label->setAttribute("class","problemLabel label");        
            $container->appendChild($label);

            $controls=$DOM->createElement("span"," ");
            $controls->setAttribute("class","controls");
            $container->appendChild($controls);           
           
            $parent->appendChild($container);
            $retVal=$DOM->createElement("span");
            $retVal->setAttribute("class","content");
            $container->appendChild($retVal);
            
            break;
        case TYPE_MEDICATION_ENTRY:
            $container=createTagElem($DOM,$docEntry,"span",$depth," ");
            $label=$DOM->createElement("span",$text);
            $label->setAttribute("class","medicationLabel label");
            $container->appendChild($label);
            $container->setAttribute("class","medication");
            $container->setAttribute("canDelete","true");

            $controls=$DOM->createElement("span"," ");
            $controls->setAttribute("class","controls");
            $container->appendChild($controls);           
            
            $parent->appendChild($container);
            $retVal=$DOM->createElement("span");
            $retVal->setAttribute("class","content");
            $container->appendChild($retVal);
            
            break;
        case TYPE_MED_SIG:
            $retVal=createTagElem($DOM,$docEntry,"span",$depth,$text);
            $retVal->setAttribute("class","medSIG");
            $parent->appendChild($retVal);
            break;
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


