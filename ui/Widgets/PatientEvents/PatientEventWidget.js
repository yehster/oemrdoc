function handle_event_create(data)
{
   $("#patient_events_widget").replaceWith(data);
    bind_patient_event_events();
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
                eventType: this.value,
                refresh: 'yes'
            },
            handle_event_create
            );
            
        }
}
function bind_patient_event_events()
{
    $(".patient_event_select").change(event_select_change);
}
bind_patient_event_events();