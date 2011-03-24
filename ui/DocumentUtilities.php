<?php
function generateDOM($DOMRoot,$DocItem,$parentDOMElem=null,$parentItem=null)
{
    $DocEntry = $DocItem->getEntry();
    $parSectionText=null;
    $ProblemListSection=null;

    if($parentItem!==null)
    {
        $parentEntry = $parentItem->getEntry();
        if($parentEntry->getType()=="Section")
        {
            $parSectionText = $parentEntry->getText();
        }
    }
    if($DocEntry->getType()=="Narrative")
    {
        $textArea=$DOMRoot->createElement("TEXTAREA",htmlentities($DocEntry->getText()));
        $div=$DOMRoot->createElement("DIV");
        $div->appendChild($textArea);
        $DOMElem=$div;
    }
    elseif ($DocEntry->getType()=="Section")
    {
            if( ($parSectionText!==null) && (strpos($DocEntry->getText(),$parSectionText)!== false))
            {
                $sectionText=substr($DocEntry->getText(),strlen($parSectionText)+1);
            }
            else
            {
                $sectionText=$DocEntry->getText();
            }


        $DOMElem=$DOMRoot->createElement("DIV",htmlentities($sectionText));
        $DOMElem->setAttribute("NAME",htmlentities($DocEntry->getText()));
    }
    elseif ($DocEntry->getType()=="Problem")
    {
        $DOMElem=$DOMRoot->createElement("li",htmlentities($DocEntry->getText()));
    }
    elseif ($DocEntry->getType()=="MedicationEntry")
    {
        $DOMElem=$DOMRoot->createElement("DIV",htmlentities($DocEntry->getText()));
    }
    elseif ($DocEntry->getType()=="Observation")
    {
        $DOMElem=$DOMRoot->createElement("DIV",htmlentities($DocEntry->getText())." ".$DocEntry->getValue()."|");
    }

    $DOMElem->setAttribute("CLASS",$DocEntry->getType());
    $DOMElem->setAttribute("ID",$DocEntry->getUUID());

    if ($DocEntry->getType()=="Section")
    {
        if($DocEntry->getText()=="Physical Exam")
        {
            $fullClass = $DOMElem->getAttribute("CLASS")." PE";
            $DOMElem->setAttribute("CLASS",$fullClass);

        }
        elseif($DocEntry->getText()=="Review of Systems")
        {
            $fullClass = $DOMElem->getAttribute("CLASS")." ROS";
            $DOMElem->setAttribute("CLASS",$fullClass);

        }

    }


    // connect the current new element to its parent
    if($parentDOMElem==null)
    {
        $DOMRoot->appendChild($DOMElem);
    }
    else
    {
        $parentDOMElem->appendChild($DOMElem);
    }

    if (($DocEntry->getText()=="Problem List") && $DocEntry->getType()=="Section")
    {
            $ProblemListSection = $DOMElem;
            $ListElem = $DOMRoot->createElement("OL");
            $ListElem->setAttribute("NAME","Problem List");
            $ListElem->appendChild($DOMRoot->createTextNode(""));
            $DOMElem->appendChild($ListElem);
            addProblemSelector($DOMRoot,$DOMElem);
            addMedicationSelection($DOMRoot,$DOMElem);
            $DOMElem=$ListElem;
     }
    // recurse the child items.
    foreach($DocItem->getItems() as $childItem)
    {
        generateDOM($DOMRoot,$childItem,$DOMElem,$DocItem);
    }

    if ($DocEntry->getType()=="Problem")
     {
                 addProblemInfoControls($DOMRoot, $DOMElem);
     }
     return $DOMElem;
}

function addProblemSelector(DOMDocument $DOM,$ProblemListNode)
{

        $ProblemAdd = $DOM->createElement("DIV");
        $ProblemAdd->setAttribute("name","addProblem");

        $ProblemListNode->appendChild($ProblemAdd);

        $addProblemLabel=$DOM->createElement("SPAN","Add Problem:");
        $addProblemLabel->setAttribute("TYPE","LABEL");


        $addProblemText=$DOM->createElement("INPUT");
        $addProblemText->setAttribute("TYPE","text");
        $addProblemText->setAttribute("ID","txtNewProblem");
        $addProblemText->setAttribute("CLASS","problemItem");

        $probSuggestions=$DOM->createElement("DIV");
        $probSuggestions->setAttribute("ID","divProblemSuggestions");

        $ProblemAdd->appendChild($addProblemLabel);
        $ProblemAdd->appendChild($addProblemText);
        $ProblemAdd->appendChild($probSuggestions);


}

function addMedicationSelection(DOMDocument $DOM,$section)
{
    $divMedSel=$DOM->createElement("DIV");
    $section->appendChild($divMedSel);
    $divMedSel->setAttribute("ID", "divMedSuggestion");

}

function addProblemInfoControls(DOMDocument $DOM, $ProblemNode)
{
    $divProblemDetails = $DOM->createElement("DIV");
    $divProblemDetails->setAttribute("CLASS","problemDetails");

    $ProblemNode->appendChild($divProblemDetails);

   $selProblemType=$DOM->createElement("SELECT");
    $divProblemDetails->appendChild($selProblemType);

    $optMed=$DOM->createElement("OPTION","Medication");
    $selProblemType->appendChild($optMed);

    $optStudy=$DOM->createElement("OPTION","Study");
    $selProblemType->appendChild($optStudy);

    $optNarrative=$DOM->createElement("OPTION","Narrative");
    $selProblemType->appendChild($optNarrative);



    $txtProblemDetailsEntry=$DOM->createElement("TEXTAREA");
    $txtProblemDetailsEntry->appendChild($DOM->createTextNode(""));
    $txtProblemDetailsEntry->setAttribute("NAME",$ProblemNode->getAttribute("ID"));
    $txtProblemDetailsEntry->setAttribute("CLASS","ProblemEntry");



    $divProblemDetails->appendChild($txtProblemDetailsEntry);
 
}
function findDiv(DOMDocument $DOM,$name)
{
    $elems = $DOM->getElementsByTagName("DIV");
    foreach($elems as $element)
    {
        if($element->getAttribute("NAME")==$name)
        {
            return $element;
        }
    }
    return null;
}


?>
