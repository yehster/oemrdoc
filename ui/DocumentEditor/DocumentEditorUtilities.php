<?php
include_once('/var/www/openemr/library/doctrine/ui/Editor/EditorConstants.php');
function createButton($DOM,$Elem,$docEntry,$text,$func)
{
    $but=$DOM->createElement("BUTTON",$text);
    $but->setAttribute("FUNC",$func);
    $but->setAttribute("EntryUUID",$docEntry->getUUID());
    $but->setAttribute(ATTR_ENTRY_TYPE,$docEntry->getType());
    $Elem->appendCHild($but);
    return $but;
            
}
function addSectionControls($DOM,$Elem,$docEntry)
{
    
}


function createTagElem($DOM,$docEntry,$tag,$text="")
{
    $retVal=$DOM->createElement($tag,$text);
    $retVal->setAttribute(ATTR_ENTRY_TYPE,$docEntry->getType());
    $retVal->setAttribute("CODE",$docEntry->getCode());
    $retVal->setAttribute("UUID",$docEntry->getUUID());
    
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

            // Create the label for this section
            $label=$DOM->createElement("DIV",htmlentities($text));
            $label->setAttribute("CLASS","LABEL");
            $newElem->appendChild($label);

            addSectionControls($DOM,$newElem,$docEntry);


            // Create a list for Problem lists
            if($docEntry->getText()==SECTION_PROBLEM_LIST)
            {
                $retVal=$DOM->createElement("OL");
                $newElem->appendChild($retVal);
            }
            else
            {
                $retVal=$newElem;
            }
            $parent->appendChild($newElem);

            break;
        case TYPE_PROBLEM:
            $newElem=createTagElem($DOM,$docEntry,"SPAN",htmlentities($docEntry->getText()));
            createButton($DOM,$newElem,$docEntry,"del",FUNC_DELETE);
            $retVal=$DOM->createElement("UL");
            $newElem->appendChild($retVal);
            $parent->appendChild($newElem);
            break;
        case TYPE_MEDICATION_ENTRY:
            $newElem=createTagElem($DOM,$docEntry,"SPAN",htmlentities($docEntry->getText()));
            createButton($DOM,$newElem,$docEntry,"del",FUNC_DELETE);
            $retVal=$newElem;
            $parent->appendChild($newElem);
            break;
        case TYPE_NARRATIVE:
            $narSpan=$DOM->createElement("SPAN");
            $newElem=createTagElem($DOM,$docEntry,"TEXTAREA",$docEntry->getText());
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


    return $retVal;
        
}

function populateEditorDOM($DOM,$parent,$docItem,$depth)
{
    $docEntry = $docItem->getEntry();
    $parentElem = createElement($DOM, $parent, $docEntry,$docItem);
        if(($parentElem->tagName=="UL") || ($parentElem->tagName=="OL"))
        {
            foreach($docItem->getItems() as $docItem)
            {
                $LI=$DOM->createElement("LI");
                $parentElem->appendChild($LI);
                populateEditorDOM($DOM,$LI,$docItem,$depth+1);
            }              
        }
        else
        {
            foreach($docItem->getItems() as $docItem)
            {
                populateEditorDOM($DOM,$parentElem,$docItem,$depth+1);
            }   
        }
        
}
?>
