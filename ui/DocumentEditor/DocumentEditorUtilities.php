<?php
include_once('/var/www/openemr/library/doctrine/ui/Editor/EditorConstants.php');
function createButton($DOM,$Elem,$docEntry,$text,$func)
{
    if($docEntry->isLocked())
    {
        return;
    }
    if(isset($GLOBALS['review']))
    {
        return;
    }
    $but=$DOM->createElement("BUTTON",$text);
    $but->setAttribute("FUNC",$func);
    $but->setAttribute("EntryUUID",$docEntry->getUUID());
    $but->setAttribute(ATTR_ENTRY_TYPE,$docEntry->getType());
    $Elem->appendChild($but);
    return $but;
            
}
function createLabel($DOM,$parent,$docEntry,$text,$type)
{
    $label=$DOM->createElement("SPAN",$text);
    $label->setAttribute("type",$type);
    $label->setAttribute("EntryUUID",$docEntry->getUUID());
    $label->setAttribute(ATTR_ENTRY_TYPE,$docEntry->getType());
    
    $parent->appendChild($label);
    return $label;
}
function addSectionControls($DOM,$Elem,$docEntry)
{
    switch($docEntry->getText())
    {
        case SECTION_CHIEF_COMPLAINT:
        case SECTION_HPI:
        case SECTION_AP:
            createButton($DOM,$Elem,$docEntry,"Review","REVIEW");
            break;
        case SECTION_PAST_MEDICAL_HISTORY:
            createButton($DOM,$Elem,$docEntry,"Add",FUNC_DETAILS);
            createButton($DOM,$Elem,$docEntry,"Review","REVIEW");
            break;
        case SECTION_PROBLEM_LIST:
            createButton($DOM,$Elem,$docEntry,"Add Problem","ADDPROB");
            break;
        case SECTION_VITAL_SIGNS:
        case SECTION_PHYSICAL_EXAM:
        case SECTION_ALLERGIES:
        case SECTION_FAMILY_HISTORY:
        case SECTION_REVIEW_OF_SYSTEMS:
        case SECTION_SOCIAL_HISTORY:
            createButton($DOM,$Elem,$docEntry,"Details",FUNC_SHOWFORM);
            createButton($DOM,$Elem,$docEntry,"Review","REVIEW");
            break;
        case SECTION_MEDICATIONS:
            createButton($DOM,$Elem,$docEntry,"med",FUNC_MED);
            createButton($DOM,$Elem,$docEntry,"Review","REVIEW");
            break;
    }
}


function createTagElem($DOM,$docEntry,$tag,$text="")
{
    $retVal=$DOM->createElement($tag,$text);
    $retVal->setAttribute(ATTR_ENTRY_TYPE,$docEntry->getType());
    $retVal->setAttribute("CODE",$docEntry->getCode());
    $retVal->setAttribute("UUID",$docEntry->getUUID());
    $retVal->setIdAttribute("UUID",true);
    
    return $retVal;
}

