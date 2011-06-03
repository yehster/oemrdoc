require "rubygems"
require "watir-webdriver"
require "watir-webdriver/wait"

require "test/unit"

module OEMRTestFrame
  def CreateTestPatient(sex="Male")
    currentTime=Time.new
    suffix=currentTime.mon.to_s + currentTime.day.to_s + currentTime.hour.to_s + currentTime.min.to_s + currentTime.sec.to_s
    puts(suffix)
    patient=OEMRTestFrame::Patient.new
    patient.dob= Time.local(currentTime.year - 30)
    
    patient.fname, patient.mname, patient.lname = "First" + suffix, "Middle" + suffix, "Last" + suffix
    patient.sex= sex
    return patient
  end #def CreateTestPatient


	LBL_Frame_Login="Login"
	LBL_Frame_Title="Title"
	LBL_Frame_LNav ="left_nav"
	LBL_Frame_RTop ="RTop"
	LBL_Frame_RBot ="RBot"
	LBL_Sec_Admin = "Administration"
	LBL_Sec_Users="Users"
	LBL_Link_AddUser = "Add User"
	LBL_TF_AddUserID = "rumple"
	LBL_TF_AddUserPWD = "stiltskin"
	LBL_Sel_AccGrp="access_group[]"
	LBL_Sel_AccGrp_Physician="Physicians"
	
	LBL_Sec_Patient="Patient/Client"
	LBL_Sec_Management="Management"
	LBL_Sec_Visit_Forms="Visit Forms"
	LBL_Link_New_Search="New/Search"
  LBL_But_Search="search"
  
	class Patient
    attr_accessor :fname,:mname, :lname, :dob, :sex
	def initialize()
	end
	def initialize(fn="",ln="")
		@fname=fn
		@lname=ln
		@dob=nil
	end
    def dobSTR
		if (@dob!=nil)
			retval = @dob.mon.to_s + "/" + @dob.day.to_s + "/" + @dob.year.to_s
		else
			retval = ""
		end
    end #dobSTR
  end #class patient

	class Manager
    def CreateTestPatient(sex="Male")
      currentTime=Time.new
      suffix=currentTime.mon.to_s + currentTime.day.to_s + currentTime.hour.to_s + currentTime.min.to_s + currentTime.sec.to_s
      puts(suffix)
      patient=OEMRTestFrame::Patient.new
      patient.dob= Time.local(currentTime.year - 30, currentTime.mon, currentTime.day)
    
      patient.fname, patient.mname, patient.lname = "First" + suffix, "Middle" + suffix, "Last" + suffix
      patient.sex= sex
      return patient
    end #def CreateTestPatient
