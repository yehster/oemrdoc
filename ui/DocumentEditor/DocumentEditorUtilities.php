<?php
include_once('/var/www/openemr/library/doctrine/ui/Editor/EditorConstants.php');
function createElement($DOM,$parent,$docEntry,$docItem)
{
    

    $text=$docEntry->getText();
    switch($docEntry->getType())
    {
        case TYPE_SECTION:
            $newElem=$DOM->createElement("SECTION");

            
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
            break;
        case TYPE_PROBLEM:
            $newElem=$DOM->createElement("SPAN",htmlentities($docEntry->getText()));
            $retVal=$DOM->createElement("UL");
            $newElem->appendChild($retVal);
            break;
        case TYPE_NARRATIVE:
            $newElem=$DOM->createElement("TEXTAREA",$docEntry->getText());
            $retVal=$newElem;
            break;
        case TYPE_OBSERVATION:
        default:
            $newElem=$DOM->createElement("SPAN",htmlentities($docEntry->getText()));
            $retVal=$newElem;
    }
    $newElem->setAttribute(ATTR_ENTRY_TYPE,$docEntry->getType());
    $newElem->setAttribute("CODE",$docEntry->getCode());
    $newElem->setAttribute("UUID",$docEntry->getUUID());
    
    if(($parent->tagName=="UL") || ($parent->tagName=="OL"))
    {
        $newLI=$DOM->createElement("LI");
        $newLI->appendChild($newElem);
        $parent->appendChild($newLI);
    }
    else
    {
        $parent->appendChild($newElem);
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
