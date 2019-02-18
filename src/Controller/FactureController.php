<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Facture;
use App\Repository\FactureRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Compteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\AbonnementRepository;
use App\Repository\CompteurRepository;
use App\Form\FactureType;
use Symfony\Component\HttpFoundation\JsonResponse;



class FactureController extends AbstractController
{
    /**
     * @Route("/factures", name="listFactures")
     */
    public function listFactures(FactureRepository $repoFact)
    {
        
        return $this->render('facture/index.html.twig', [
            'controller_name' => 'SDE - Facture',
            'factures' => $repoFact->findAll()
        ]);

    }

      /**
     * @Route("/findFacturesForOneAbonnement/{id}", name="findFacturesForOneAbonnement")
     */
    public function findFacturesForOneAbonnement(FactureRepository $repo2,$id)
    {
    
        $factures = $repo2->findBy(array('abonnement' => $id));
        $_tab = [];
        $facture = [];
        foreach($factures as $tab)
        {   
                $_tab['id'] = $tab->getId(); 
                $_tab['abonnement'] = $id;
                $_tab['mois'] = $tab->getMois();
                $_tab['consommation'] = $tab->getConsommation();
                $_tab['prix'] = $tab->getPrix();
                $_tab['reglement'] = $tab->getReglement();
                $facture[] = $_tab;
            
        }
        return new JsonResponse($facture);
    }


     /**
     * @Route("/factures/add", name="addFacture")
     * @Route("/factures/{id}/edit", name="editFacture")
     */
    public function facturesAdd(Facture $facture=null,Request $request, ObjectManager $manager,CompteurRepository $repo1)
    {
        
        if(!$facture){
            $facture = new Facture();
        }

        $form = $this->createForm(FactureType::class, $facture)  ;   
                   

       
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            //On met à jour la table abonnement
            $cons = $facture->getConsommation() ;
            $abonn = $facture->getAbonnement();
            $idAbonn = $abonn->getId();
            dump($idAbonn);

            $em = $this->getDoctrine()->getManager();
            $sql = "UPDATE `abonnement` SET `cumul_ancien` = `cumul_nouveau`, `cumul_nouveau` = `cumul_nouveau`+ $cons WHERE `abonnement`.`id` = $idAbonn" ;
            $result = $em->getConnection()->prepare($sql);
            $result->execute();

            //On insere dans la table facture
            $manager->persist($facture);
            $manager->flush();
           

          return $this->redirectToRoute('listFactures');
        }

        return $this->render('facture/addFacture.html.twig', [
            'formFacture' => $form->createView(),
            'controller_name' => 'SDE - Facture',
            'editMode' => $facture->getId() !== null
        ]);
    }

    /**
     * @Route("/facture/{id}", name="showFacture")
     */
    public function showFacture(Facture $facture)
    {

        return $this->render('facture/showFacture.html.twig', [
            'facture' => $facture,
            'controller_name' => 'SDE - Facture',
        ]);
    }
    
}
