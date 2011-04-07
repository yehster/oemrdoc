<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function getSectionOptions($em,$docEntry)
{
    $c=$docEntry->getCode();
    $ct=$docEntry->getCode_type();
   $qb = $em->createQueryBuilder()
        ->select("opt")
        ->from("library\doctrine\Entities\Option","opt")
        ->where("opt.target_code=:tc")
        ->andWhere("opt.target_code_type=:tt")
        ->orderBy("opt.seq","ASC");
    $qb->setParameter("tc",$c);
    $qb->setParameter("tt",$ct);
    $qry=$qb->getQuery();
    return $qry->getResult();
}

function createHeader($DOM,$parentElem,$docEntry)
{
    $headerRow=$DOM->createElement("TR");
    $parentElem->appendChild($headerRow);
    $thName=$DOM->createElement("TH",$docEntry->getText());
    $headerRow->appendChild($thName);
}

function createCheckBox($DOM,$parent,$option)
{
    $optionRow=$DOM->createElement("TR");
    $optionRow->setAttribute("class","OptionRow");
    $optionRow->setAttribute("code",$option->getSource_code());
    $optionRow->setAttribute("NomText",htmlentities($option->getText()));

    $parent->appendChild($optionRow);
    $optionRow->setAttribute("code",$option->getSource_code());
    $optionRow->setIdAttribute("code",true);

    $tdLabel=$DOM->createElement("TD",htmlentities($option->getText()));
    $optionRow->appendChild($tdLabel);

    $tdCheckBox=$DOM->createElement("TD");
    $optionRow->appendChild($tdCheckBox);

    $inpCheckBox=$DOM->createElement("INPUT");
    $tdCheckBox->appendChild($inpCheckBox);

    $inpCheckBox->setAttribute("TYPE","CHECKBOX");

}
function createOptionsListDOM($em, $DOM, $parentElem, $docEntry)
{
    if($docEntry->getType()==="Section")
    {

        $options=getSectionOptions($em,$docEntry);
        if(count($options)>0)
        {
            $divOptions=$DOM->createElement("DIV");
            $parentElem->appendChild($divOptions);

            $sectionTable=$DOM->createElement("TABLE");
            $sectionTBODY=$DOM->createElement("TBODY");
            $sectionTBODY->setAttribute("sectionUUID",$docEntry->getUUID());
            $divOptions->appendChild($sectionTable);
            $sectionTable->appendChild($sectionTBODY);
            createHeader($DOM,$sectionTBODY,$docEntry);
            for($optionIdx=0;$optionIdx<count($options);$optionIdx++)
            {
                $curOpt=$options[$optionIdx];
                createCheckBox($DOM,$sectionTBODY,$curOpt);
            }

            $parentElem=$divOptions;
        }
    }

    if($docEntry->getType()=="Nominative")
    {
        $obsRow=$DOM->getElementById($docEntry->getvocabID());
        if($obsRow!==null)
        {
            $obsRow->setAttribute("nominativeUUID",$docEntry->getUUID());
            $inputs=$obsRow->getElementsByTagName("INPUT");
            for($inpIdx=0;$inpIdx<$inputs->length;$inpIdx++)
            {
                $inp=$inputs->item($inpIdx);
                $inp->setAttribute("Checked","");
            }
        }
    }

    // iterate through the child elements
    $item=$docEntry->getItem();
    $childCount= $item->getItems()->count();
    if($childCount>0)
    {
        for($childIdx=0;$childIdx<$childCount;$childIdx++)
        {
            // modify this to spread sections into columns.
            $childItem = $item->getItems()->get($childIdx);
            $childEntry = $childItem->getEntry();
            createOptionsListDOM($em,$DOM,$parentElem,$childEntry);
        }
    }
}
?>
