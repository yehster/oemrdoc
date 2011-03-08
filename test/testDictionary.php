<?php
//include('/var/www/openemr/interface/globals.php');
include('/var/www/openemr/library/doctrine/init-em.php');
if(isset($_POST['queryString']))
    {
        $queryString = $_POST['queryString'];
        if(strlen($queryString) >0) {
            $query = "SELECT k FROM library\doctrine\Entities\Keyword k WHERE k.content LIKE '".$queryString."%'";
            $dqry = $em->createQuery($query);
        
            $res = $dqry->getResult();
            foreach($res as $value)
            {
                echo '<li onClick="fill('."'".$value->getContent()."'".');">'.$value->getContent().'</li>';
            }

        }
   }
?>