<?php
include_once('/var/www/openemr/library/doctrine/ui/Editor/EditorConstants.php');

function CreateEditorElement($DOM,$DocEntry,$tag,$parent=null,$text=null)
{
    $retval = $DOM->createElement($tag,$text);
    $retval->setAttribute(ATTR_UUID,$DocEntry->getUUID());
    $retval->setAttribute(ATTR_ENTRY_TYPE,$DocEntry->getType());
    if($parent!=null)
    {
        $parent->appendChild($retval);
    }
    return $retval;
}

function addProblemListControls($DOM,$parent)
{
    $DIV=$DOM->createElement("DIV");
    $parent->appendChild($DIV);
    $label=$DOM->createElement("TEXT","Add Problem:");
    $DIV->appendChild($label);

    $input=$DOM->createElement("INPUT");
    $input->setAttribute("TYPE","TEXT");
    $input->setAttribute("ID","txtNewProblem");

    $DIV->appendChild($input);
}

function addButton($DOM,$DocEntry,$parent,$val)
{
    $Button=CreateEditorElement($DOM,$DocEntry,"INPUT",$parent);
    $Button->setAttribute("TYPE","BUTTON");
    $Button->setAttribute("value",$val);
    
    return $Button;
}

function generateEditorDOM($DOM,$Parent,$DocItem,$Depth)
{
    $DocEntry=$DocItem->getEntry();
    $text=htmlentities($DocEntry->getText());
    $hide=false;
    switch($DocEntry->getType())
    {
        case TYPE_SECTION:
            $parentItem=$DocItem->getParent();
            if($parentItem!=null)
            {
                $parSectionText=$parentItem->getEntry()->getText();
                if(strpos($text,$parSectionText)!== false)
                {
                    $text=substr($text,strlen($parSectionText)+1);
                    if(count($DocItem->getItems())==0)
                    {
                        $hide=true;
                    }
                }
            }
            $sectionDIV=CreateEditorElement($DOM,$DocEntry,"DIV",$Parent);
            $sectionDIV->setAttribute("ID",$DocEntry->getUUID());
            if($hide)
            {
                $sectionDIV->setAttribute(ATTR_CLASS,CLASS_HIDDEN);
            }
            $sectionHeader=CreateEditorElement($DOM,$DocEntry,"TEXT",$sectionDIV,$text);
            if(($text==SECTION_PHYSICAL_EXAM) || ($text==SECTION_REVIEW_OF_SYSTEMS))
            {
                $button=CreateEditorElement($DOM,$DocEntry,"INPUT",$sectionDIV);
                $button->setAttribute("type","BUTTON");
                $button->setAttribute("value","details");
            }
            $firstSPAN=CreateEditorElement($DOM,$DocEntry,"SPAN",$sectionDIV);
            $firstSPAN->appendChild($DOM->createElement("BR"));

            $secondSPAN=CreateEditorElement($DOM,$DocEntry,"SPAN",$sectionDIV," ");
            $nextParent=$firstSPAN;

            if($text==SECTION_PROBLEM_LIST)
            {
                $olProblems=CreateEditorElement($DOM,$DocEntry,"ol",$firstSPAN);
                $thirdSPAN=CreateEditorElement($DOM,$DocEntry,"SPAN",$sectionDIV," ");
                addProblemListControls($DOM,$thirdSPAN);
            }
            $retVal=$sectionDIV;
            break;
        case TYPE_NARRATIVE:
            $div=CreateEditorElement($DOM,$DocEntry,"DIV",$Parent);
            $div->setAttribute("ID",$DocEntry->getUUID());
            $textArea=CreateEditorElement($DOM,$DocEntry,"TEXTAREA",$div,$text);
            $textArea->setAttribute("rows",1);
            $textArea->setAttribute("cols",80);
            $nextParent=$div;
            if($Depth>2)
            {
                addButton($DOM,$DocEntry,$div,"del");
            }
            break;
        case TYPE_OBSERVATION:
            $nextParent=CreateEditorElement($DOM,$DocEntry,"SPAN",$Parent,"[".$text."]");
            break;
        case TYPE_PROBLEM:
            $div=CreateEditorElement($DOM,$DocEntry,"DIV",$Parent);
            $retVal=$div;
            $text=CreateEditorElement($DOM,$DocEntry,"TEXT",$div,$text);
            $medButton=addButton($DOM,$DocEntry,$div,"med");
            $detailsButton=addButton($DOM,$DocEntry,$div,"details");
            
            $deleteButton = addButton($DOM,$DocEntry,$div,"del");

            $problemInfo=CreateEditorElement($DOM,$DocEntry,"ul",$div);
            $div->setAttribute("ID",$DocEntry->getUUID());
            $nextParent=$div;
            break;
        case TYPE_MEDICATION_ENTRY:
            $nextParent=CreateEditorElement($DOM,$DocEntry,"TEXT",$Parent,$text);
            $deleteButton = addButton($DOM,$DocEntry,$Parent,"del");
            break;
        default:
            $nextParent=CreateEditorElement($DOM,$DocEntry,"TEXT",$Parent,$text);
    }

    //recurse the document tree
    foreach($DocItem->getItems() as $childItem)
    {
        $childType=$childItem->getEntry()->getType();
        if((($childType==TYPE_NARRATIVE) || ($childType==TYPE_SECTION)) && ($DocEntry->getType()==TYPE_SECTION))
        {
            generateEditorDOM($DOM,$secondSPAN,$childItem,$Depth+1);
        }
        elseif($childType==TYPE_PROBLEM)
        {
            $li=$DOM->createElement("LI");
            $olProblems->appendChild($li);
            generateEditorDOM($DOM,$li,$childItem,$Depth+1);
        }
        elseif($DocEntry->getType()==TYPE_PROBLEM)
        {
            $li=$DOM->createElement("LI");
            $problemInfo->appendChild($li);
            generateEditorDOM($DOM,$li,$childItem,$Depth+1);
        }
        else
        {
            generateEditorDOM($DOM,$nextParent,$childItem,$Depth+1);
        }
    }
    return $retVal;
}

?>
