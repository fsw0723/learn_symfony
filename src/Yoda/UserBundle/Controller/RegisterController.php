<?php

namespace Yoda\UserBundle\Controller;

use Yoda\EventBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
use Yoda\UserBundle\Entity\User;
use Yoda\UserBundle\Form\RegisterFormType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegisterController extends Controller{
    /**
     * @Route("/register", name="register")
     *
     * @Template()
     */
    public function registerAction(Request $request){
        $defaultUser = new User();
        $defaultUser->setUsername('Foo');
        $form = $this->createForm(new RegisterFormType(), $defaultUser);

        if($request->isMethod('POST') ){
            $form->bind($request);
            if($form->isValid()){
                $user = $form->getData();
                $user->setPassword($this->encodePassword($user, $user->getPlainPassword()));
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->authenticateUser($user);

                $request->getSession()->getFlashBag()->add('success', 'Registration is a success');

                $url = $this->generateUrl('event');

                return $this->redirect($url);
            }
        }
        return array('form' => $form->createView());

    }


    private function encodePassword($user, $plainPassword)
    {
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

    private function authenticateUser(UserInterface $user)
    {
        $providerKey = 'secured_area'; // your firewall name
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

        $this->getSecurityContext()->setToken($token);
    }

}