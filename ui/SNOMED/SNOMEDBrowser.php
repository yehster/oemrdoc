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
    .normal {
            background-color: lightgreen;
    }

    .abnormal {
            background-color: lightpink;
    }
    table.formEntries {
        width: 100%;
    }
</style>

<script src="/openemr/library/js/jquery-1.5.min.js" type="text/javascript"></script>
<script  type="text/javascript">
    function getAttrForElem(elem,attrName)
{

    if($(elem).attr(attrName)!=undefined)
    {
        return($(elem).attr(attrName));
    }
    else
    {
        return getAttrForElem($(elem).parent(),attrName);
    }
}

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

    function showFormEntries(uuid)
    {
            $.post("/openemr/library/doctrine/ui/SNOMED/lookupSectionVocab.php",
                {
                    sectionUUID: ""+uuid+""
                },
                function(data) {
                    $("#FormEntries").html(data);
                }
            );

    }
    function clickSnomed()
    {
        mode=$("#mode").find("input:radio:checked").val();
        if((mode=="abnormal") || (mode=="normal"))
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
                    text: ""+text+"",
                    classification: ""+mode+"",
                    mode: "create"
                },
                function(data) {
                    $("#status").text(data);
                    uuid=$("#code").attr("uuid");
                    showFormEntries(uuid);
                });
        }
    }
    function chooseSection()
    {
        code=$(this).attr("code");
        code_type=$(this).attr("code_type");
        uuid=$(this).attr("uuid");
        $("#code").text(code);
        $("#code").attr("uuid",uuid);
        $("#codeType").text(code_type);
        showFormEntries(uuid);
    }

    function refreshFETable()
    {
        uuid=$("#code").attr("uuid");
        showFormEntries(uuid);

    }

    function modifyEntry()
    {
        uuid=getAttrForElem(this,"uuid");
        mode = this.getAttribute("value");
        $.post("/openemr/library/doctrine/ui/SNOMED/manageVocab.php",
                {
                    uuid: ""+uuid+"",
                    mode: ""+mode+"",
                    type: "FormEntry"
                },
                function(data) {
                    $("#status").text(data);
                    refreshFETable();
                });

    }

    function registerControlEvents()
    {
        $("#btnSearch").live({click: findConcepts});
        $("#btnSearchSection").live({click: findSections});
        $("tr.section").live({click: chooseSection, mouseover: function() {$(this).addClass('Highlight');} ,mouseout: function() {$(this).removeClass('Highlight');}});
        $("tr.SNOMED").live({click: clickSnomed ,mouseover: function() {$(this).addClass('Highlight');} ,mouseout: function() {$(this).removeClass('Highlight');} });
        $("input.modFE").live({click: modifyEntry});
    }
    window.onload= registerControlEvents;
</script>
<DIV ID="left">
    <H3>Terms:</H3>
<INPUT TYPE="TEXT" ID="txtSearch"/>
<INPUT TYPE="BUTTON" ID="btnSearch" value="search"/>
<DIV ID="results1"></DIV>
</DIV>
<DIV ID="middle">
    <H3>Document Section:</H3>
<INPUT TYPE="TEXT" ID="txtSearchSection"/>
<INPUT TYPE="BUTTON" ID="btnSearchSection" value="search"/>
<DIV ID="sectionInfo">
    
</DIV>
<DIV ID="sectionSearchResult">

</DIV>

</DIV>
<DIV ID="right">
    <H3>Details:</H3>
    <DIV ID="mode">
    <span>normal
        <input type="radio" name="mode" value="normal"/>
    </span>
    <span>abnormal
        <input type="radio" name="mode" value="abnormal"/>
    </span>
     <span>rel
        <input type="radio" name="mode" value="rel" checked/>
    </span>
    </DIV>
    <DIV id="FormEntries"></DIV>
    <h1 id="code"></h1>
    <h1 id="codeType"></h1>
    <h1 id="status"></h1>

</DIV>