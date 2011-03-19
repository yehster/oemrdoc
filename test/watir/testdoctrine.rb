require "rubygems"
require "watir"
require "OEMRTestFrame"

#use firefox by default
Watir::Browser.default = 'firefox'
@host='192.168.75.130'
ARGV.each do|a|
  puts "Argument: #{a}"
	if a=='ie' or a=='IE'
		Watir::Browser.default = 'ie'
	end
    if a=='docEditor'
    @mode= 'docEditor'
  end
  if a=='createHP'
    @mode= 'createHP'
  end
  puts a[0..1];
  if a[0..1]=='-h'
        @host=a[2,a.length]
  end

end
puts @host
@host='http://'+@host+"/openemr"
#@@m=OEMRTestFrame::Manager.new("http://192.168.1.137/openemr","admin","pass")
@@m=OEMRTestFrame::Manager.new(@host,"admin","pass")

#@@m.login("admin","pass")
#@@m.addUser("physician1","physician1","Physician")
#modify to create a patient with unique name and set birthdate based on current date

patient = @@m.CreateTestPatient
patient.fname='John'
patient.mname=''
patient.lname='Doe'
#@@m.addPatient(patient)
patient.dob=nil
@@m.findPatient(patient)

 currentTime=Time.new
 suffix=currentTime.mon.to_s + currentTime.day.to_s + currentTime.hour.to_s + currentTime.min.to_s + currentTime.sec.to_s
 
if (@mode=='docEditor')
	@@m.viewDocuments()
end

if (@mode=='createHP')
  @@m.createDocument("HP")
end
#@@m.addAppointment(((currentTime.hour)+1),currentTime.day,currentTime.mon, currentTime.year,"Yeh, Kevin", "Test")

#m.navPatient(OEMRTestFrame::LBL_Sec_Visit_Forms)
