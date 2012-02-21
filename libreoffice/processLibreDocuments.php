<?php

function generateXMLFromDocument($filename,$path)
{
    echo $path;
    $shell_cmd="sh " .$GLOBALS['doctrineroot']."/shell/io.sh". " ".$filename. " ".$path;
    $shell_cmd=escapeshellcmd($shell_cmd);
    exec($shell_cmd,$output,$ret_val);
    echo "\n".$ret_val."\n";
}
?>
