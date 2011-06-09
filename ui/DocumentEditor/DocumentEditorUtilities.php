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

function tagElement($elem,$docEntry)
{
    $elem->setAttribute(ATTR_ENTRY_TYPE,$docEntry->getType());
    $elem->setAttribute("CODE",$docEntry->getCode());
    $elem->setAttribute("UUID",$docEntry->getUUID());
}

function addToParentOrList($DOM,$parent,$elem)
{
        
    if(($parent->tagName=="UL") || ($parent->tagName=="OL"))
    {
        $newLI=$DOM->createElement("LI");
        $newLI->appendChild($elem);
        $parent->appendChild($newLI);
    }
    else
    {
        $parent->appendChild($elem);
    }
}

function createElement($DOM,$parent,$docEntry,$docItem)
{
    

    $text=$docEntry->getText();
    switch($docEntry->getType())
    {
        case TYPE_SECTION:
            $newElem=$DOM->createElement("SECTION");
            tagElement($newElem,$docEntry);
            
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
            addToParentOrList($DOM,$parent,$newElem);

            break;
        case TYPE_PROBLEM:
            $newElem=$DOM->createElement("SPAN",htmlentities($docEntry->getText()));
            tagElement($newElem,$docEntry);
            $retVal=$DOM->createElement("UL");
            $newElem->appendChild($retVal);
            addToParentOrList($DOM,$parent,$newElem);
            break;
        case TYPE_NARRATIVE:
            $narSpan=$DOM->createElement("SPAN");
            $newElem=$DOM->createElement("TEXTAREA",$docEntry->getText());
            tagElement($newElem,$docEntry);
            $narSpan->appendChild($newElem);
            $retVal=$narSpan;
            if(is_null($docEntry->getMetadata()))
            {
                createButton($DOM,$narSpan,$docEntry,"DEL",FUNC_DELETE);
            }
            addToParentOrList($DOM,$parent,$narSpan);
            break;
        case TYPE_OBSERVATION:
        default:
            $newElem=$DOM->createElement("SPAN",htmlentities($docEntry->getText()));
            tagElement($newElem,$docEntry);
            $retVal=$newElem;
            addToParentOrList($DOM,$parent,$newElem);
    }


    return $retVal;
        
}

function populateEditorDOM($DOM,$parent,$docItem,$depth)
{
    $docEntry = $docItem->getEntry();
    $parentElem = createElement($DOM, $parent, $docEntry,$docItem);

    foreach($docItem->getItems() as $docItem)
    {
        populateEditorDOM($DOM,$parentElem,$docItem,$depth+1);
    }    
        
}
?>
