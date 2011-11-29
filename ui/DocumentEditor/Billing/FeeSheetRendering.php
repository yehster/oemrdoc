<?php
function FeeSheetRender($DOM,$parent,$OEMREnc)
{
    $table=$DOM->createElement("TABLE");
    $parent->appendChild($table);
    $tbody=$DOM->createElement("TBODY");
    $table->appendChild($tbody);
    $diags=array();
    foreach($OEMREnc->getBillingEntries() as $BE)
    {
        if(strpos($BE->getCode_type(),"ICD")!==false)
        {
            $diags[]=$BE->getCode();
        }
    }

    foreach($OEMREnc->getBillingEntries() as $BE)
    {
        if(strpos($BE->getCode_type(),"ICD")===false)
        {
            FeeSheetRenderLineItem($DOM,$tbody,$BE,$diags);
        }
    }
    
    return $table;
}

function RenderJustify($DOM,$parent,$justify,$diagnoses)
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
        $parent->appendChild($cbDiag);
    }
}

function FeeSheetRenderLineItem($DOM,$parent,$OEMRBE,$diags)
{
    
    $row=$DOM->createElement("tr");
    $row->setAttribute("class","billing");
    $row->setAttribute("billingID",$OEMRBE->getId());
    $parent->appendChild($row);
    $tdCode=$DOM->createElement("td",$OEMRBE->getCode());
    $row->appendChild($tdCode);
    if(($OEMRBE->getCode_type()=="CPT4") || ($OEMRBE->getCode_type()=="HCPCS"))
    {
        $strFee=sprintf("%.2f",$OEMRBE->getFee());
        $txtFee=$DOM->createElement("input");
        $txtFee->setAttribute("value",$strFee);
        $txtFee->setAttribute("type","text");
        
        $tdFee=$DOM->createElement("td");
        $tdFee->appendChild($txtFee);
        $row->appendChild($tdFee);
        $tdJustify=$DOM->createElement("td");
        RenderJustify($DOM,$tdJustify,$OEMRBE->getJustify(),$diags);
        $row->appendChild($tdJustify);

    }
    
    
    
    return $row;
}
?>
