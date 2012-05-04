function simplifyDisplay(parent)
{
    var secondary_sections=parent.find("section[depth='2']");
    secondary_sections.each(hideEmpty);
}

function hideEmpty(idx,elem)
{
    var section=$(elem);
    if(section.children("span.content").children().length==0)
    {
        section.hide();
    }
    else
        {
            // If the subsection label has a prefix of the parent section, then strip it out.
            var parent_section=section.parents("section[depth='1']");
            var labelText = parent_section.children("span.label").text();
            var myLabel=section.children("span.label");
            if(myLabel.text().indexOf(labelText)==0)
                {
                    myLabel.text(myLabel.text().substr(labelText.length+1));
                }
        }
}
function show_vocab_form()
{
    debugMessage("Stub function for vocab display!");
}
function process_problems()
{
    var problem = $(this).val();
    debugMessage(problem);
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

function setupProblems(parent)
{
    var problemsLabel=parent.find("section[entrytype='Section'] > span.label:contains('Problem List')");
    if(problemsLabel.length==0)
        {
            return;
        }
    var problemsInput=$("<input type='text'/>");
    problemsLabel.after(problemsInput);
    problemsInput.addClass("problems_input");
    problemsInput.on({
        keyup: process_problems
    });
    // 
    var content=problemsLabel.siblings("span.content");
    var list=$("<ol></ol>");
    content.children().each(function(idx,elem)
    {
        var li=$("<li></li>");
        li.append(elem);
        list.append(li);
    }
    );
    content.append(list);
}
function setupDisplay(parent)
{
    simplifyDisplay(parent);
    addVocabMappingControls(parent);
    setupProblems(parent);
}

setupDisplay($("#main"));