function build_codes_table(descriptions,codes,code_types)
{
    var table=$("<table><tbody></tbody></table>");
    var tbody=table.find("tbody");
    for(codeIdx=0;codeIdx<descriptions.length;codeIdx++)
        {
            var tr=$("<tr><td class='code_description'>"+descriptions[codeIdx]+"</td>"+
                        "<td>"+codes[codeIdx]+"</td>"+
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
        target.html(table)
        bind_code_table(table);
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
    window.alert($(this).text());
}
function bind_code_table(table)
{
    table.find(".code_description").on({click: clickCodeDescription});
}

function setupControls()
{
    $("#codesLookup").on({keyup: codesLookupKUP});
    $("input[name='codeType']").on({click:codeTypeRadioChange});
}
$(document).ready(setupControls);