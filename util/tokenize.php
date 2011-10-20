<?php
function tokenize($str,$toks=" ")
{
    
    $idx=0;
    $tok = strtok($str,$toks);
    $tokens=array();
    while($tok!==false)
    {
        $tokens[$idx]=$tok;
        $idx++;
        $tok=strtok(" ");
    }
    return $tokens;
}
?>
