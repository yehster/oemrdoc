function toggleGroupChooserVisibility()
{
    if($("#sections:visible").length>0)
        {
            $("#sections").slideUp();
            $("#groups").slideUp();           
        }
        else
        {
            $("#sections").slideDown();
            $("#groups").slideDown();           
        }
}

