<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Persona;
use App\Form\PersonaType;
use Symfony\Component\HttpFoundation\Request;

class PersonaController extends AbstractController
{
    /**
     * @Route("/registroPersona", name="registro")
     */
    public function index(Request $request)
    {
        //Instancia de persona.
        $persona = new Persona();
        
        //Cargamos el formulario.
        $formulario = $this->createForm(PersonaType::class,$persona);
        $formulario -> handleRequest($request);
        
        if ($formulario->isSubmitted() && $formulario->isValid()){
            
            $entManager = $this->getDoctrine()->getManager();
            $entManager->persist($persona);
            $entManager->flush();
            
            return $this->render('persona/success.html.twig',
                ['persona' => $persona]
            );
            
        }        
        
        return $this->render('persona/index.html.twig', [
            'formulario' => $formulario->createView()
        ]);
    }
    
    /**
     * @Route("/listarPersonas", name="listaPersonas")
     */
    
    public function listarPersonas(Request $request)
    {
        $manager=$this->getDoctrine()->getManager();
        $form = $this->createForm(PersonaType::class,new Persona());
        $form->handleRequest($request);
        
        $personas= $manager->getRepository(Persona::class)->findAll();
        
        return $this->render('persona/listaPersonas.html.twig',
                ['personas' => $personas]
            );
    }
    
    /**
     * @Route("/modificarPersona/{id}", name="modificarPersona")
     */
    
    public function modificarPersona(Request $request, $id)
    {
        $manager=$this->getDoctrine()->getManager();
        
        $persona= $manager->getRepository(Persona::class)->find($id);
        
        $form = $this->createForm(PersonaType::class,$persona);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            
            $manager->flush();
            
            return $this->listarPersonas($request);
            
        }
        
        return $this->render('persona/modificarPersona.html.twig',
                ['formulario' => $form->createView()]
            );
    }
    
    /**
     * @Route("/eliminarPersona/{id}", name="eliminarPersona")
     */
    
    public function eliminarPersona(Request $request, $id)
    {
        $manager=$this->getDoctrine()->getManager();
        
        $form = $this->createForm(PersonaType::class,new Persona());
        $form->handleRequest($request);
        
        $persona= $manager->getRepository(Persona::class)->find($id);
       
        $manager->remove($persona);
        $manager->flush();
        
        return $this->listarPersonas($request);
        
    }
    
}
