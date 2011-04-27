function numbersOnly()
{
    this.value = this.value.replace(/[^0-9\.]/g,'');
}

function updateQuant()
{
    this.value = this.value.replace(/[^0-9\.]/g,'');
    val = this.value;
    task = "update";
    content = $(this).parent().siblings("text").text();
    window.alert(content);
    
            $.post("/openemr/library/doctrine/interface/manageEntry.php",
           {
                parentEntryUUID: ""+parentEntryUUID+"",
                vocabID: ""+vocabID+"",
                quantUUID: ""+quantUUID+"",
                EntryType: "nominative",
                task: ""+task+"",
                val: ""+val+"",
                content: ""+content+"",
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
