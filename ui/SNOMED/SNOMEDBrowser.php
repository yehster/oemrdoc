<?php
include('/var/www/openemr/library/doctrine/init-em.php');
?>

<style>
    #left
    {
        width: 30%;
        float: left;
    }
    #middle
    {
        width: 30%;
        float:left;
    }
    #right
    {
        float: left;
        width: 30%;
    }

    .Highlight {
        background-color: yellow;
        cursor: pointer;
    }

</style>

<script src="/openemr/library/js/jquery-1.5.min.js" type="text/javascript"></script>
<script  type="text/javascript">
    function updateResults(data)
    {
        $("#results1").html(data);
    }

    function updateSectionsResults(data)
    {
        $("#sectionSearchResult").html(data);
    }        $("#btnSearch").live({click: findConcepts});

    function findConcepts()
    {
        searchString = $('#txtSearch').val();
        $.post("/openemr/library/doctrine/ui/SNOMED/lookupSNOMED.php",
            {searchString: ""+searchString+"" },
            updateResults
        );
    }
    
    function findSections()
    {
        searchString = $('#txtSearchSection').val();
        $.post("/openemr/library/doctrine/ui/SNOMED/lookupSection.php",
            {searchString: ""+searchString+"" },
            updateSectionsResults
        );        
    }

    function clickSnomed()
    {
        aui=$(this).attr("aui");
        targetCode=$("#code").text();
        targetType=$("#codeType").text();
        text=$(this).find("td.str").text();
        $.post("/openemr/library/doctrine/ui/SNOMED/manageVocab.php",
            {
                aui: ""+aui+"",
                targetCode: ""+targetCode+"",
                targetType: ""+targetType+"",
                text: ""+text+""
            },
            function(data) {
                $("#status").text(data);
            });
    }
    function chooseSection()
    {
        code=$(this).attr("code");
        code_type=$(this).attr("code_type");
        $("#code").text(code);
        $("#codeType").text(code_type);
    }

    function registerControlEvents()
    {
        $("#btnSearch").live({click: findConcepts});
        $("#btnSearchSection").live({click: findSections});
        $("tr.section").live({click: chooseSection, mouseover: function() {$(this).addClass('Highlight');} ,mouseout: function() {$(this).removeClass('Highlight');}});
        $("tr.SNOMED").live({click: clickSnomed ,mouseover: function() {$(this).addClass('Highlight');} ,mouseout: function() {$(this).removeClass('Highlight');} });
    }
    window.onload= registerControlEvents;
</script>
<DIV ID="left">
<INPUT TYPE="TEXT" ID="txtSearch"/>
<INPUT TYPE="BUTTON" ID="btnSearch" value="search"/>
<DIV ID="results1"></DIV>
</DIV>
<DIV ID="middle">
<INPUT TYPE="TEXT" ID="txtSearchSection"/>
<INPUT TYPE="BUTTON" ID="btnSearchSection" value="search"/>
<DIV ID="sectionInfo">
    
</DIV>
<DIV ID="sectionSearchResult">

</DIV>

</DIV>
<DIV ID="right">
    <h1 id="code"></h1>
    <h1 id="codeType"></h1>
    <h1 id="status"></h1>

</DIV>