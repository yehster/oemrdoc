function simplifyDisplay()
{
    var secondary_sections=$("section[depth='2']");
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

function addVocabMappingControls()
{
    var mapped_sections=$("section[vocab='true'][entrytype='Section']");
    mapped_sections.each(function(idx,elem){
 
       var parent=$(elem).parents("section[entrytype='Section']:first");
       var control=parent.children(".vocab_control");
       if(control.length==0)
           {
               var label=parent.children(".label:first");
               control=$("<button>details</button>");
               control.addClass("vocab_control");
               label.after(control);
           }
    });
}
simplifyDisplay();
addVocabMappingControls();