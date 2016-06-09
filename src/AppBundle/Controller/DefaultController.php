<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    public function displayMenuAction(Request $request) {
        $menu = $this->getDoctrine()
            ->getRepository('AppBundle:Menu')
            ->findBy(array('parent' => null));

        $final_menu = array();
        foreach ($menu as $item) {
            $final_menu[$item->getId()] = array(
                'item' => $item
            );

            $final_menu[$item->getId()]["children"] = $item->getChildren();
        }

        return $this->render('default/menu_items.html.twig', array(
            'menu' => $final_menu,
        ));
    }

    /**
     * @Route("/{content_slug}", name="content_show")
     */
    public function showContentAction($content_slug) {

        $content = $this->getDoctrine()
            ->getRepository('AppBundle:Content')
            ->findOneBy(array("slug" => $content_slug));

        if (!$content) {
            throw $this->createNotFoundException('A keresett oldal nem található!');
        }

        return $this->render('default/content.html.twig', array(
            'content' => $content,
        ));
    }

    /**
     * @Route("/menu/{menu_id}", name="menu_show")
     */
    public function showMenuAction($menu_id) {

        $menu = $this->getDoctrine()
            ->getRepository('AppBundle:Menu')
            ->find($menu_id);

        //$item = explode(":", $menu->getItem());
        if (is_null($menu->getItem())) {
            return $this->redirectToRoute('homepage');
        }

        $content = $this->getDoctrine()
            ->getRepository('AppBundle:Content')
            ->find($menu->getItem());

        return $this->redirectToRoute('content_show', array('content_slug' => $content->getSlug()));
    }
}
