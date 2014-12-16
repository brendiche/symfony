<?php

namespace BG\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{
    public function indexAction()
    {
    	$content = $this->get('templating')->render('BGPlatformBundle:Advert:index.html.twig',array('nom'=>'Brendan'));
        return new Response($content);
    }
}
