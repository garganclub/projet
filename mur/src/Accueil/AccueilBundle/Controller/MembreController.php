<?php

namespace Accueil\AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MembreController extends Controller
{
    public function membreAction()
    {
        return $this->render('AccueilBundle:Membre:membre.html.twig');
    }
}