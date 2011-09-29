// ==UserScript==
// @name          Allscripts Integration
// @namespace     http://yehster.no-ip.org/
// @description   
// @include       https://eprescribe.allscripts.com/*
// @include       */openemr/interface/main/main_title.php
// @include       */openemr/interface/patient_file/summary/demographics.php*
// @exclude       
// @exclude       
// @require http://code.jquery.com/jquery-1.6.4.min.js
// ==/UserScript==


var pages={
    interstitial: "/InterstitialAd.aspx",
    addPatient: "/AddPatient.aspx?Mode=Add",
    def: "/default.aspx",
    allergy: "/PatientAllergy.aspx",
    oemrMain: "/openemr/interface/main/main_title.php",
    oemrDemo: "openemr/interface/patient_file/summary/demographics.php"
}

var asContID={
    lblPatientName: "ctl00_lblPatientName",
    txtPatLNAME: "ctl00_ContentPlaceHolder1_PatientSearch_txtLastName_text",
    txtPatFNAME: "ctl00_ContentPlaceHolder1_PatientSearch_txtFirstName_text",
    txtPatDOB: "ctl00_ContentPlaceHolder1_PatientSearch_rdiDOB_text"
}


function resetInfo()
{
    GM_setValue("OpenEMR Server","");
    GM_setValue("OpenEMR Session","");
    
    // Patient Info
    GM_setValue("patientFNAME","");
    GM_setValue("patientLNAME","");
    
    GM_setValue("patientDOBYear","");
    GM_setValue("patientDOBMonth","");
    GM_setValue("patientDOBDay","");
    
    // Prescription Info
    GM_setValue("MedName","") // The Med Name
    GM_setValue("MedSTR",""); // The Med Strength
    GM_setValue("MedSIG",""); // The Med SIG
    
    GM_setValue("patientSearch","not started");
    
}

//TODO: Can I add a dialog div that displays the drugs from OpenEMR?

// Retrieve the patient information from the OpenEMR demographics page.
function findPatientInfo()
{
    text=$(this).html()
    marker="top.window.parent.left_nav.setPatient("
    loc=text.indexOf(marker);
    if(loc>=0)
    {
        //window.alert(text);
        end=text.indexOf(")",loc)
        rest=text.substr(loc+marker.length,end-(loc+marker.length));
        toks=rest.split(",");
        name=toks[0]
        nameParts=name.split(" ");
        fname=nameParts[0].replace("'","");
        lname=nameParts[1].replace("'","");
        pid=toks[1];
        pubpid=toks[2];
        dobSTR=toks[4]
        DOBHeader="DOB: ";
        AgeHeader="Age:";
        start = DOBHeader.length + dobSTR.indexOf(DOBHeader)
        DOB=dobSTR.substr(start,(dobSTR.indexOf(AgeHeader)-start));
        DOB.replace(" ","");
        DOBParts=DOB.split("-");
        DOBYear=DOBParts[0];
        DOBMonth=DOBParts[1];
        DOBDay=DOBParts[2];

        GM_setValue("patientFNAME",fname);
        GM_setValue("patientLNAME",lname);
    
        GM_setValue("patientDOBYear",DOBYear);
        GM_setValue("patientDOBMonth",DOBMonth);
        GM_setValue("patientDOBDay",DOBDay);
        
        window.alert(fname+":"+DOBYear +"/"+DOBMonth+"/"+DOBDay);
        
    }
}

function patDOB()
{
    retVal= GM_getValue("patientDOBMonth")+"/"+GM_getValue("patientDOBDay")+"/"+ GM_getValue("patientDOBYear");
    retVal.replace(" ","");
    return retVal;
 
}

function asPopulateAndSearchPatientInfo()
{
    $("#"+asContID['txtPatLNAME']).val(GM_getValue("patientLNAME"));
    $("#"+asContID['txtPatFNAME']).val(GM_getValue("patientFNAME"));
    window.alert(patDOB());
    $("#"+asContID['txtPatDOB']).val(patDOB());
}

var loc=window.location.href;
if(loc.indexOf(pages['interstitial'])>=0)
    {
        var adButton = document.getElementById("adControl_closeButton");
        if (adButton != null)
        {
            adButton.click();
        }
    
    }

if(loc.indexOf(pages['def'])>=0)
    {
//        if(GM_getValue("patientFound")=="not started")
        {
            asPopulateAndSearchPatientInfo();
            window.alert($("#"+asContID['lblPatientName']).text());
        }
    }



if(loc.indexOf(pages['oemrMain'])>=0)
    {
    }

if(loc.indexOf(pages['oemrDemo'])>=0)
    {
        resetInfo();
        $("script[language='JavaScript']").each(findPatientInfo);
    }
