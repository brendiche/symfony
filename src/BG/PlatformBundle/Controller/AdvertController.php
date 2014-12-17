<?php

namespace BG\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AdvertController extends Controller
{
    public function indexAction($page){

        if($page == null||$page <= 0){
             $page = 1;
        }
         $listAdverts = array(
      array(
        'title'   => 'Recherche développpeur Symfony2',
        'id'      => 1,
        'author'  => 'Alexandre',
        'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Mission de webmaster',
        'id'      => 2,
        'author'  => 'Hugo',
        'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Offre de stage webdesigner',
        'id'      => 3,
        'author'  => 'Mathieu',
        'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
        'date'    => new \Datetime())
    );
        return $this->render("BGPlatformBundle:Advert:index.html.twig",array("listAdverts"=>$listAdverts));
    }

    public function index01Action(){
    	$url = $this->get('router')->generate('bg_platform_view',array("id"=>10));
    	return $this->redirect($url);
    }

    public function viewAction($id){
        $advert = array(
          'title'   => 'Recherche développpeur Symfony2',
          'id'      => $id,
          'author'  => 'Alexandre',
          'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
          'date'    => new \Datetime()
        );

        return $this->render('BGPlatformBundle:Advert:view.html.twig', array('advert' => $advert));
    }

    public function viewSlugAction($year,$slug,$_format){

    	  return new Response(
            "On pourrait afficher l'annonce correspondant au
            slug '".$slug."', créée en ".$year." ,au format ".$_format
        );
    }
  
    public function addAction($id,Request $request){
        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('notice','Annonce bien enregistrée.');
            return $this->redirect($this->generateUrl('bg_platform_view', array('id' => 5)));    
        }
        return $this->render('BGPlatformBundle:Advert:add.html.twig');
    }

    public function editAction(Request $request){
        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('notice','Annonce bien modifiée.');
            return $this->redirect($this->generateUrl('bg_platform_view', array('id' => 5)));    
        }
        return $this->render('BGPlatformBundle:Advert:edit.html.twig');
    }

    public function deleteAction($id){
        return $this->render('BGPlatformBundle:Advert:delete.html.twig');
    }

    public function menuAction(){
        $listAdverts = array(
          array('id' => 2, 'title' => 'Recherche développeur Symfony2'),
          array('id' => 5, 'title' => 'Mission de webmaster'),
          array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );

        return $this->render('BGPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }
}
