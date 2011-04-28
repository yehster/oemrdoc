function numbersOnly()
{
    this.value = this.value.replace(/[^0-9\.]/g,'');
}

function updateQuant()
{
    this.value = this.value.replace(/[^0-9\.]/g,'');
    val = this.value;
    if(this.value=="")
        {
            return;
        }
    task = "update";
    content = $(this).parent().siblings("text").text();
    vocabID = $(this).attr("code");
    quantUUID = null;
    seq=$(this).attr("seq");
    parentEntryUUID = getAttrForElem(this,"sectionid");
    units=$(this).siblings("select.units").val();
            $.post("/openemr/library/doctrine/interface/manageEntry.php",
           {
                parentEntryUUID: ""+parentEntryUUID+"",
                vocabID: ""+vocabID+"",
                quantUUID: ""+quantUUID+"",
                EntryType: "quantitative",
                task: ""+task+"",
                val: ""+val+"",
                seq: ""+seq+"",
                content: ""+content+"",
                units: ""+units+"",
                refresh: "YES"
            },
            function(data) {
                    idText = "#" + parentEntryUUID;
                    $(idText).replaceWith(data) ;
                    $(idText).removeClass('hidden');
            }
            );

}
function registerFormQuantEvents()
{
    $("input[type='text'].quantitative").live({blur: updateQuant, 
        keyup: numbersOnly} 
    );
}