#/////////////////////////////////////////////////////////////////////////////
#Name: check_pop-up
#Description: Checks If a pop up with the given title and text appears. Throws error if the pop up does not appear.
#                             If the pop up appears clicks the 'OK' button.
#Arguments: title: Title of the pop up to look for
#                 text:  Text of the pop up to look for
#/////////////////////////////////////////////////////////////////////////////

      def check_pop_up(title,button)
    auto=WIN32OLE.new('AutoItX3.control')
    #Refer to AutoIt WinTitleMatchMode document: "2 = Match any substring in the title"
    auto.Opt("WinTitleMatchMode", 2)
    #Check if such pop up appears. Time out after 10 sconds
	#MozillaDialogClass
    popup_appears=auto.WinWait("[CLASS:MozillaDialogClass]",nil,20)
	winTitle=auto.WinGetTitle("[CLASS:MozillaDialogClass]",nil)
	puts(winTitle)
	winText=auto.WinGetText("[CLASS:MozillaDialogClass]",nil)
	puts(winText)
    assert_equal(popup_appears,1,"The popup did not appear/the popup text is incorrect")
    #Click 'OK' button
	auto.WinActivate("[CLASS:MozillaDialogClass]");
	auto.Send("{ENTER}")
    #Check if the pop up disappeared.
    sleep(1)
    popup_disappears=auto.WinExists("[CLASS:MozillaDialogClass]",nil)
    assert_equal(popup_disappears,0,"The popup did not close")
  end
    def brw
      return @brw
    end #brw
		def initialize(baseURL="localhost/openemr",userID="admin",pwd="pass")
			@baseURL=baseURL
			@brw= Watir::Browser.new :firefox
                        @brw.goto(@baseURL)
                        login(userID,pwd)
		end # initialize
		def login(userID,pwd)
			frmLogin=@brw.frame(:name,LBL_Frame_Login)
			frmLogin.text_field(:name,"authUser").value=userID
			frmLogin.text_field(:name,"clearPass").value=pwd
			frmLogin.button(:value,"Login").click	
		end #login
		def logout
			@brw.frame(:name,LBL_Frame_Title).link(:text,"Logout").click
		end #logout
		def frmLeftNav
			return @brw.frame(:name,LBL_Frame_LNav)
		end #frmLeftNav
		def frmRTop
			return @brw.frame(:name,LBL_Frame_RTop)
		end #frmRTop
		def frmRBot
			return @brw.frame(:name,LBL_Frame_RBot)
		end #frmRBot


		#navigational methods
		#need to address existing state (e.g. don't click if desired section already visible)
		def navAdmin(section)
			frmLeftNav.span(:text,LBL_Sec_Admin).click
			frmLeftNav.link(:text,section).click
		end #navAdmin
		
		def navPatient(section,function=nil)
			
                Watir::Wait::until { frmLeftNav.span(:text,LBL_Sec_Patient).exists? }
			unless frmLeftNav.link(:text,function).visible?
				frmLeftNav.span(:text,LBL_Sec_Patient).click
			end 
			unless function == nil
                            frmLeftNav.link(:text,function).click
			end #unless
		end #navPatient 



		def addUser(userID,pwd,access_role)
			navAdmin(LBL_Sec_Users)
			begin
				frmRTop.link(:href,"usergroup_admin_add.php").click
				#frmRTop.link(:class,"iframe_medium css_button").click
			rescue
				frmRTop.link(:href,"usergroup_admin_add.php").click
			end
			sleep(2)			
			form=frmRTop.div(:id,"fancy_outer").div(:id,"fancy_content")
			puts(form.html)
