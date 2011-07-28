<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
?>
<style>

</style>

<script src="../../../js/jquery-1.6.1.min.js"></script>
<script src="icd9lookup.js"></script>
<script>
window.onload=registerICD9lookupEvents;    
</script>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>ICD9 Search - Integral EMR</title>
    </head>
<body>
<section ID="problemDialog">
    <input type="text" id="txtProblem"/>
    <span>Filter</span>
    <input type="checkbox" id="filter" checked="true"/>
    <span id="problemLoading"><img src="../loading.gif"></span>    
    <section ID="problemFavorites"/> </section>
    <section ID="problemSearch"/> </section>       
</section>
</body>
</html>