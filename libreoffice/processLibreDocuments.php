<?php

function generateXMLFromDocument($filename,$path)
{
    $shell_cmd="sh " .$GLOBALS['doctrineroot']."/shell/io.sh". " ".$filename. " ".$path;
    $shell_cmd=escapeshellcmd($shell_cmd);
    $last_output=exec($shell_cmd,$output,$ret_val);
    if($ret_val!="0")
    {
        foreach($output as $line)
        {
            echo $line."\n";
        }
    }
    else
    {
        $filename=$last_output;
    }
}
?>
