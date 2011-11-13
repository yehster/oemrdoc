<?php
include_once("DocumentEditorUtilities.php");

function refreshSection($item)
{
        $docEntryDOM =  new DOMDocument("1.0","utf-8");
        $span=$docEntryDOM->createElement("SPAN");
        $DOMNode= populateEditorDOM($docEntryDOM,$span,$item,2);
        return $docEntryDOM->saveXML($DOMNode);

}
?>
