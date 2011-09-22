// ==UserScript==
// @name          Reload Helper
// @require       http://code.jquery.com/jquery-1.6.4.min.js
// @namespace     http://yehster.no-ip.org/
// @description   
// @include       http://192.168.227.128/*
// @exclude       
// @exclude
// ==/UserScript==


function refreshHandler(evt)
{
    if(evt.keycode==116)
        {
            window.alert(window.location.href);
        }
}
$(window.document).keypress(refreshHandler);