<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    private $passwordEncoder;
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         //if ($this->getUser()) {
           //  return $this->redirectToRoute('listaPersonas');
         //}

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    
    /**
     * @Route("/registroUsuario", name="registroUsuario")
     */
    public function registroUsuario(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $formulario = $this->createForm(UserType::class,$user);
        $formulario -> handleRequest($request);
        
        if ($formulario->isSubmitted() && $formulario->isValid()){
            
            $user->setRoles($user->getRoles());
            $user->setPassword($passwordEncoder->encodePassword($user,$formulario['password']->getData()));
            $entManager = $this->getDoctrine()->getManager();
            $entManager->persist($user);
            $entManager->flush();
            
            return $this->redirectToRoute('listaPersonas');
            
        }
        return $this->render('security/registroUsuario.html.twig', [
            'formulario' => $formulario->createView()
        ]);
    }
    
    

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
