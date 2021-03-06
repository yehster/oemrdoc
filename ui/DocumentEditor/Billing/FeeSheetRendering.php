<?php
$FeeSheetHeaders=array("Code","Mod","Fee","Justify");
        
function FeeSheetRender($DOM,$parent,$OEMREnc)
{
    $table=$DOM->createElement("TABLE");
    $parent->appendChild($table);
    $tbody=$DOM->createElement("TBODY");
    $table->appendChild($tbody);
    $diags=array();
    
    $trHeader=$DOM->createElement("tr");
    $tbody->appendChild($trHeader);

    foreach($GLOBALS['FeeSheetHeaders'] as $head)
    {
        $td=$DOM->createElement("th",$head);
        $trHeader->appendChild($td);
    }
    foreach($OEMREnc->getBillingEntries() as $BE)
    {
        if(strpos($BE->getCode_type(),"ICD")!==false)
        {
            $diags[]=$BE->getCode();
        }
    }

    foreach($OEMREnc->getBillingEntries() as $BE)
    {
//        if(strpos($BE->getCode_type(),"ICD")===false)
        {
            FeeSheetRenderLineItem($DOM,$tbody,$BE,$diags);
        }
    }
    
    return $table;
}

function RenderJustify($DOM,$parent,$justify,$diagnoses,$billed)
{
    foreach($diagnoses as $diag)
    {
        $lblDiag=$DOM->createElement("span",$diag);
        $parent->appendChild($lblDiag);
        $cbDiag=$DOM->createElement("input");
        $cbDiag->setAttribute("value",$diag);
        $cbDiag->setAttribute("type","checkbox");
        if(strpos($justify,$diag)!==false)
        {
            $cbDiag->setAttribute("checked","true");
        }
        if($billed)
        {
            $cbDiag->setAttribute("disabled","true");
        }
        
        $parent->appendChild($cbDiag);
    }
}

function FeeSheetRenderLineItem($DOM,$parent,$OEMRBE,$diags)
{
    
    $billed=$OEMRBE->getBilled()>0;
    
    $row=$DOM->createElement("tr");
    $row->setAttribute("class","billing");
    $row->setAttribute("billingID",$OEMRBE->getId());
    $parent->appendChild($row);
    $tdCode=$DOM->createElement("td",$OEMRBE->getCode());
    $row->appendChild($tdCode);
    
    $txtMod=$DOM->createElement("input");
    $txtMod->setAttribute("type","text");
    $txtMod->setAttribute("class","mod");
    $txtMod->setAttribute("value",$OEMRBE->getModifier());
    if($billed)
    {
        $txtMod->setAttribute("disabled","true");
    }
    
    $tdMod=$DOM->createElement("td");
    $tdMod->appendChild($txtMod);
    $row->appendChild($tdMod);
    
        if(($OEMRBE->getCode_type()=="CPT4") || ($OEMRBE->getCode_type()=="HCPCS"))
    {
        $strFee=sprintf("%.2f",$OEMRBE->getFee());
        $txtFee=$DOM->createElement("input");
        $txtFee->setAttribute("value",$strFee);
        $txtFee->setAttribute("type","text");
        $txtFee->setAttribute("class","fee");
        if($billed)
        {
            $txtFee->setAttribute("disabled","true");
        }        
        $tdFee=$DOM->createElement("td");
        $tdFee->appendChild($txtFee);
        $row->appendChild($tdFee);
        $tdJustify=$DOM->createElement("td");
        RenderJustify($DOM,$tdJustify,$OEMRBE->getJustify(),$diags,$billed);
        $row->appendChild($tdJustify);

    }
    
    
    
    return $row;
}

function createInsuranceInfo($DOM,$parent,$pat)
{
    $id=$pat->getInsurance_data();
    $info=$DOM->createElement("SPAN",count($id));
    $sel=$DOM->createElement("SELECT");
    $sel->setAttribute("class","insuranceChoice");
    foreach ($id as $insurance)
    {
        if($insurance->getProvider()!=null)
        {
            $opt=$DOM->createElement("option",$insurance->getPlan_name());
            $opt->setAttribute("value",$insurance->getProvider());
            $sel->appendChild($opt);
        }
    }
    $parent->appendChild($sel);
}
?>
