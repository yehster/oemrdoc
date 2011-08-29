
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
        registerLOINCEvents();
    }

    function updateSectionsResults(data)
    {
        $("#sectionSearchResult").html(data);
    }        

    function findConcepts()
    {
        searchString = $('#txtSearch').val();
        searchType=$("#selSearchType").val();
        if(searchType=="SNOMED")
            {
                $.post("/openemr/library/doctrine/ui/SNOMED/lookupSNOMED.php",
                {searchString: ""+searchString+"" },
                updateResults
                );
            }
       else if(searchType=="LOINC")
           {
                $.post("/openemr/library/doctrine/ui/SNOMED/lookupLOINC.php",
                {searchString: ""+searchString+"" },
                updateResults
                );               
           }
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
            $("input.modFE").die();
            $("input.modFE").live({click: modifyEntry});
    }
    function createFormEntry(src,mode)
    {
            aui=$(src).attr("aui");
            targetCode=$("#code").text();
            targetType=$("#codeType").text();
            text=$(src).find("td.str").text();
            if(mode=="opt")
                {
                    mode="option";
                    type="Option";
                }
                else
                    {
                        type="FormEntry";
                    }
            $.post("/openemr/library/doctrine/ui/SNOMED/manageVocab.php",
                {
                    aui: ""+aui+"",
                    targetCode: ""+targetCode+"",
                    targetType: ""+targetType+"",
                    text: ""+text+"",
                    type: ""+type+"",
                    classification: ""+mode+"",
                    mode: "create"
                },
                function(data) {
                    $("#status").text(data);
                    uuid=$("#code").attr("uuid");
                    showFormEntries(uuid);
                });

    }
    function clickSnomed()
    {
        mode=$("#mode").find("input:radio:checked").val();
        if((mode=="abnormal") || (mode=="normal") || (mode=="opt"))
        {
            createFormEntry(this,mode);
        }
        if(mode=="rel")
        {
            lookupRel(this);
        }
        if(mode=="target")
        {
            code=$(this).attr("aui");
            code_type="SNOMED";
            $("#code").text(code);
            $("#code").attr("uuid","");
            $("#codeType").text(code_type);
        }
    }

function clickRel()
{
    mode=$("#mode").find("input:radio:checked").val();
    if(mode=="opt")
        {
            createFormEntry(this,"opt");
        }
        else
            {
                    lookupRel(this);
            }
}


function lookupRel(obj)
{
                aui=$(obj).attr("aui");
                $.post(
            "/openemr/library/doctrine/ui/SNOMED/lookupRelationships.php",
                {
                    aui: ""+aui+""
                },
                function(data) {
                    $("#right").html(data);
                }
               );
                                           $("tr.relationship").die();

                        $("tr.relationship").live({click: clickRel
                                    ,mouseover: function() {$(this).addClass('Highlight');}
                                    ,mouseout: function() {$(this).removeClass('Highlight');
                                    }});
                        $("select.OptionSelect").die();
                        $("select.OptionSelect").live({change: modifyOption});

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
                },
                function(data) {
                    $("#status").text(data);
                    refreshFETable();
                });

    }

    function modifyOption()
    {

        uuid=getAttrForElem(this,"uuid");
        classification = $(this).val();
        $.post("/openemr/library/doctrine/ui/SNOMED/manageVocab.php",
                {
                    uuid: ""+uuid+"",
                    mode: "updateClass",
                    classification: ""+classification+"",
                    type: "Option"
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
        $("tr.FormEntry").live({click: clickFE});
        $("input.modFE").live({click: modifyEntry});
        registerLOINCEvents();

    }
    window.onload= registerControlEvents;
