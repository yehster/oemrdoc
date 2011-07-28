<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
?>
<style>

</style>
<section ID="problemDialog">
    <input type="text" id="txtProblem"/>
    <button id="useTxtProblem">Add Problem</button>
    <button id="cancelProblem">Cancel</button> 
    <span id="medLoading"><img src="../loading.gif"></span>    
    <section ID="problemFavorites"/> </section>
    <section ID="problemSearch"/> </section>       
</section>

<script src="../../../js/jquery-1.6.1.min.js"></script>
<script src="icd9lookup.js"></script>
<script>
window.onload=registerICD9lookupEvents;    
</script>