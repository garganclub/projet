<?php

namespace Utilisateurs\UtilisateursBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class RedirectionController extends Controller
{
    public function retourAction()
    {
        if(isset($_POST['confirmation'])) {
			$_SESSION['confirmation'] = $_POST['confirmation'];
		}
		if(isset($_POST['adresseRetour'])) {
			$_SESSION['adresseRetour'] = $_POST['adresseRetour'];
		}
        
        $session = $this->getRequest()->getSession();
        if(!$session->has('retour')) {
        	$session->set('retour', array());
        }
        if(isset($_POST['confirmation'])) {
			$retour = $session->get('retour');
			$retour['message'] = $_POST['confirmation'];
			$session->set('retour', $retour);
		}
        
        return $this->redirect($_POST['adresseRetour']);
    }
    public function finAction()
    {
        $session = $this->getRequest()->getSession();
        $retour = $session->get('retour');
        if($session->has('retour')) {
        	unset($retour['message']);
        	$session->set('retour', $retour);
        }
        
        return $this->redirect($_SESSION['adresseRetour']);
    }
}