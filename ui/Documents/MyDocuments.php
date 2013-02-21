<!DOCTYPE html>
<style type="text/css" media="all">
   @import "MyDocuments.css";
</style>
<html>
    <head>
        <meta charset="utf-8">
        <title>My Documents</title>
    </head>
    <body>        
<?php
require_once("/var/www/openemr/library/doctrine/init-session.php");
require_once("$doctrineroot/common/checkUser.php");
require_once("$doctrineroot/ui/Documents/MyDocumentsUtil.php");

$DOM = new DOMDocument("1.0","utf-8");

$tableDocs = $DOM->createElement("TABLE");
$tableDocs->setAttribute("class","documentsTable");
$tbodyDocs = $DOM->createElement("tbody");
$tableDocs->appendChild($tbodyDocs);

$docs = findUnlockedDocs($em,$doctrineUser);
genDocsTable($em,$DOM,$tbodyDocs,$doctrineUser,$docs);

echo $DOM->saveXML($tableDocs);
?>
<button class="bill">Bill</button>
<script src="/openemr/library/js/jquery-1.9.1.min.js"></script>
<script src="MyDocuments.js"></script>
<script src="/openemr/library/doctrine/ui/Billing/OEMRBillingProcess.js"></script>
<script>
    $(document).ready(function(){
        registerMyDocsEvents($("body"));
        setupEncountersForm($("body"));
    });
</script>
    </body>
</html>
