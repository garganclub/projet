<?php

namespace Images\ImagesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Images\ImagesBundle\Entity\Image;
use Images\ImagesBundle\Form\ImageType;
use Images\ImagesBundle\Form\ImageEditType;

/**
 * Image controller.
 *
 */
class ImageController extends Controller
{

    /**
     * Lists all Image entities.
     *
     */
    public function indexAction()
    {
        if($this->get('request')->get('page') != null) {
        	$page = $this->get('request')->get('page');
        }
        else {
        	$page = 1;
        }
        return $this->render('ImagesBundle:Image:index.html.twig', array(
        	'page' => $page
        ));
    } 
    public function listeAction($page = null)
    {
        $utilisateur = $this->container->get('security.context')->getToken()->getUser()->getUsername();
        $nombreElements = 12;
        if($page == null) {
        	$page = 1;
        }
        $em = $this->getDoctrine()->getManager();
        $recupereEntities = $em->getRepository('ImagesBundle:Image')->listeGlobale();
        $tri = array();
        for($i=0;$i<sizeOf($recupereEntities);$i++) {
        	$dossier = explode('/',$recupereEntities[$i]->getAdresse());
        	if($dossier[0] == $utilisateur) {
        		array_push($tri, $recupereEntities[$i]);
        	}
        }
        $entities = $this->get('knp_paginator')->paginate($tri,$page,$nombreElements);

        return $this->render('ImagesBundle:Image:liste.html.twig', array(
            'entities' => $entities,
        	'page' => $page
        ));
    }
    public function ajaxAction() {
    	$answer['html'] = $this->forward('ImagesBundle:Image:liste', array('page' => $this->get('request')->query->get('page')))->getContent(); 
		$response = new Response();                                                
		$response->headers->set('Content-type', 'application/json; charset=utf-8');
		$response->setContent(json_encode($answer));
		return $response;
    }
    
    /**
     * Creates a new Image entity.
     *
     */
    public function createAction(Request $request)
    {
        $utilisateur = $this->container->get('security.context')->getToken()->getUser()->getUsername();
        $entity = new Image($utilisateur);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entities = $em->getRepository('ImagesBundle:Image')->findBy(array('adresse' => $entity->getTitre()));
            if(sizeOf($entities) > 0) {
            	$this->get('session')->getFlashBag()->add('error', 'Ce titre existe déjà, choisissez en un autre');
            }
            else {
            	$dossier = 'uploads/'.$utilisateur;
            	if(is_dir($dossier)){
            		$autorisation = true;
            		$repertoire = opendir($dossier);
            		while (($fichier = readdir($repertoire)) !== false) {
            			$nom = explode('.',$fichier);
            			if($nom[0] == $entity->getTitre()) {
            				$autorisation = false;
            				break;
            			}
            		}
            		closedir($repertoire);
            		if(!$autorisation) {
						$this->get('session')->getFlashBag()->add('error', 'Ce titre existe déjà, choisissez en un autre');
            		}
            		else {
						$em->persist($entity);
						$em->flush();
						return $this->redirect($this->generateUrl('images_show', array('id' => $entity->getId(), 'page' => 1)));
            		}
            	}
            	else {
            		$em->persist($entity);
					$em->flush();
					return $this->redirect($this->generateUrl('images_show', array('id' => $entity->getId(), 'page' => 1)));
            	}
            }    
        }
	 	else {
			$this->get('session')->getFlashBag()->add('warning', 'Erreur système : formulaire invalide, revenez plus tard');
		} 

        return $this->render('ImagesBundle:Image:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a form to create a Image entity.
     *
     * @param Image $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Image $entity)
    {
        $form = $this->createForm(new ImageType(), $entity);

        $form->add('submit', 'submit', array('label' => 'Valider'));

        return $form;
    }

    /**
     * Displays a form to create a new Image entity.
     *
     */
    public function newAction()
    {
        $utilisateur = $this->container->get('security.context')->getToken()->getUser()->getUsername();
        $entity = new Image($utilisateur);
        $form   = $this->createCreateForm($entity);

        return $this->render('ImagesBundle:Image:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Image entity.
     *
     */
    public function showAction($id, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ImagesBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ImagesBundle:Image:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'page' => $page
        ));
    }

    /**
     * Displays a form to edit an existing Image entity.
     *
     */
    public function editAction($id, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ImagesBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ImagesBundle:Image:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'page' => $page
        ));
    }

    /**
    * Creates a form to edit a Image entity.
    *
    * @param Image $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Image $entity)
    {
        $form = $this->createForm(new ImageEditType(), $entity);

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    /**
     * Edits an existing Image entity.
     *
     */
    public function updateAction(Request $request, $id, $page)
    {
        //$utilisateur = $this->container->get('security.context')->getToken()->getUser()->getUsername();
        //$entity = new Image($utilisateur);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ImagesBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }
		
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
        	if(isset($_POST["images_imagesbundle_imageedit"]['affiche'])) {
				$sql = "UPDATE image SET affiche = :valeur WHERE id = :id";
				$params = array(':id' => $id, ':valeur' => $_POST["images_imagesbundle_imageedit"]['affiche']);
				$stmt = $em->getConnection()->prepare($sql);
				$stmt->execute($params);
			}
			
            return $this->redirect($this->generateUrl('images_show', array('id' => $id, 'page' => $page)));
        }

        return $this->render('ImagesBundle:Image:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Image entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ImagesBundle:Image')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Image entity.');
            }

            $sql = "DELETE FROM image WHERE id = :id";
			$params = array(':id'=> $id);
			$stmt = $em->getConnection()->prepare($sql);
			$stmt->execute($params);
        }

        return $this->redirect($this->generateUrl('images'));
    }
    
    public function effacerAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ImagesBundle:Image')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Image entity.');
		}

		$sql = "DELETE FROM image WHERE id = :id";
		$params = array(':id'=> $id);
		$stmt = $em->getConnection()->prepare($sql);
		$stmt->execute($params);

        return $this->redirect($this->generateUrl('images'));
    }

    /**
     * Creates a form to delete a Image entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('images_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
