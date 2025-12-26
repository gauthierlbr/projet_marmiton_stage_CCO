<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(Request $request, UtilisateurRepository $repository): Response
    {
        $utilisateurs = $repository->findAll();
        //dump($utilisateurs);
        return $this->render('login/login.html.twig',
        );
    }

    #[Route('/login/modifier', name: 'page_modifier')]
    public function edit(Request $request, UtilisateurRepository $repository, EntityManagerInterface $em){
        $utilisateurs = $repository->findOneBy(['id' => $request->get('Id')]);
        $form = $this->createForm(UtilisateurType::class, $utilisateurs);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', 'Les données personnelles ont bien été modifiées.');
        }
        return $this->render('login/edit.html.twig', [
            'utilisateur' => $utilisateurs,
            'form' => $form
        ]);
    }

    #[Route('/login/create', name: 'login.create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $utilisateur = new Utilisateur();
        $form =  $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
        //var_dump($form->isValid());
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($utilisateur);
            $em->flush();
            return $this->render('login/login.html.twig');
        }
        return $this->render('login/create.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/login/liste', name: 'login.liste')]
    public function createFunction(Request $request, UtilisateurRepository $repository,EntityManagerInterface $em): Response
    {
        if($request->get('action')== "delete"){
            $utilisateur= $repository->find($request->get('Id'));
            $em->remove($utilisateur);
            $em->flush();
        }

        $utilisateurs = $repository->findAll();
        return $this->render('login/liste.html.twig', [
            'liste' => $utilisateurs
        ]);
    }
}
