// ==UserScript==
// @name          Allscripts Integration
// @namespace     http://yehster.no-ip.org/
// @description   
// @include       https://eprescribe.allscripts.com/*
// @exclude       
// @exclude       
// @require http://code.jquery.com/jquery-1.6.4.min.js
// ==/UserScript==


var pages={
    interstitial: "/InterstitialAd.aspx",
    addPatient: "/AddPatient.aspx?Mode=Add",
    def: "/default.aspx",
    allergy: "/PatientAllergy.aspx"
}

function resetInfo()
{
    GM_setValue("OpenEMR Server","");
    GM_setValue("OpenEMR Session","");
    
    // Patient Info
    GM_setValue("patientFNAME","");
    GM_setValue("patientLNAME","");
    GM_setValue("patientDOB","");
    
    // Prescription Info
    GM_setValue("MedName","") // The Med Name
    GM_setValue("MedSTR",""); // The Med Strength
    GM_setValue("MedSIG",""); // The Med SIG
    
    GM_setValue("patientFound",false);
    
}

//TODO: Can I add a dialog div that displays the drugs from OpenEMR?

var loc=window.location.href;
if(loc.indexOf(pages[interstitial])>=0)
    {
        var adButton = document.getElementById("adControl_closeButton");
        if (adButton != null)
        {
            adButton.click();
        }
    }