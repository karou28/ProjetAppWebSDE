<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Abonnement;
use App\Entity\Compteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\AbonnementRepository;
use App\Repository\CompteurRepository;
use App\Form\AbonnementType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

class AbonnementController extends AbstractController
{
    /**
     * @Route("/abonnements", name="listAbonnement")
     */
    public function index(AbonnementRepository $repo1)
    {
        $abonnements = $repo1->findAll();
        return $this->render('abonnement/listAbonnement.html.twig', [
            'controller_name' => 'SDE - Abonnement',
            'abonnements' => $abonnements
        ]);
    }

     /**
     * @Route("/abonnements/add", name="addAbonnement")
     * @Route("/abonnements/{id}/edit", name="editAbonnement")
     */
    public function abonnementsAdd(Abonnement $abnmt=null,Request $request, ObjectManager $manager,CompteurRepository $repo1)
    {
        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT compteur.id FROM `compteur` LEFT JOIN `abonnement` ON compteur.id = abonnement.compteur_id WHERE compteur_id IS NULL  " ;

        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $i = 0;
        while($row = $result->fetch()){
           
            $val = $row['id'];
            $cpts[$i] = $repo1->find($row['id']);
            $i++;   
        }
        //dump($cpts);
        if(!$abnmt){
            $abnmt = new Abonnement();
        }

        $form = $this->createFormBuilder($abnmt)      
                    ->add('contrat')
                    ->add('date')
                    ->add('cumulAncien')
                    ->add('cumulNouveau')
                    ->add('compteur', EntityType::class, [
                    'class' => Compteur::class,
                    'choices' => $cpts,
                    'choice_label' => 'numero',
                    'expanded' => false,
                    'multiple' => false
                    ])
                    ->getForm();

       
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
          
           $manager->persist($abnmt);
           $manager->flush();

           return $this->redirectToRoute('listAbonnement');
        }

        return $this->render('abonnement/addAbonnement.html.twig', [
            'formAbonnement' => $form->createView(),
            'controller_name' => 'SDE - Abonnement',
            'editMode' => $abnmt->getId() !== null
        ]);
    }

    /**
     * @Route("/abonnement/{id}", name="showAbonnement")
     */
    public function showAbonnement(Abonnement $abonnement)
    {

        return $this->render('abonnement/showAbonnement.html.twig', [
            'controller_name' => 'SDE - Abonnement',
            'abonnement' => $abonnement
        ]);
    }
   
}
