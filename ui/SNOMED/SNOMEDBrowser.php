<?php
include('/var/www/openemr/library/doctrine/init-em.php');
?>

<script src="/openemr/library/js/jquery-1.5.min.js" type="text/javascript"></script>
<script  type="text/javascript">
    function updateResults(data)
    {
        $("#results1").html(data);
    }

    function findConcepts()
    {
        searchString = $('#txtSearch').val();
        $.post("/openemr/library/doctrine/ui/SNOMED/lookupSNOMED.php",
            {searchString: ""+searchString+"" },
            updateResults
        );
    }

    function registerControlEvents()
    {
        $("#btnSearch").live({click: findConcepts});

    }
    window.onload= registerControlEvents;
</script>
<INPUT TYPE="TEXT" ID="txtSearch"/>
<INPUT TYPE="BUTTON" ID="btnSearch" value="search"/>
<DIV ID="results1"></DIV>
