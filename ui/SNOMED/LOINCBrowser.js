function clickLOINC()
{
    loinc_num=$(this).attr("loinc_num");
    str=$(this).children("td.str").text();
    property=$(this).attr("property");
    
    
    st = $(this).attr("scale_type");
    targetCode=$("#code").text();
    targetType=$("#codeType").text();    

    sourceType="LOINC";
    sourceCode=loinc_num;
    if(st=="Qn")
        {
            mode = "quantitative";
        }
    type="VocabComponent";
    text=str;
            $.post("/openemr/library/doctrine/ui/SNOMED/manageVocab.php",
                {
                    sourceType: ""+sourceType+"",
                    sourceCode: ""+sourceCode+"",
                    targetCode: ""+targetCode+"",
                    targetType: ""+targetType+"",
                    text: ""+text+"",
                    type: ""+type+"",
                    property: ""+property+"",
                    classification: ""+mode+"",
                    mode: "create"
                },
                function(data) {
                    $("#status").text(data);
                    uuid=$("#code").attr("uuid");
                    showFormEntries(uuid);
                });
}

function registerLOINCEvents()
{
    $("tr.LOINC").die();
    $("tr.LOINC").live({mouseover: function() {$(this).addClass('Highlight');} ,mouseout: function() {$(this).removeClass('Highlight');}, click: clickLOINC });

}