<?php

namespace BG\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{
    public function indexAction(){

    	$content = $this->get('templating')->render('BGPlatformBundle:Advert:index.html.twig',array('nom'=>'Brendan'));
        return new Response($content);
    }
    public function index01Action(){
    	$url = $this->get('router')->generate('bg_platform_view',array("id"=>10),true);
    	$content = $this->get('templating')->render('BGPlatformBundle:Advert:test.html.twig',array("advert_id"=>10));
    	// return new Response($url);
    	return new Response($content);
    }
    public function viewAction($id){

    	return new Response("Affichage de l'annonce avec l'id : ".$id);
    }
    public function viewSlugAction($year,$slug,$_format){

    	  return new Response(
            "On pourrait afficher l'annonce correspondant au
            slug '".$slug."', créée en ".$year." ,au format ".$_format
        );
    }
}
