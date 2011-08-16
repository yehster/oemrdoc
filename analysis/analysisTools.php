<?php

function recurseEntries($entries,$item,$type=null)
{
    if(($type==null) || ($type==$item->getEntry()->getType()))
    {
        $entries[]=$item->getEntry();    
    }
    foreach($item->getItems() as $it)
    {
        recurseEntries($entries,$it,$type);
    }
}


function scanDocument($doc,$type=null)
{
    $entries = array();
    foreach($doc->getItems() as $item)
    {
        recurseEntries($entries,$item,$type);    
    }
    return $entries;
}
?>
