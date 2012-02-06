function handle_event_create(data)
{
    window.alert(data);
}
function event_select_change()
{
    dispatch_url=oemr_webroot+"/interface/PatientEvents/managePatientEvents.php";
    if(this.value>0)
        {
            $.post(dispatch_url,
            {
                patientID: oemr_pat_id,
                task: "create",
                eventType: this.value
            },
            handle_event_create
            );
            
        }
}
$(".patient_event_select").change(event_select_change);