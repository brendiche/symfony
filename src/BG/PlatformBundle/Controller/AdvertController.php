<?php

namespace BG\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use BG\PlatformBundle\Entity\Advert;
use BG\PlatformBundle\Entity\Image;
use BG\PlatformBundle\Entity\Application;
use BG\PlatformBundle\Entity\Skill;
use BG\PlatformBundle\Entity\AdvertSkill;

class AdvertController extends Controller
{
    public function indexAction($page){
      if($page === "") $page = 1;
      $nbPerPage = 3;
      $repo = $this->getDoctrine()->getManager()->getRepository("BGPlatformBundle:Advert");
      $listAdverts = $repo->myfind($page, $nbPerPage);
      $nbPages = ceil(count($listAdverts)/$nbPerPage);
      if($page > $nbPages) return $this->render("BGPlatformBundle:Advert:notFound.html.twig");
      
     return $this->render("BGPlatformBundle:Advert:index.html.twig",array("listAdverts"=>$listAdverts,'nbPages' => $nbPages,'page'=> $page));
    }

    public function index01Action(){
    	$url = $this->get('router')->generate('bg_platform_view',array("id"=>10));
    	return $this->redirect($url);
    }

    public function viewAction($id){
        $repository = $this->getDoctrine()->getManager()->getRepository('BGPlatformBundle:Advert');
        $advert = $repository->find($id);
        if($advert === null) {
          return $this->render("BGPlatformBundle:Advert:notFound.html.twig");
        }

        $listApplications = $this->getDoctrine()->getManager()->getRepository('BGPlatformBundle:Application')->findBy(array('advert' => $advert));
        return $this->render('BGPlatformBundle:Advert:view.html.twig', array('advert' => $advert,'listApplications'=>$listApplications));
    }
  
    public function addAction(Request $request){
      $advert = new Advert;
      $advert->setDate(new \Datetime());
      $form = $this->get('form.factory')->createBuilder('form',$advert)
      ->add('date',      'date')
      ->add('title',     'text')
      ->add('content',   'textarea')
      ->add('author',    'text')
      ->add('published', 'checkbox',array('required'=>false))
      ->add('save',      'submit')
      ->getForm();
      $form->handleRequest($request);

        if($request->isMethod('POST') && $form->isValid()){
          $em = $this->getDoctrine()->getManager();
          $em->persist($advert);
          $em->flush();
          $request->getSession()->getFlashBag()->add('notice','Annonce bien enregistrée.');
          return $this->redirect($this->generateUrl('bg_platform_view', array('id' => $advert->getId())));    
        }
        return $this->render('BGPlatformBundle:Advert:add.html.twig',array('form' => $form->createView()));
    }

    public function editAction($id,Request $request){
      $em = $this->getDoctrine()->getManager(); 
      $advert = $em->getRepository('BGPlatformBundle:Advert')->find($id);
      if (null === $advert) {
        return $this->render("BGPlatformBundle:Advert:notFound.html.twig");
      }
      $form = $this->get('form.factory')->createBuilder('form', $advert)
      ->add('date',      'date')
      ->add('title',     'text')
      ->add('content',   'textarea')
      ->add('author',    'text')
      ->add('published', 'checkbox',array('required'=>false))
      ->add('save',      'submit')
      ->getForm();

      if($request->isMethod('POST') && $form->isValid()){
        $request->getSession()->getFlashBag()->add('notice','Annonce bien modifiée.');
        return $this->redirect($this->generateUrl('bg_platform_view', array('id' => $advert->getId())));    
      }
      return $this->render('BGPlatformBundle:Advert:edit.html.twig', array(
        'form' => $form->createView(),
        'advert' =>$advert
        ));
    }

    public function deleteAction($id){
      $em = $this->getDoctrine()->getManager();
      $advert = $em->getRepository('BGPlatformBundle:Advert')->find($id);

      if (null === $advert) {
        return $this->render("BGPlatformBundle:Advert:notFound.html.twig");
      }

    // On boucle sur les catégories de l'annonce pour les supprimer
      foreach ($advert->getCategories() as $category) {
        $advert->removeCategory($category);
      }

    // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
    // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine

    // On déclenche la modification
      $em->flush();
      return $this->render('BGPlatformBundle:Advert:delete.html.twig');
    }

    public function menuAction(){
      $repo = $this->getDoctrine()->getManager()->getRepository("BGPlatformBundle:Advert");
      $listAdverts = $repo->find3last();

        return $this->render('BGPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }
}
