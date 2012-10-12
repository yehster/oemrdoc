function toggleGroupChooserVisibility()
{
    if($("#sections:visible").length>0)
        {
            $("#sections").hide();
            $("#groups").hide();           
        }
        else
        {
            $("#sections").show();
            $("#groups").show();           
        }
}

