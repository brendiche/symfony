<?php

namespace BG\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BGPlatformBundle:Default:index.html.twig', array('name' => $name));
    }
}
