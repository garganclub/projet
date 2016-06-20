<?php

namespace Utilisateurs\UtilisateursBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class InscriptionController extends Controller
{
    public function appelInscriptionAction()
    {
        $answer['html'] = $this->forward('FOSUserBundle:Registration:register')->getContent(); 
		$response = new Response();                                                
		$response->headers->set('Content-type', 'application/json; charset=utf-8');
		$response->setContent(json_encode($answer));
		return $response;
    }
}
