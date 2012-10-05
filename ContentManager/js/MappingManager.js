function build_codes_table(descriptions,codes,code_types)
{
    var table=$("<table><tbody></tbody></table>");
    var tbody=table.find("tbody");
    for(codeIdx=0;codeIdx<descriptions.length;codeIdx++)
        {
            var tr=$("<tr><td class='code_description' code='"+codes[codeIdx]+"' code_type='"+code_types[codeIdx]+"'>"+descriptions[codeIdx]+"</td>"+
                        "<td class='code'>"+codes[codeIdx]+"</td>"+
                        "</tr>");
            tbody.append(tr);
        }
    return table;
}
function lookupEntries(type,search,target)
{
    $.post("ajax/lookup.php",{
        type: type,
        searchString: search
    },
    function(data)
    {
        var table=build_codes_table(data.descriptions,data.codes,data.code_types);
        table.attr("class","entries");
        target.html(table)
        bind_code_table(table,clickCodeDescription);
    }
    ,"json"
    )
}
function searchCodes(elem)
{
    var section =$(elem).parents(".topDiv");
    var searchString = section.find("#codesLookup").val();
    var type = section.find("input[type='radio']:checked").val();
    var target=section.find("div.results");
    lookupEntries(type,searchString,target);

}
function codesLookupKUP(evt)
{
    var val=$(this).val();
    if((val.length>3)||(evt.keyCode==13))
        {
            searchCodes(this);    
        }
}

function codeTypeRadioChange(evt)
{
    searchCodes(this);
}

function clickCodeDescription(evt)
{
    var code=$(this).attr("code");
    var code_type=$(this).attr("code_type");
    
    $("#codeChoice").text($(this).text());
    $("#codeChoice").attr("code",code);
    $("#codeChoice").attr("code_type",code_type);
}
function bind_code_table(table,event)
{
    table.find(".code_description").on({click: event});
}


function clickSectionDescription(evt)
{
    var code=$(this).attr("code");
    var code_type=$(this).attr("code_type");
    
    $("#sectionChoice").text($(this).text());
    $("#sectionChoice").attr("code",code);
    $("#sectionChoice").attr("code_type",code_type);
    
    $.post("ajax/manageContentGroups.php",
    {
        task: "search",
        document_code: code,
        document_code_type: code_type
    },
    function(data)
    {
        $("#createGroup").removeAttr("disabled");
        $("#groupResults").html(data.result_count);
    }
    ,"json");
}

function createGroupClick(evt)
{
    var code = $("#sectionChoice").attr("code");
    var code_type =$("#sectionChoice").attr("code_type");

    $.post("ajax/manageContentGroups.php",
    {
        task: "create",
        document_code: code,
        document_code_type: code_type
    },
    function(data)
    {
        window.alert("yo!");
    }
    ,"json");
}

function lookupSections(target,searchString)
{
    $.post("ajax/lookupSections.php",{
        searchString: searchString
    },
    function(data)
    {
        var table=build_codes_table(data.descriptions,data.codes,data.code_types);
        table.attr("class","entries");
        target.html(table)
        bind_code_table(table,clickSectionDescription);
    }
    ,"json"
    )
    
}

function searchSections(elem)
{
    var htmlDIV=$(elem).parents(".topDiv");
    var resultsTarget=htmlDIV.find("div.results");
    var searchString=$("#sectionLookup").val();
    lookupSections(resultsTarget,searchString);
}

function sectionLookupKUP(evt)
{
    var val=$(this).val();
    if((val.length>3)||(evt.keyCode==13))
        {
            searchSections(this);    
        }
    
}
function sectionSelectChange(evt)
{
    var sectionText=$("#sectionSelect").val();
    $("#sectionLookup").val(sectionText);
    searchSections($("#sectionLookup"));
}

function setupControls()
{
    $("#codesLookup").on({keyup: codesLookupKUP});
    $("input[name='codeType']").on({click:codeTypeRadioChange});
    
    $("#sectionLookup").on({keyup: sectionLookupKUP});
    $("#sectionSelect").on({change: sectionSelectChange});
    $("#createGroup").on({click: createGroupClick});
    sectionSelectChange(null);
}
$(document).ready(setupControls);