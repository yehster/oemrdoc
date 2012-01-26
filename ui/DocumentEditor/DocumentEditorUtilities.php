<?php
include_once("$doctrineroot/common/EditorConstants.php");
require_once("$doctrineroot/ui/DocumentEditor/EntryStatus/StatusUtils.php");
require_once("$doctrineroot/ui/DocumentEditor/AllergyInfo/AllergyInfo.php");
require_once("$doctrineroot/ui/DocumentEditor/ImageEntry/ImageEntryInfo.php");

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
function createText($DOM,$Elem,$docEntry,$func)
{
    if($docEntry->isLocked())
    {
        return;
    }
    if(isset($GLOBALS['review']))
    {
        return;
    }
    $txt=$DOM->createElement("INPUT");
    $txt->setAttribute("type","text");
    $txt->setAttribute("FUNC",$func);
    $txt->setAttribute("EntryUUID",$docEntry->getUUID());
    $txt->setAttribute(ATTR_ENTRY_TYPE,$docEntry->getType());
    $Elem->appendChild($txt);
    return $txt;
    
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
    $span=$DOM->createElement("SPAN");
    $span->setAttribute("FUNC","controlGroup");
    $Elem->appendChild($span);
    
    switch($docEntry->getText())
    {
        case SECTION_CHIEF_COMPLAINT:
        case SECTION_HPI:
        case SECTION_AP:
            createButton($DOM,$span,$docEntry,"Review","REVIEW");
            break;
        case SECTION_PAST_MEDICAL_HISTORY:
            createButton($DOM,$span,$docEntry,"Add",FUNC_DETAILS);
            createButton($DOM,$span,$docEntry,"Review","REVIEW");
            break;
        case SECTION_PROBLEM_LIST:
        case SECTION_IMPRESSION:
            createText($DOM,$span,$docEntry,"ADDPROB");
            createButton($DOM,$span,$docEntry,"Add Problem" ,"ADDPROB");

            break;
        case SECTION_ALLERGIES:
            createButton($DOM,$span,$docEntry,"Details",FUNC_SHOWFORM);
            createButton($DOM,$span,$docEntry,"Review","REVIEW");
            break;
        case SECTION_VITAL_SIGNS:
        case SECTION_PHYSICAL_EXAM:
            createButton($DOM,$span,$docEntry,"Image",FUNC_ADD_IMAGE);
        case SECTION_FAMILY_HISTORY:
        case SECTION_REVIEW_OF_SYSTEMS:
        case SECTION_SOCIAL_HISTORY:
            createButton($DOM,$span,$docEntry,"Details",FUNC_SHOWFORM);
            createButton($DOM,$span,$docEntry,"Review","REVIEW");
            createButton($DOM,$span,$docEntry,"comment",FUNC_DETAILS);            
            break;
        case SECTION_DRUG_ALLERGIES:
        case SECTION_FOOD_ALLERGIES:
            createButton($DOM,$span,$docEntry,"Add",FUNC_DETAILS);
            break;
        case SECTION_MEDICATIONS:
            createButton($DOM,$span,$docEntry,"med",FUNC_MED);
            createButton($DOM,$span,$docEntry,"Review","REVIEW");
            createButton($DOM,$span,$docEntry,"Comment",FUNC_DETAILS);
            break;
        case SECTION_BILLING:
            break;
        case SECTION_STUDIES:
            createButton($DOM,$span,$docEntry,"Add",FUNC_DETAILS);
            createButton($DOM,$span,$docEntry,"Review","REVIEW");
            break;
    }
}

function medSigText($DOM,$docEntry,$parent,$func,$value) 
{
    $retval=$DOM->createElement("INPUT");
    $retval->setAttribute("TYPE","TEXT");
    $retval->setAttribute("EntryUUID",$docEntry->getUUID());
    $retval->setAttribute("value",$value);
    $retval->setAttribute("func",$func);
    $parent->appendChild($retval);
    
    return $retval;
}

