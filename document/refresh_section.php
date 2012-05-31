<?php
include_once("/var/www/openemr/library/doctrine/document/utilities.php");

function refreshSection($item,$depth=2)
{
        $docEntryDOM =  new DOMDocument("1.0","utf-8");
        $span=$docEntryDOM->createElement("SPAN");
        $DOMNode= populateEditorDOM($docEntryDOM,$span,$item,$depth);
        $stuff=$docEntryDOM->saveHTML($span->firstChild);
        return $stuff;

}
?>