<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<html>
<body>
    <form id="testform" method="post" enctype="multipart/form-data">
        <input type="file" name="file"/>
        <input type='text' name="text" value='stuff' />
        <input type='submit' name="submit" value='SUBMIT' />
    </form>
    <h1> hello world! </h1>
<?php
    error_log("processing test file upload");
    if(sizeof($_FILES)!=0)
    {
        echo sizeof($_FILES);
        foreach($_FILES as $file)
        {
            echo "<h1>".$file['name']."|".$file['tmp_name']."|".$file['size']."</h1>";
            echo "<img src='"."http://ubuntu/openemr/library/doctrine/libre_tmp/".$file['name']."'/>";
            error_log(move_uploaded_file($file['tmp_name'],"/var/www/openemr/library/doctrine/libre_tmp/".$file['name']));
            if(strpos($file['name'],".DOC")!==false)
            {
                require_once("/var/www/openemr/library/doctrine/init-em.php");
                require_once("$doctrineroot/libreoffice/processLibreDocuments.php");
                $filename="/var/www/openemr/library/doctrine/libre_tmp/".$file['name'];
                $path="/var/www/openemr/library/doctrine/libre_tmp/xml/";
                $le=generateXMLFromDocument($em,$filename,$path);
                if(!$le->successful())
                {
                    echo "failed generating XML\n";
                    exit();
                }
                $XML=$le->getXMLDOM();
                $pat=null;
                $lpe=matchPatient($em,$le->getFile(),$XML,$pat);
                if(!$lpe->successful())
                {
                    echo $lpe->getMessage()."\n"."failed matching patient";
                    exit();
                }

                echo "Patient Matched:".$pat->displayName()."\n";

                $user=null;
                $lue=identifyDictator($em,$le->getFile(),$XML,$user);
                if(!$lue->successful())
                {
                    echo $lue->getMessage()."\n"."Unable to determine user";
                    exit();
                }

                echo $user->getUsername();

                createLibreDocument($em,$lue->getFile(),$XML,$user,$pat);
                
            }
        }
        error_log("file received!");
    }
    foreach ($_REQUEST as $k=>$v)
    {
       echo $k; 
       echo $v;
    }
?>
</body>
</html>
