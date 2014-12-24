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
      // $em = $this->getDoctrine()->getManager();
      // $advert = new Advert();
      // $advert->setTitle('Recherche développpeur Symfony2');
      // $advert->setAuthor('Alexandre');
      // $advert->setContent('Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…');

      // $listSkills = $em->getRepository('BGPlatformBundle:Skill')->findAll();

      // foreach ($listSkills as $skill) {
      //   // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
      //   $advertSkill = new AdvertSkill();
      //   $advertSkill->setAdvert($advert);
      //   $advertSkill->setSkill($skill);
      //   $advertSkill->setLevel('Expert');
      //   $em->persist($advertSkill);
      // }
      // $em->persist($advert);
      // $em->flush();
        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('notice','Annonce bien enregistrée.');
            return $this->redirect($this->generateUrl('bg_platform_view', array('id' => $advert->getId())));    
        }
        return $this->render('BGPlatformBundle:Advert:add.html.twig');
    }

    public function editAction($id,Request $request){
      $em = $this->getDoctrine()->getManager();
      $advert = $em->getRepository('BGPlatformBundle:Advert')->find($id);
      if (null === $advert) {
        return $this->render("BGPlatformBundle:Advert:notFound.html.twig");
      }

      $listCategories = $em->getRepository('BGPlatformBundle:Category')->findAll();

    // On boucle sur les catégories pour les lier à l'annonce
      foreach ($listCategories as $category) {
        $advert->addCategory($category);
      }
      $em->flush();

      if($request->isMethod('POST')){
        $request->getSession()->getFlashBag()->add('notice','Annonce bien modifiée.');
        return $this->redirect($this->generateUrl('bg_platform_view', array('id' => 5)));    
      }
      return $this->render('BGPlatformBundle:Advert:edit.html.twig', array(
        'advert' => $advert
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
