<?php
require_once('/var/www/openemr/library/doctrine/init-session.php');
session_write_close();
require_once("groupManagementUtilities.php");

$section_code=$_REQUEST['section_code'];
$section_code_type=$_REQUEST['section_code_type'];
$context_code=$_REQUEST['context_code'];
$context_code_type=$_REQUEST['context_code_type'];

$group=findOrCreateGroup($section_code,$section_code_type,$context_code,$context_code_type);

$retval=json_encode(array("msg"=>$group->getUUID().":".$section_code.":".$section_code_type.":".$context_code.":".$context_code_type));

echo $retval;
?>
