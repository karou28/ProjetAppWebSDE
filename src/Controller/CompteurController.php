<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Compteur;
use App\Repository\CompteurRepository;
use App\Repository\FactureRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;


class CompteurController extends AbstractController
{
   /**
     * @Route("/", name="home")
     */
    public function homePage(CompteurRepository $repo)
    {

        $compteurs = $repo->findAll();
        return $this->render('home.html.twig');
    }
     
    //Recuperer les factures au format JSON
    /**
     * @Route("/getFacture", name="compteur")
     */
    public function index(FactureRepository $repo1)
    {
        $factures = $repo1->findAll();
        //$tab = [];
        $_tab = [];
        $facture = [];
        foreach($factures as $tab)
        {
            $_tab['id'] = $tab->getId(); 
            $_tab['numero'] = $tab->getNumero();
            $facture[] = $_tab;
        }
        return new JsonResponse($facture);
       

    }

     

     /**
     * @Route("/findCompteurById/{id}", name="findCompteurById")
     */
    public function FctfindCompteurById(CompteurRepository $repo1,$id)
    {
        $compteurs = $repo1->findAll();
        $_tab = [];
        $compteur = [];
        foreach($compteurs as $tab)
        {
            if($tab->getId()==$id){
                $_tab['id'] = $tab->getId(); 
                $_tab['numero'] = $tab->getNumero();
                $compteur[] = $_tab;
            }
            
        }
        return new JsonResponse($compteur);
    }

  

   
    /**
     * @Route("/compteurs", name="listCompteurs")
     */
    public function listCompteurs(CompteurRepository $repo)
    {

        $compteurs = $repo->findAll();
        return $this->render('compteur/listCompteur.html.twig', [
            'controller_name' => 'SDE - Compteur',
            'compteurs' => $compteurs
        ]);
    }

   

     /**
     * @Route("/compteurs/add", name="addCompteur")
     * @Route("/compteurs/{id}/edit", name="editCompteur")
     */
    public function addCompteur(Compteur $compteur=null,Request $request, ObjectManager $manager)
    {
        if(!$compteur){
            $compteur = new Compteur();
        }
        
        
        $form = $this->createFormBuilder($compteur)
                     
                     ->add('numero')
                     ->getForm();
        
        $form->handleRequest($request);
        dump($compteur);

        if($form->isSubmitted()){
           // $abb->setCre(new \DateTime());
           $manager->persist($compteur);
           $manager->flush();

           return $this->redirectToRoute('listCompteurs');
        }

        return $this->render('compteur/addCompteur.html.twig', [
            'formCompteur' => $form->createView(),
            'controller_name' => 'SDE - Compteur',
            'editMode' => $compteur->getId() !== null
        ]);
    }

    /**
     * @Route("/compteur/{id}", name="showCompteur")
     */
    public function showCompteur(Compteur $compteur)
    {

        return $this->render('compteur/showCompteur.html.twig', [
            'compteur' => $compteur,
            'controller_name' => 'SDE - Compteur',
        ]);
    }

  

    

}