#			frmRTop.form(:name,"new_user").text_field(:name,LBL_TF_AddUserID).text=userID
#			puts(userField.value)
#			userField.value = userID
#			puts(UserField.value)		
#			iframe.text_field(:name,LBL_TF_AddUserID).value = userID
#			iframe.text_field(:name,LBL_TF_AddUserPWD).value = pwd
#			frmRTop.form(:name,"new_user").SelectList(:name,LBL_Sel_AccGrp).value = LBL_Sel_AccGrp_Physician
			

		end #addUser


		LBL_TF_PatientFName="form_fname"
		LBL_TF_PatientMName="form_mname"
		LBL_TF_PatientLName="form_lname"
		LBL_TF_Patientdob="form_DOB"
		LBL_Sel_PatientSex="form_sex"
		LBL_But_Create="create"
		def addPatient(p)
			# can improve this abstraction by developing classes specific to various forms in OEMR. Still need to understand how that would work.

      navPatient(nil,LBL_Link_New_Search)
      Watir::Wait::until { frmRTop.text_field(:id,LBL_TF_PatientFName).exists? }

			frmRTop.text_field(:id,LBL_TF_PatientFName).value = p.fname
			frmRTop.text_field(:id,LBL_TF_PatientMName).value = p.mname
			frmRTop.text_field(:id,LBL_TF_PatientLName).value = p.lname
			frmRTop.text_field(:id,LBL_TF_Patientdob).value = p.dobSTR
			frmRTop.select_list(:id,LBL_Sel_PatientSex).set(p.sex)
      sleep(1)

			#broken in firefox!
			brw.startClicker("OK")
			puts "before click"
			#brw.startClicker("OK")
			frmRTop.button(:id,LBL_But_Create).click
			puts "after click!"
			check_pop_up("The page at","OK")
			#frmRTop.button(:id,LBL_But_Create).click_no_wait

      #check_pop_up("Message from webpage","OK")


		end #addPatient
    LBL_DIV_SR="searchResults"
	def findPatient(p)
      navPatient(nil,LBL_Link_New_Search)
      Watir::Wait::until { frmRTop.text_field(:id,LBL_TF_PatientFName).exists? }


			frmRTop.text_field(:id,LBL_TF_PatientFName).value = p.fname
			frmRTop.text_field(:id,LBL_TF_PatientMName).value = p.mname
			frmRTop.text_field(:id,LBL_TF_PatientLName).value = p.lname
			if(p.dob!=nil)
				frmRTop.text_field(:id,LBL_TF_Patientdob).value = p.dobSTR
			end
			frmRTop.select_list(:id,LBL_Sel_PatientSex).select(p.sex)

      Watir::Wait::until { frmRTop.button(:id,LBL_But_Search).exists? }

      frmRTop.button(:id,LBL_But_Search).click
      sleep(2)
	  popupURL=@baseURL + "/interface/main/finder/patient_select.php?popup=1"
      popup=Watir::Browser.attach(:url,popupURL)
      puts "URL:" + popup.url
      srDiv=popup.div(:id,LBL_DIV_SR)
      fullname = p.lname.to_s
      fullname = fullname + ", "
      fullname = fullname + p.fname
      puts fullname
      found = false
	  patID= ""
      srDiv.tables[1].each do |row|
        row.each do |cell|
          #puts cell.text
          if cell.text == fullname then
            found = true
			patID=row.id
			row.click
			if @brwType == 'firefox' then
				jsCommand = "var target; for(i=0;i<getWindows().length;i++) {if(getWindows()[i].content.opener) {target=getWindows()[i];}} var content = target.content; opener=content.opener;"
				jsCommand = jsCommand + "opener.document.location.href = '/openemr/interface/patient_file/summary/demographics.php?set_pid="
				jsCommand = jsCommand + patID + "'; target.window.close();"
				#jsCommand = "var target = getWindows()[1]; var content = target.content; pat = new Object(); pat.id= '9'; target.document.SelectPatient(pat);"
				puts(jsCommand)
				popup.js_eval(jsCommand)
			end
			break
          end
        end #do row
        break if found
      end #do table

    end #def
	def addAppointment(hour,day,month,year,provider, title)
		#click the add button
		puts("here!")
		frmRTop.link(:text,"Add").click
		sleep(1)
		popup=Watir::Browser.attach(:title,"Add New Event")
		popup.select_list(:name,"form_provider").set(provider)
		if(hour>11) then
			if hour > 12 then
				hour = hour - 12
			end
			popup.select_list(:name,"form_ampm").set("PM")
		else
			popup.select_list(:name,"form_ampm").set("AM")		
		end
		popup.text_field(:name,"form_hour").set(hour.to_s)
		popup.text_field(:name,"form_minute").set("00")
		popup.text_field(:name,"form_date").set(month.to_s + "-" + day.to_s + "-" + year.to_s)
		popup.text_field(:name,"form_title").set(title)
		popup.button(:value,"Save").click
	end #def addAppointment
	
	def viewDocuments()
		@brw.goto(@baseURL+"/library/doctrine/test/testDocumentEditor.php")
	end  # def viewDocuments

	def createDocument(type)
    puts("creating document")
		@brw.goto(@baseURL+"/library/doctrine/test/testDocument.php")
    if type=="HP" then
      @brw.div(:name,"HP").click()
      @brw.div(:id,"results").button(:name,"HP").click()
    end
	end  #def viewDocuments


	end # Manager
end # module

