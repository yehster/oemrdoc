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
            $("#sectionFormsDisplay table[type='form'] tr[code] input[type='checkbox']").change(formCheckBoxChanged);
            $("#sectionFormsDisplay table[type='form'] tr[code] input[type='text'][entrytype='quantitative']").blur(updateQuantitative);
            $("#sectionFormsDisplay table[type='form'] tr[code] select.units").change(updateQuantitative);
            $("#sectionFormsDisplay table[type='form'] th").dblclick(dblClickHeader);
            $("#sectionFormsDisplay table[type='form'] tr[code] td[type='label']").dblclick(dblClickLabel);
            $("#sectionFormsDisplay table[type='form'] tr[code] td[type='label'] input[type='text'].TableFreeText").blur(updateShortText);
            $("#sectionFormsDisplay table[type='form'] tr[code] td[type='FreeText'] input[type='text'].TableFreeText").blur(updateShortText);


            calculateBMI();
            registerCalculate();
        }
    );
}
function getFormDiv(button)
{
    parentDiv=$(button).parent("span").parent("div:first");
    df=parentDiv.siblings("div.displayForm");
    if(df.length==0)
        {
            dfDiv=document.createElement("div");
            dfDiv.setAttribute("class","displayForm");
            dfDisplay=document.createElement("div");
            dfDisplay.setAttribute("class","displayDiv");
            dfDiv.appendChild(dfDisplay);
            parentDiv.after(dfDiv);
            df=parentDiv.siblings("div.displayForm");
            $(df).hide();
        }
   return df;
}
function showFormDialog2()
{
    display=getFormDiv(this);
    
    entryUUID=$(this).attr("entryUUID");
    display.attr("entryUUID",entryUUID);
    displayDiv=display.find("div.displayDiv");
    label=$(this).siblings(".LABEL").text();
    displayDiv.html("");
    display.toggle();
    $.post("../DocumentForms/generateForm.php",
        {
            entryUUID: ""+entryUUID+""
        },
        function(data)
        {
            displayDiv.html(data);
            table=displayDiv.find("table[type='form']");
            table.find("tr[code] input[type='checkbox']").change(formCheckBoxChanged);
            table.find("tr[code] input[type='text'][entrytype='quantitative']").blur(updateQuantitative);
            table.find("tr[code] select.units").change(updateQuantitative);
            table.find("th").dblclick(dblClickHeader);
            table.find("td[type='label']").dblclick(dblClickLabel);
            table.find("tr[code] td[type='label'] input[type='text'].TableFreeText").blur(updateShortText);
            table.find("tr[code] td[type='FreeText'] input[type='text'].TableFreeText").blur(updateShortText);


            calculateBMI();
            registerCalculate();
        }
    );
}


function getCodeRow(child)
{
    return $(child).parents("tr[code]");
}

function dblClickLabel()
{
    
    div=$(this).find(".TableDivFreeText").show();
    text=div.find("input[type=text].TableFreeText");
    text.focus();
}

function updateShortText()
{
    
    task="update";
    codeRow = getCodeRow(this);
    code = codeRow.attr("code");
    code_type = codeRow.attr("code_type");
    seq=parseInt(codeRow.attr("seq")) + 1;
    
    value = $(this).val();

    parentuuid=$(this).parents("table[type='form']").attr("entryuuid");
    
    classification="ShortNarrative";
    // create or update a new ShortNarrative for this row.
    
    $.post("../../interface/manageFormShortNarrative.php",
    {
            task: ""+task+"",
            parentUUID: ""+parentuuid+"",
            code: ""+code+"",
            codeType: ""+code_type+"",
            classification: ""+classification+"",
            value: ""+value+"",
            seq: ""+seq+"",
            refresh: "YES"
        
    },
    manageShortNarrativeReturn
    );
    
    
}

function manageShortNarrativeReturn(data)
{
    pos=data.indexOf("<",0);
    uuid=data.substr(0,pos);
    refreshEntry(parentuuid,data.substr(pos));
//    tr.attr("entryuuid",uuid);

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
                else
                    {
                        if(heightUnits=="cm")
                            {
                                height=height/100;
                            }
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
    $("tr[code='8302-2'] select.units").change(calculateBMI);
    $("tr[code='3141-9'] input[type='text']").blur(calculateBMI);
    $("tr[code='3141-9'] select.units").change(calculateBMI);
}

function registerSectionFormsEvents()
{
    $("button[func='SHOWFORM']").live({click: showFormDialog2});
    $("#closeSectionForm").click(hideFormDialog);
    
}

