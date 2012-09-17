<?php
namespace DOMUtilities;
function addScript(\DOMDocument $DOM,\DOMElement $parent,$scriptName)
{
    $retval =$DOM->createElement("script");
    $retval->setAttribute("src",$scriptName);
    $parent->appendChild($retval);
    return $retval;
}

function addStyle(\DOMDocument $DOM, \DOMElement $parent,$sheet)
{
    

    $retval=$DOM->createElement("style","@import url('".$sheet."');");
    $retval->setAttribute("type","text/css");
    $retval->setAttribute("media","all");
    $parent->appendChild($retval);
    return $retval;
}

function addElement(\DOMDocument $DOM, \DOMElement $parent,$tag,$content=null,array $attributes=null)
{
    $retval=$DOM->createElement($tag,$content);
    $parent->appendChild($retval);
    if(!empty($attributes))
    {
        foreach($attributes as $key=>$value)
        {
            $retval->setAttribute($key,$value);
        }
    }
    return $retval;
}
?>
