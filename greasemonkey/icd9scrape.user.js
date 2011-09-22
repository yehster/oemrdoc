// ==UserScript==
// @name          ICD9 Web Crawler
// @namespace     http://yehster.no-ip.org/
// @description   
// @include       http://www.icd9data.com/*
// @exclude       
// @exclude       
// ==/UserScript==

links=document.getElementsByTagName('a');
//window.alert(links.length);
//window.alert(window.location);

if(window.location.href=="http://www.icd9data.com/")
    {
        for(idx=0;idx<links.length;idx++)
        {
            if(links[idx].href="http://www.icd9data.com/2011/Volume1/default.htm");          
        }
    
    }
