<?php

namespace BG\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AdvertController extends Controller
{
    public function indexAction($page){

        // if($page < 1){
        //     throw new NotFoundException("Page ".$page." inexistante.");
        // }
        return $this->render("BGPlatformBundle:Advert:index.html.twig",array("page"=>$page));
    }
    public function index01Action(){
    	$url = $this->get('router')->generate('bg_platform_view',array("id"=>10));
    	return $this->redirect($url);
    }
    public function viewAction($id){

    	return $this->render('BGPlatformBundle:Advert:view.html.twig',array('id'=>$id));
    }
    public function viewSlugAction($year,$slug,$_format){

    	  return new Response(
            "On pourrait afficher l'annonce correspondant au
            slug '".$slug."', créée en ".$year." ,au format ".$_format
        );
    }
  
  public function addAction(Request $request)
  {
   $session = $request->getSession();
    
    // Bien sûr, cette méthode devra réellement ajouter l'annonce
    
    // Mais faisons comme si c'était le cas
    $session->getFlashBag()->add('info', 'Annonce bien enregistrée');
    // // Le « flashBag » est ce qui contient les messages flash dans la session
    // // Il peut bien sûr contenir plusieurs messages :
    $session->getFlashBag()->add('info', 'Oui oui, il est bien enregistré !');
    // Puis on redirige vers la page de visualisation de cette annonce
    return $this->redirect($this->generateUrl('bg_platform_view', array('id' => 5)));
  }
}
