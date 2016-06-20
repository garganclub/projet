<?php

namespace Accueil\AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Images\ImagesBundle\Entity\Mur;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AccueilBundle:Default:index.html.twig');
    }
    public function vueAction($messageAdresse, $messageTexte)
    {
        return $this->render('AccueilBundle:Default:vue.html.twig', array(
        	'messageAdresse' => $messageAdresse,
        	'messageTexte' => $messageTexte
        ));
    }
    public function vueAjaxAction(Request $request)
    {
        $messageAdresse = $request->request->get('resultatAdresse');
        $messageTexte = $request->request->get('resultatTexte');
        if($messageAdresse == '' && $messageTexte == '') {
        	return $this->redirect($this->generateUrl('accueil_bombage'));
        }
        $answer['html'] = $this->forward('AccueilBundle:Default:vue', array('messageAdresse' => $messageAdresse, 'messageTexte' => $messageTexte))->getContent(); 
		$response = new Response();                                                
		$response->headers->set('Content-type', 'application/json; charset=utf-8');
		$response->setContent(json_encode($answer));
		return $response;
    }
    public function enregistreAction(Request $request)
    {
        $messageAdresse = $request->request->get('messageAdresse');
        $messageTexte = $request->request->get('messageTexte');
        if(is_object($this->container->get('security.context')->getToken()->getUser())) {
        	$utilisateur = $this->container->get('security.context')->getToken()->getUser()->getUsername();
        }
        else {
        	$utilisateur = 'anonyme';
        }
        $message = array('messageAdresse' => $messageAdresse, 'messageTexte' => $messageTexte);
        $mur = new Mur();
        $mur->setAuteur($utilisateur);
        $mur->setMessage($message);
        $em = $this->getDoctrine()->getManager();
        $em->persist($mur);
        $em->flush();
        
        return $this->redirect($this->generateUrl('index_mur'));
    }
}