<?php

namespace IntegralEMR\ContextBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    public function indexAction($uuid,Request $request)
    {
        $session = $request->getSession();
        $last_update=$session->get("last_update");
        $elapsed=time() - intval($last_update);
        if  ($elapsed > 10)
        {
            $site_id=$session->get('site_id');
            $login="/openemr/interface/login/login_frame.php?error=1&site=".$site_id.":".$last_update.":".$elapsed.":".$session->getId();
            $session->set("last_update",time());
            return new Response($login) ;
            
        }
        require_once("/var/www/openemr/library/doctrine/init-em.php");
        $doc = $em->getRepository('library\doctrine\Entities\Document')->find($uuid);
        
        $test=$request->get("test","default");
        if($doc==null)
        {
            return new Response("no document found");
        }
        return $this->render('IntegralEMRContextBundle:Default:index.html.twig', array('document' => $doc,  'test'=>$test));
    }
}
