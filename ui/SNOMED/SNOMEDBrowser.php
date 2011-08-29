<?php
include('/var/www/openemr/library/doctrine/init-em.php');
?>

<style>
    #left
    {
        width: 24%;
        float: left;
    }
    #middle
    {
        width: 24%;
        float:left;
    }
    #middle2
    {
        width: 24%;
        float:left;
    }

    #right
    {
        float: left;
        width: 24%;
    }

    .Highlight {
        background-color: yellow;
        cursor: pointer;
    }
    .normal {
            background-color: lightgreen;
    }

    .abnormal {
            background-color: lightpink;
    }
    table.formEntries {
        width: 100%;
    }
    .rad{
       border: black thin solid;
    }
</style>

<script src="/openemr/library/js/jquery-1.6.1.min.js" type="text/javascript"></script>
<script src="/openemr/library/doctrine/ui/SNOMED/LOINCBrowser.js" type="text/javascript"></script>
<script src="/openemr/library/doctrine/ui/SNOMED/SNOMEDBrowser.js" type="text/javascript"></script>
<DIV ID="left">
    <H3>Terms:</H3>
<INPUT TYPE="TEXT" ID="txtSearch"/>
<INPUT TYPE="BUTTON" ID="btnSearch" value="search"/>
<SELECT ID="selSearchType">
    <OPTION>SNOMED</OPTION>
    <OPTION>LOINC</OPTION>
</SELECT>
<DIV ID="results1"></DIV>
</DIV>
<DIV ID="middle">
    <H3>Document Section:</H3>
<INPUT TYPE="TEXT" ID="txtSearchSection"/>
<INPUT TYPE="BUTTON" ID="btnSearchSection" value="search"/>
<DIV ID="sectionInfo">
    
</DIV>
<DIV ID="sectionSearchResult">

</DIV>

</DIV>

<DIV ID="middle2">

    <H3>Details:</H3>
    <DIV ID="mode">
    <span class="rad">normal
        <input type="radio" name="mode" value="normal"/>
    </span>
    <span class="rad">abnormal
        <input type="radio" name="mode" value="abnormal"/>
    </span>
     <span class="rad">rel
        <input type="radio" name="mode" value="rel" checked/>
    </span>
     <span class="rad">target
        <input type="radio" name="mode" value="target"/>
    </span>
     <span class="rad">opt
        <input type="radio" name="mode" value="opt"/>
    </span>
     <span class="rad">text
        <input type="radio" name="mode" value="text"/>
    </span>

    </DIV>
    <DIV id="FormEntries"></DIV>
    <h1 id="code"></h1>
    <h1 id="codeType"></h1>
    <h1 id="status"></h1>

</DIV>
<DIV ID="right"> </DIV>
