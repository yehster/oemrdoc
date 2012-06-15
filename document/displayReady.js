function simplifyDisplay(parent)
{
    var secondary_sections=parent.find("section:not([depth='1'])");
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
            var control=toAdd.find(".controls > .vocab_control");
            if(control.length==0)
                {
                    var controlsSection=toAdd.children(".controls");
                    control=$("<span>&#xe054</span>");
                    control.addClass("vocab_control");
                    control.addClass("document_iconic");
                    control.on({
                        click: show_vocab_form
                    })
                    controlsSection.append(control);
                }               
    });
}

function setupDeletable(idx,elem)
{
    var item=$(elem);
    var controls=item.find(".controls");
    if(controls.find("span[func='delete']").length==0)
    {
        var button=$("<span>&#xe05a;</span>");
        var di=new doctrineInfo(elem);
        button.attr("entryUUID",di.uuid);
        button.attr("func","delete");
        button.addClass("document_iconic");
        button.addClass("delete_button");
        controls.append(button);
        button.on(
        {
            click: function()
                {
                    var requestTime=new Date().getTime();    
                    var entryUUID=$(this).attr("entryUUID");
                    $.post("/openemr/library/doctrine/interface/deleteEntry.php",
                        {
                            entryUUID: entryUUID,
                            refresh: "doc",
                            requestTime: requestTime                                        
                        },
                        function(data)
                        {
                            refreshSection(data);
                        },"json"
                        );

                }
        });

    }
    
}

function setupDeleteControls(parent)
{
    var deletableSpan=parent.find("[candelete='true']").each(setupDeletable);
    parent.find("textarea[candelete='true']").each(setupDeletable);
    
}

function addNarrative(evt)
{
    entryUUID=$(this).attr("entryuuid");
    $.post("/openemr/library/doctrine/interface/manageNarrative.php",
        {parentUUID: ""+entryUUID+"",
         task: "create",
         refresh: "doc"},
         refreshSection,
         "json"
    );
}

function addNarrativeControl(idx,elem)
{
    var item=$(elem);
    if(item.find("span[func='addNarrative']").length==0)
    {
        var control=$("<span>&#xe06d</span>");
        var di=new doctrineInfo(elem);
        control.attr("entryUUID",di.uuid);
        control.attr("func","addNarrative");
        control.addClass("document_iconic");
        item.append(control);
        control.on({click: addNarrative});
    }
}
function setupAddNarrative(parent)
{
    var problems=parent.find("span.problem >span.controls");
    problems.each(addNarrativeControl);
}

function setupDisplay(parent)
{
    simplifyDisplay(parent);
    addVocabMappingControls(parent);
    setupProblems(parent);
    setupDeleteControls(parent);
    setupAddNarrative(parent);
    setupMedicationControls(parent);
    addMoveControls(parent);
    setupContextMenu(parent);
}

function refreshSection(data)
{
//    var new_html=$(data.html);
//    window.alert(data.html);
    var skipRefresh=false;
    var toRefresh=$("[uuid='"+data.uuid+"']");
    if(typeof(data.requestTime)!='undefined')
        {
            var oldTime=toRefresh.attr("requestTime");
            if(typeof(oldTime)!='undefined')
                {
                    var intOld=parseInt(oldTime);
                    var intRef=parseInt(data.requestTime);
                    skipRefresh=intOld>intRef;
                }
        }
    if(!skipRefresh)    
        {
            toRefresh.replaceWith(data.html);
            var parent=$("[uuid='"+data.uuid+"']").parent();
            $("[uuid='"+data.uuid+"']").attr("requestTime",data.requestTime);
            setupDisplay(parent);
            registerEvents(parent);
            if((typeof data.focusUUID)!="undefined")
                {
                    parent.find("[uuid='"+data.focusUUID+"']").focus();
                }
        }
}

setupDisplay($("#main"));