<?php

namespace Yoda\EventBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Yoda\EventBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name, $count)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('EventBundle:Event');
        $event = $repo->findOneBy(array('name' => 'awesome event'));
        return $this->render('EventBundle:Default:index.html.twig', array(
            'name' => $name,
            'count' => $count,
            'event' => $event
        ));
//        $arr = array(
//            'status' => 'good',
//            'message' => 'Keep smiling'
//        );
//        $response = new Response(json_encode($arr));
//        $response -> headers -> set('Content-Type', 'application/json');
//        return $response;

    }
}
