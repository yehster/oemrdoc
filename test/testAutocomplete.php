<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
//include('/var/www/openemr/interface/globals.php');
?>
<html>
<style type="text/css">

.suggestionsBox {
    position: relative;
    left: 30px;
    margin: 10px 0px 0px 0px;
    width: 90%;
    background-color: #212427;
    -moz-border-radius: 7px;
    -webkit-border-radius: 7px;
    border: 2px solid #000;
    color: #fff;
}

.suggestionList {
    margin: 0px;
    padding: 0px;
}

.suggestionList li {
    margin: 0px 0px 3px 0px;
    padding: 3px;
    cursor: pointer;
}

.suggestionList li:hover {
    background-color: #659CD8;
}
</style>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>

    </head>
    <body>

<script src="/openemr/library/js/jquery.js" type="text/javascript"></script>

<script type="text/javascript">

function lookup(inputString) {

    if(inputString.length == 0) {
        // Hide the suggestion box.
        $('#suggestions').hide();
    } else {
        $.post("/openemr/library/doctrine/test/testDictionary.php", {queryString: ""+inputString+""}, function(data){
            if(data.length >0) {
                $('#suggestions').show();
                $('#autoSuggestionsList').html(data);
                $('#results').html(thisValue);
            }
        });
    }
} // lookup

function fill(thisValue) {
    $('#inputString').val(thisValue);
        $.post("/openemr/library/doctrine/test/testICD9Lookup.php", {queryString: ""+thisValue+""}, function(data){
            if(data.length >0) {
    $('#results').html(data);
            }});

//   $('#suggestions').hide();
}

</script>
        <?php
        // put your code here
        ?>
       <div>
         <div>

        
        <input size="30" id="inputString" onkeyup="lookup(this.value);" type="text" />

         <div class="suggestionsBox" id="suggestions" >
         <div class="suggestionList" id="autoSuggestionsList"></div>
        </div>
        <div id="results" class="suggestionsList"></div>


    </div>
    </body>
</html>
