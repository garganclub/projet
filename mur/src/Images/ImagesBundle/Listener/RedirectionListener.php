<?php

namespace Images\ImagesBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RedirectionListener {
    public function __construct(ContainerInterface $container, Session $session) {
    	$this->session = $session;
    	$this->router = $container->get('router');
    	$this->securityContext = $container->get('security.context');
    }
    public function onKernelRequest(GetResponseEvent $event) {
    	$route = $event->getRequest()->attributes->get('_route');
    	if(preg_match('/images_/', $route) || $route == 'images') {
    		if(!is_object($this->securityContext->getToken()->getUser())) {
				$this->session->getFlashBag()->add('warning', 'Vous devez vous inscrire ou vous connecter !');
				$event->setResponse(new RedirectResponse($this->router->generate('accueil_homepage')));
			}
    	}
    }
}