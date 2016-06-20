<?php

namespace Accueil\AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BombageController extends Controller
{
    public function bombageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $recupereEntities = $em->getRepository('ImagesBundle:Image')->listeGlobale();        
        $entities = $this->get('knp_paginator')->paginate($recupereEntities,$this->get('request')->query->get('page', 1),8);
        
        return $this->render('AccueilBundle:Default:bombage.html.twig', array(
        	'entities' => $entities
        ));
    }
    public function iconesAction($page = null)
    {
        $nombreElements = 8;
        if($page == null) {
        	$page = 1;
        }
        $em = $this->getDoctrine()->getManager();
        $recupereEntities = $em->getRepository('ImagesBundle:Image')->listeGlobale();        
        $entities = $this->get('knp_paginator')->paginate($recupereEntities,$page,$nombreElements);
        
        return $this->render('AccueilBundle:Default:icones.html.twig', array(
        	'entities' => $entities,
        	'page' => $page
        ));
    }
    public function ajaxAction() {
    	$answer['html'] = $this->forward('AccueilBundle:Bombage:icones', array('page' => $this->get('request')->query->get('page')))->getContent(); 
		$response = new Response();                                                
		$response->headers->set('Content-type', 'application/json; charset=utf-8');
		$response->setContent(json_encode($answer));
		return $response;
    }
}