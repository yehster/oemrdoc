require "rubygems"
require "watir"
require "OEMRTestFrame"

Watir::Browser.default='firefox'

@@m=OEMRTestFrame::Manager.new("http://192.168.75.129/openemr","admin","pass")
#@@m.login("admin","pass")
#@@m.addUser("physician1","physician1","Physician")
#modify to create a patient with unique name and set birthdate based on current date

patient = @@m.CreateTestPatient
patient.fname='John'
patient.mname=''
patient.lname='Doe'
@@m.addPatient(patient)
patient.dob=nil
@@m.findPatient(patient)

 currentTime=Time.new
 suffix=currentTime.mon.to_s + currentTime.day.to_s + currentTime.hour.to_s + currentTime.min.to_s + currentTime.sec.to_s
 

@@m.addAppointment(((currentTime.hour)+1),currentTime.day,currentTime.mon, currentTime.year,"Yeh, Kevin", "Test")

#m.navPatient(OEMRTestFrame::LBL_Sec_Visit_Forms)
