function headerClick()
{
    window.alert($(this).text());
}

function patientClick()
{
    patient_id=$(this).attr("patid");
    new_url="/openemr/interface/patient_file/summary/demographics.php?set_pid="+patient_id
    
    if(typeof top.goPid!="undefined")
        {
           
            top.restoreSession();
            top.RTop.location = new_url;
            top.confirmFrameVisible(top.frameProxies["patient"]);                
            
        }
        else
        {
            window.location.href=new_url;    
        }

    if(typeof top.removeCreatedTabs!="undefined")
        {
            top.removeCreatedTabs();
        }
}
function registerDashboardEvents()
{
    $(".dashboard th").click(headerClick);
    $("table.dashboard td[patid]").click(patientClick);
}
registerDashboardEvents();