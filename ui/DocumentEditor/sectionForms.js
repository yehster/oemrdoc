function dblClickHeader()
{
    tr=$(this).parent("tr");
    sibs=tr.siblings("tr[classification='normal']");
    if(sibs.length)
        {
            sibs.find("input:checkbox[val='Y']").attr("checked","checked").change();
        }
        else
        {
            sibs=tr.siblings("tr[classification='abnormal']");
            sibs.find("input:checkbox[val='N']").attr("checked","checked").change();
            
        }
    
}

function formCheckBoxChanged()
{
    tr=$(this).parents("tr[code]");
    td=$(this).parent("td");
    value=$(this).is(':checked');    
    otherBoxes=td.siblings("td").find("input:checkbox");
    if(otherBoxes.length>0)
        {
            if(value)
                {
                    otherBoxes.removeAttr("checked");
                    task="update";
                    value=$(this).attr("val");
                }
                else
                {
                    task="clear";                
                }
        }
    else
        {
                if(value)
            {
                task="update";        
            }
            else
            {
                task="clear";
            }
        }
    code=tr.attr("code");
    code_type=tr.attr("code_type")
    parentuuid=$(this).parents("table[type='form']").attr("entryuuid");
    classification=tr.attr("classification");
    entryuuid=tr.attr("entryuuid");


    label=tr.children("td[type='label']").text();
    seq=tr.attr("seq");
    $.post("../../interface/manageVocabEntry.php",
        {
            task: ""+task+"",
            parentUUID: ""+parentuuid+"",
            code: ""+code+"",
            codeType: ""+code_type+"",
            entryUUID: ""+entryuuid+"",
            classification: ""+classification+"",
            value: ""+value+"",
            seq: ""+seq+"",
            text: ""+label+"",
            refresh: "YES"
        },
        function(data)
        {
             pos=data.indexOf("<",0);
             uuid=data.substr(0,pos);
             refreshEntry(parentuuid,data.substr(pos));
             tr.attr("entryuuid",uuid);
             if(task=="update")
                 {
                     

             if(classification=="multiple")
                 {
                     tr.siblings("[classification='exclusive']").find("input:checkbox:checked").removeAttr("checked").change();
                 }
             else if(classification=="exclusive")
                 {
                        tr.siblings().find("input:checkbox:checked").removeAttr("checked").change()                     
                 }
                 }
        }
    );
}
function showFormDialog()
{
    $("#sectionForm").show();
    entryUUID=$(this).attr("entryUUID");
    $("#sectionForm").attr("entryUUID",entryUUID);
    label=$(this).siblings(".LABEL").text();
    $("#sectionFormsDisplay").html("");
    $.post("../DocumentForms/generateForm.php",
        {
            entryUUID: ""+entryUUID+""
        },
        function(data)
        {
            $("#sectionFormsDisplay").html(data);
            $("#sectionFormsDisplay table[type='form'] tr[code] input[type='checkbox']").change(formCheckBoxChanged)
            $("#sectionFormsDisplay table[type='form'] tr[code] input[type='text'][entrytype='quantitative']").blur(updateQuantitative)
            $("#sectionFormsDisplay table[type='form'] tr[code] select.units").change(updateQuantitative)
            $("#sectionFormsDisplay table[type='form'] th").dblclick(dblClickHeader)
            calculateBMI();
            registerCalculate();
        }
    );
}

function hideFormDialog()
{
    $("#sectionForm").hide();    
}

function updateQuantitative()
{
    td=$(this).parent("td");
    tr=$(this).parents("tr[code]")
    valInput=td.find("input[entrytype='quantitative']");
    unitsInput=td.find("select");
    if(unitsInput.length==0)
        {
            units="";
        }
        else
            {
                units=escape(unitsInput.val());
            }
            
    value=valInput.val()
    code=tr.attr("code");
    code_type=tr.attr("code_type")
    parentuuid=$(this).parents("table[type='form']").attr("entryuuid");
    classification=tr.attr("classification");
    entryuuid=tr.attr("entryuuid");
    label=tr.children("td[type='label']").text();
    seq=tr.attr("seq");

    if(value=="")
        {
            task="clear";
        }
        else
        {
            task="update"
                
        }
    $.post("../../interface/manageVocabEntry.php",
        {
            task: ""+task+"",
            parentUUID: ""+parentuuid+"",
            code: ""+code+"",
            codeType: ""+code_type+"",
            entryUUID: ""+entryuuid+"",
            classification: ""+classification+"",
            value: ""+value+"",
            units: ""+units+"",
            seq: ""+seq+"",
            text: ""+label+"",
            
            refresh: "YES"
        },
        function(data)
        {
             pos=data.indexOf("<",0);
             uuid=data.substr(0,pos);
             refreshEntry(parentuuid,data.substr(pos));
             tr.attr("entryuuid",uuid);
        }
   )


}

function calculateBMI()
{
    height=$("tr[code='8302-2'] input[type='text']").val();
    weight=$("tr[code='3141-9'] input[type='text']").val();

    if((height == parseFloat(height)) && (weight == parseFloat(weight)))
    {
        if((height!=0) && (weight!=0))
        {
            heightUnits=$("tr[code='8302-2'] select.units").val();
            weightUnits=$("tr[code='3141-9'] select.units").val();
            if(heightUnits=="inches")
                {
                    height=height*0.0254; // 2.54 cm/inch
                }
            if(weightUnits=="lbs")
                {
                    weight = weight / 2.2;
                }
            BMI = weight/(height*height);
            BMI = BMI.toFixed(1);
            $("tr[code='39156-5'] input[type='text']").val(BMI).blur();
        }
    }
}

function registerCalculate()
{
    $("tr[code='8302-2'] input[type='text']").blur(calculateBMI);
    $("tr[code='3141-9'] input[type='text']").blur(calculateBMI);
}

function registerSectionFormsEvents()
{
    $("button[func='SHOWFORM']").live({click: showFormDialog});
    $("#closeSectionForm").click(hideFormDialog);
    
}

