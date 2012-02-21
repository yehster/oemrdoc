<script src="/openemr/library/js/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    function createDoc(thisValue,typeName) {
       $.post("/openemr/library/doctrine/interface/createDocument.php", {metadataUUID: ""+thisValue+""}, function(data){
            if(data.length >0) {
            html="<FORM action='/openemr/library/doctrine/ui/DocumentEditor.php' method='GET'><INPUT TYPE='TEXT' NAME='docUUID' VALUE='" +data +"'><INPUT TYPE='SUBMIT' NAME='"+typeName+"'/></FORM>";
            $('#results').html(html);
            }});

//   $('#suggestions').hide();
}
</script>
<?php
    include('/var/www/openemr/library/doctrine/init-session.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
            $qry = $em->createQuery("SELECT dt FROM library\doctrine\Entities\DocumentType dt");
            $res = $qry->getResult();
            foreach($res as $value)
            {
                echo "<DIV NAME='".$value->getshortDesc()."'onClick='javascript:createDoc(\"".$value->getuuid()."\",\"".$value->getshortDesc()."\")'>";
                echo $value->getshortDesc();
                echo "</DIV>";
                $md = $value;
            }

        ?>
        <div id="results"></div>
    </body>
</html>
