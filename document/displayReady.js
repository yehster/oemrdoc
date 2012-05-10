function simplifyDisplay(parent)
{
    var secondary_sections=parent.find("section[depth='2']");
    secondary_sections.each(hideEmpty);
}

function hideEmpty(idx,elem)
{
    // This function is used to process second level sections
    var section=$(elem);

    // If the subsection label has a prefix of the parent section, then strip it out.
    var parent_section=section.parents("section[depth='1']");
    var labelText = parent_section.children("span.label").text();
    var myLabel=section.children("span.label");
    if(myLabel.text().indexOf(labelText)==0)
    {
        myLabel.text(myLabel.text().substr(labelText.length+1));
    }

    // Hide empty sections that have vocabulary options
    if((section.attr("vocab")=="true") &&(section.children("span.content").children().length==0))
    {
        section.hide();
    }

}
function show_vocab_form()
{
    var di=new doctrineInfo(this);
    debugMessage("Stub function for vocab display!"+di.uuid);
}

function addVocabMappingControls(parent)
{
    var mapped_sections=parent.find("section[vocab='true'][entrytype='Section']");
    mapped_sections.each(function(idx,elem){
 
       var parent=$(elem).parents("section[entrytype='Section']:first");
       var toAdd=parent;
       if(parent.length==0)
           {
               toAdd=$(elem);
           }
            var control=toAdd.children(".vocab_control");
            if(control.length==0)
                {
                    var label=toAdd.children(".label:first");
                    control=$("<button>details</button>");
                    control.addClass("vocab_control");
                    control.on({
                        click: show_vocab_form
                    })
                    label.after(control);
                }               
    });
}


function setupDisplay(parent)
{
    simplifyDisplay(parent);
    addVocabMappingControls(parent);
    setupProblems(parent);
}

function refreshSection(data)
{
//    var new_html=$(data.html);
//    window.alert(data.html);
    $("[uuid='"+data.uuid+"']").replaceWith(data.html);
    var parent=$("[uuid='"+data.uuid+"']").parent();
    
    setupDisplay(parent);
    registerEvents(parent);
}

setupDisplay($("#main"));