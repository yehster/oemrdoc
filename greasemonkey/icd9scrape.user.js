// ==UserScript==
// @name          ICD9 Web Crawler
// @namespace     http://yehster.no-ip.org/
// @description   
// @include       http://www.icd9data.com/*
// @exclude       
// @exclude       
// ==/UserScript==

links=document.getElementsByTagName('a');
window.alert(links.length);
links[1].click();