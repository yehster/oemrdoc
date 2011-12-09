<?php
require_once("$doctrineroot/queries/AllergiesQueries.php");
function ReviewInfo($em,$DOM,$pat,$section)
{

    $retval=$DOM->createElement("span","hello world!");
    $info=findSubEntries($em,$pat,$section->getCode(),$section->getCode_type());
    echo count($info);
    $DOM->appendChild($retval);
}
?>
