<?php
include('/var/www/openemr/library/doctrine/init-session.php');
if(isset($_POST['queryString']))
    {
        $queryString = $_POST['queryString'];
        if(strlen($queryString) >0) {
            // can add prioritzation of codes
            $query = "SELECT c FROM  library\doctrine\Entities\Code c, library\doctrine\Entities\KeywordCodeAssociation kwa, library\doctrine\Entities\Keyword k WHERE k.content = '".$queryString."' and kwa.keyword=k and kwa.code=c";
            $dqry = $em->createQuery($query);

            $res = $dqry->getResult();
            foreach($res as $value)
            {
                echo '<li onClick="fill('."'".$value->getCodeText()."'".');">'.$value->getCodeText().'</li>';
            }

        }
   }
?>
