function addSectionControls()
{
    var root_sections=$("section[depth='1']");
    var medications=$("section > span.label:contains('Medications')");
    var problems=$("section > span.label:contains('Problem List')");
    problems.after("<input type='text'/>");

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
            var parent_section=section.parents("section[depth='1']");
            var labelText = parent_section.children("span.label").text();
            var myLabel=section.children("span.label");
            if(myLabel.text().indexOf(labelText)==0)
                {
                    myLabel.text(myLabel.text().substr(labelText.length+1));
                }
        }
}
addSectionControls();
