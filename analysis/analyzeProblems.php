<?php
require_once("../init-em.php");

function recurseEntries($entries,$item)
{
    $entries[]=$item->getEntry();
    foreach($item->getItems() as $it)
    {
        recurseEntries($entries,$it);
    }
}


function scanDocument($doc)
{
    $entries = array();
    foreach($doc->getItems() as $item)
    {
        recurseEntries($entries,$item);    
    }
}
?>
