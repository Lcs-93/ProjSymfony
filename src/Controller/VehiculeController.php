<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

#[Route('/vehicule')]
class VehiculeController extends AbstractController
{
    // Affiche la liste des véhicules avec un formulaire de recherche
    #[Route('/', name: 'app_vehicule_index', methods: ['GET', 'POST'])]
    public function index(Request $request, VehiculeRepository $vehiculeRepository): Response
    {
        // Crée un formulaire de recherche
        $form = $this->createFormBuilder()
            ->add('marque', TextType::class, ['required' => false])
            ->add('prixMax', NumberType::class, ['required' => false])
            ->add('disponible', CheckboxType::class, ['required' => false])
            ->getForm();

        $form->handleRequest($request);

        // Récupère tous les véhicules par défaut
        $vehicules = $vehiculeRepository->findAll();

        // Si le formulaire est soumis et valide, applique les filtres
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $vehicules = $vehiculeRepository->findByFilters($data);
        }

        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehicules,
            'form' => $form->createView(),
        ]);
    }

    // Affiche le formulaire pour ajouter un nouveau véhicule
    #[Route('/new', name: 'app_vehicule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vehicule);
            $entityManager->flush();

            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicule/new.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form->createView(),
        ]);
    }

    // Affiche les détails d'un véhicule spécifique
    #[Route('/{id}', name: 'app_vehicule_show', methods: ['GET'])]
    public function show(Vehicule $vehicule): Response
    {
        return $this->render('vehicule/show.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }

    // Affiche le formulaire pour modifier un véhicule existant
    #[Route('/{id}/edit', name: 'app_vehicule_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicule/edit.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form->createView(),
        ]);
    }

    // Supprime un véhicule
    #[Route('/{id}', name: 'app_vehicule_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicule->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vehicule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
    }
}