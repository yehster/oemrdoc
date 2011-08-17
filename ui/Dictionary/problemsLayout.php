<?php
function addCodeResult($DOM,$tbody,$code)
{
       $newRow=$DOM->createElement("TR");
    $newRow->setAttribute("ID",$code->getId());

    $tbody->appendChild($newRow);

    $codeTextTD = $DOM->createElement("TD",$code->getCodeText());
    $codeTextTD->setAttribute("type","CODETEXT");
    $codeTextTD->setAttribute("codetype",$code->getCode_type());
    $codeTextTD->setAttribute("code",$code->getCode());
    
    
    $newRow->appendChild($codeTextTD);

    $codeTD = $DOM->createElement("TD",$code->getCode());
    $codeTD->setAttribute("class","CODE");
    $codeTD->setAttribute("type","CODE");

    $newRow->appendChild($codeTD);
}
?>
