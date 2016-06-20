<?php

namespace Utilisateurs\UtilisateursBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ConnexionController extends Controller
{
    public function appelConnexionAction()
    {
        $answer['html'] = $this->forward('FOSUserBundle:Security:login')->getContent(); 
		$response = new Response();                                                
		$response->headers->set('Content-type', 'application/json; charset=utf-8');
		$response->setContent(json_encode($answer));
		return $response;
    }
}
