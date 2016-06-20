<?php

namespace Images\ImagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ImagesController extends Controller
{
    public function murAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tags = $em->getRepository('ImagesBundle:Mur')->listeGlobale();
        
        return $this->render('ImagesBundle:Images:mur.html.twig', array(
        	'tags' => $tags
        ));
    }
}