function headerClick()
{
    window.alert($(this).text());
}

function patientClick()
{
    patient_id=$(this).attr("patid");
    new_url="/openemr/interface/patient_file/summary/demographics.php?set_pid="+patient_id
    window.location.href=new_url;
}
function registerDashboardEvents()
{
    $(".dashboard th").click(headerClick);
    $("table.dashboard td[patid]").click(patientClick);
}
registerDashboardEvents();