function createElement($DOM,$parent,$docEntry,$docItem)
{
    

    $text=$docEntry->getText();
    switch($docEntry->getType())
    {
        case TYPE_SECTION:
            $newElem=createTagElem($DOM,$docEntry,"SECTION");
            
            // Determine if we should hide an empty section
            $parentItem=$docItem->getParent();
            if($parentItem!=null)
            {

                $parSectionText=$parentItem->getEntry()->getText();
                // Does this subsection have a redundant label (e.g. ROS/PE)
                if(strpos($text,$parSectionText)!== false)
                {
                    $text=substr($text,strlen($parSectionText)+1);
                    if(count($docItem->getItems())==0)
                    {
                        $newElem->setAttribute("hidden","true");
                    }
                }
            }

            // create the header section
            
            $header=$DOM->createElement("DIV");
            // Create the label for this section
            $label=$DOM->createElement("SPAN",htmlentities($text));
            $label->setAttribute("CLASS","LABEL");
            $header->appendChild($label);
            
            $newElem->appendChild($header);

            addSectionControls($DOM,$header,$docEntry);


            // Create a list for Problem lists
            if($docEntry->getText()==SECTION_PROBLEM_LIST)
            {
                $retVal=$DOM->createElement("OL"," ");
                $newElem->appendChild($retVal);
            }
            elseif($docEntry->getText()==SECTION_MEDICATIONS)
            {
                $retVal=$DOM->createElement("UL"," ");
                $newElem->appendChild($retVal);              
            }
            else        
            {
                $retVal=$newElem;
            }
            $parent->appendChild($newElem);

            break;
        case TYPE_PROBLEM:
            $newElem=createTagElem($DOM,$docEntry,"SPAN");
            $label=$DOM->createElement("SPAN",htmlentities($docEntry->getText()));
            $newElem->appendChild($label);
            createButton($DOM,$newElem,$docEntry,"del",FUNC_DELETE);
            createButton($DOM,$newElem,$docEntry,"details",FUNC_DETAILS);
            createButton($DOM,$newElem,$docEntry,"med",FUNC_MED);
            $retVal=$DOM->createElement("UL"," ");
            $newElem->appendChild($retVal);
            $parent->appendChild($newElem);
            break;
        case TYPE_MEDICATION_ENTRY:
            $newElem=createTagElem($DOM,$docEntry,"SPAN");
            $newElem->setAttribute("rxcui",$docEntry->getRXCUI());
            $newElem->setAttribute("rxaui",$docEntry->getRXAUI()->getRXAUI());
            
            $label=createLabel($DOM,$newElem,$docEntry,htmlentities($docEntry->getText()),"LABEL");
            createButton($DOM,$newElem,$docEntry,"del",FUNC_DELETE);
            createButton($DOM,$newElem,$docEntry,"sig",FUNC_SIG);
            $retVal=$newElem;
            $parent->appendChild($newElem);
            break;
        case TYPE_NARRATIVE:
            $narSpan=$DOM->createElement("SPAN");
            $narSpan->setAttribute("class","NarSpan");
            if(isset($GLOBALS['review']))
            {
                $newElem=createTagElem($DOM,$docEntry,"span",$docEntry->getText());
                $narSpan->appendChild($newElem);
                $parent->appendChild($narSpan);
                $retVal=$narSpan;
                break;
            }
            $newElem=createTagElem($DOM,$docEntry,"textarea",$docEntry->getText());
            if($docEntry->isLocked())
            {
                $newElem->setAttribute("disabled","true");
            }
//            $newElem->setAttribute("contenteditable","");
            $narSpan->appendChild($newElem);
            $retVal=$narSpan;
            if(is_null($docEntry->getMetadata()))
            {
                createButton($DOM,$narSpan,$docEntry,"del",FUNC_DELETE);
            }
            $parent->appendChild($narSpan);
            break;
        case TYPE_OBSERVATION:
        default:
            $newElem=createTagElem($DOM,$docEntry,"SPAN",htmlentities($docEntry->getText()));
            $retVal=$newElem;
            $parent->appendChild($newElem);
    }


    return array($newElem,$retVal);
        
}

function populateEditorDOM($DOM,$parent,$docItem,$depth)
{
    $docEntry = $docItem->getEntry();
    $vals = createElement($DOM, $parent, $docEntry,$docItem);
    $parentElem = $vals[1];
       if(($parentElem->tagName=="UL") || ($parentElem->tagName=="OL"))
        {
            foreach($docItem->getItems() as $childDI)
            {
                $LI=$DOM->createElement("LI");
                $parentElem->appendChild($LI);
                populateEditorDOM($DOM,$LI,$childDI,$depth+1);
            }
        }
        else
        {
            foreach($docItem->getItems() as $childDI)
            {
                populateEditorDOM($DOM,$parentElem,$childDI,$depth+1);
            }
        }
    return $vals[0];
}
?>
