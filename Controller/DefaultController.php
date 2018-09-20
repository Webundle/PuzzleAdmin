<?php

namespace Puzzle\Admin\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PuzzleAdminBundle:Default:index.html.twig');
    }
    
    public function settingsAction()
    {
        return $this->render('PuzzleAdminBundle:Default:settings.html.twig');
    }
}