function createMedSigEntry($DOM,$docEntry,$parent)
{
    if($docEntry->isLocked())
    {
        $retVal=createTagElem($DOM,$docEntry,"DIV",$docEntry->getText());
    }
    else
    {
        $retVal=createTagElem($DOM,$docEntry,"DIV");
        medSigText($DOM,$docEntry,$retVal,"qty",$docEntry->getQuantity());
        
        medSigText($DOM,$docEntry,$retVal,"units",$docEntry->getUnits());
        medSigText($DOM,$docEntry,$retVal,"route",$docEntry->getRoute());
        
        medSigText($DOM,$docEntry,$retVal,"schedule",$docEntry->getSchedule());
        $sigInfoSelector=$DOM->createElement("DIV");
        $sigInfoSelector->setAttribute("class","sigInfoSelector");
        $retVal->appendChild($sigInfoSelector);
    }
    $parent->appendChild($retVal);    
    return $retVal;
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
            $newElem->setAttribute("name",$docEntry->getText());
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
            if(($docEntry->getText()==SECTION_PROBLEM_LIST) || ($docEntry->getText()==SECTION_IMPRESSION))
            {
                $retVal=$DOM->createElement("OL"," ");
                $newElem->appendChild($retVal);
            }
            elseif($docEntry->getText()==SECTION_MEDICATIONS)
            {
                if(!$docEntry->isLocked())
                {
                    $script=$DOM->createElement("SCRIPT","updateExistingMedDisplay();");
                    $existingDisplay=$DOM->createElement("SECTION"," ");
                    $existingDisplay->setAttribute("class","existingMeds");
                    $existingDisplay->setAttribute("sectionUUID",$docEntry->getUUID());
                    $newElem->appendChild($existingDisplay);
                    $newElem->appendChild($script);
                }
                $retVal=$DOM->createElement("UL"," ");
                $retVal->setAttribute("id","medicationsList");
                $newElem->appendChild($retVal);              
            }
            elseif (isAllergySection($docEntry))
            {
                if(!$docEntry->isLocked())
                {
                    createAllergyReview($DOM,$newElem,$docEntry);
                }
                $retVal=$newElem;
            }
            else
            {
                $retVal=$newElem;
            }
            $parent->appendChild($newElem);

            break;
        case TYPE_PROBLEM:
            $newElem=createTagElem($DOM,$docEntry,"SPAN");
            $info=htmlentities($docEntry->getText());
            if($docEntry->getCode()!=null)
            {
                $info.="(".$docEntry->getCode().")";
            }
            $label=$DOM->createElement("SPAN",$info);
            $newElem->appendChild($label);
            createButton($DOM,$newElem,$docEntry,"del",FUNC_DELETE);
            createButton($DOM,$newElem,$docEntry,"details",FUNC_DETAILS);
            createButton($DOM,$newElem,$docEntry,"med",FUNC_MED);
            createButton($DOM,$newElem,$docEntry,"...",FUNC_MENU);
            $retVal=$DOM->createElement("UL"," ");
            $newElem->appendChild($retVal);
            $parent->appendChild($newElem);
            break;
        case TYPE_MEDICATION_ENTRY:
            $newElem=createTagElem($DOM,$docEntry,"SPAN");
            $newElem->setAttribute("rxcui",$docEntry->getRXCUI());
            if($docEntry->getRXAUI()!=null)
            {
                $newElem->setAttribute("rxaui",$docEntry->getRXAUI()->getRXAUI());
            }
            $label=createLabel($DOM,$newElem,$docEntry,htmlentities($docEntry->getText()),"LABEL");
            createButton($DOM,$newElem,$docEntry,"del",FUNC_DELETE);
            createButton($DOM,$newElem,$docEntry,"comment",FUNC_DETAILS);
            $retVal=$newElem;
            $parent->appendChild($newElem);
            break;
        case TYPE_MED_SIG:

            $retVal=createMedSigEntry($DOM,$docEntry,$parent);
            $newElem=$retVal;

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
            if($docEntry->isLocked())
            {
                            $newElem=createTagElem($DOM,$docEntry,"section",$docEntry->getText());
            }
            else
            {
                            $newElem=createTagElem($DOM,$docEntry,"textarea",$docEntry->getText());

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
        case TYPE_DOC_LINK:
            $newElem=createTagElem($DOM,$docEntry,"SPAN",htmlentities("LINKED ENTRY"));
            $retVal=$newElem;
            if($docEntry->getLinkedEntry()!=null)
            {
                // TO DO: Need to recurse to children of linked Entries
                $linkedElem=createElement($DOM,$retVal,$docEntry->getLinkedEntry(),$docEntry->getItem());
                $parent->appendChild($newElem);
            }
            else {
                // Skip empty link and clean up orphans.
                $GLOBALS['em']->remove($docEntry);
                $GLOBALS['em']->flush();
                
            }
            break;
        case TYPE_IMAGE_ENTRY:
            $retVal=createImageEntry($DOM,$parent,$docEntry);
            $newElem=$retVal;
            break;
        case TYPE_OBSERVATION:
        default:
            $newElem=createTagElem($DOM,$docEntry,"SPAN",htmlentities($docEntry->getText()));
            $retVal=$newElem;
            $parent->appendChild($newElem);
    }

    $sh=$docEntry->getStatusHistory();
    if(count($sh)>0)
    {
        createStatusControls($DOM,$docEntry,$newElem,$sh[0]);
    }
    return array($newElem,$retVal);
        
}

function populateEditorDOM($DOM,$parent,$docItem,$depth)
{
    $docEntry = $docItem->getEntry();
    $vals = createElement($DOM, $parent, $docEntry,$docItem);
    if($docEntry->getType()==TYPE_DOC_LINK)
    {
        $docItem=$docEntry->getLinkedEntry()->getItem();
    }
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
