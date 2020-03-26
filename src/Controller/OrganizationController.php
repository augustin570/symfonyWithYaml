<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Form\OrganizationType;
use App\Repository\OrganizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/organization")
 */
class OrganizationController extends AbstractController
{
    /**
     * @Route("/", name="organization_index", methods={"GET"})
     */
    public function index(OrganizationRepository $organizationRepository): Response
    {
        return $this->render('organization/index.html.twig', [
            'organizations' => $organizationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="organization_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $organization = new Organization();
        $form = $this->createForm(OrganizationType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($organization);
            $entityManager->flush();

            return $this->redirectToRoute('organization_index');
        }

        return $this->render('organization/new.html.twig', [
            'organization' => $organization,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{name}/edit", name="organization_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OrganizationRepository $organizationRepository, string $name): Response
    {
        $organization = $organizationRepository->findOneByName($name);

        if ($organization === null) {
            return $this->redirectToRoute('organization_index');
        }

        $form = $this->createForm(OrganizationType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            return $this->redirectToRoute('organization_index');
        }

        return $this->render('organization/edit.html.twig', [
            'organization' => $organization,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{name}", name="organization_delete", methods={"DELETE"})
     */
    public function delete(Request $request, OrganizationRepository $organizationRepository, string $name): Response
    {
        $organization = $organizationRepository->findOneByName($name);

        if ($organization === null) {
            return $this->redirectToRoute('organization_index');
        }

        if ($this->isCsrfTokenValid('delete'.$name, $request->request->get('_token'))) {
            $organizationRepository->removeByName($name);
        }

        return $this->redirectToRoute('organization_index');
    }
}
