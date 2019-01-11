<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Auteur;
use AppBundle\Entity\Livre;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LivreController extends FOSRestController
{
    /**
     * @Rest\Get("/books/")
     */
    public function livresAction(Request $request)
    {
        $result = $this->getDoctrine()->getRepository(Livre::class)->findAll();
        if ($result == null) {
            return new View("There Are No Books", Response::HTTP_NOT_FOUND);
        }
        return $result;
    }

    /**
     * @Rest\Get("/books/{s}/")
     */
    public function chercherAction($s, Request $request)
    {
        $result = $this->getDoctrine()->getRepository(Livre::class)->findAll();
        if ($result == null) {
            return new View("There Are No Books", Response::HTTP_NOT_FOUND);
        }
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository(Livre::class)->createQueryBuilder('b')
            ->where('b.titre LIKE :titre')
            ->setParameter('titre', '%' . $s . '%')
            ->getQuery()
            ->getResult();
        return $result;
    }

    /**
     * @Rest\Get("/books/{id}/")
     */
    public function bookAction($id, Request $request)
    {

        //$title = $request->get('title');
        $result = $this->getDoctrine()->getRepository(Livre::class)->findOneById($id);

        if ($result == null) {
            return new View("There Are No Books", Response::HTTP_NOT_FOUND);
        }
        return $result;
    }

    /**
     * @Rest\Post("/books/")
     */
    public function addTask(Request $request)
    {

        $titre = $request->get('titre');
        $auteur = $request->get('auteur');
        if (empty($titre) || empty($titre)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $em = $this->getDoctrine()->getManager();
        $auteurAdd = new Auteur();
        $auteurAdd->setNomPrenom($auteur);
        $auteurAdd->setEmail($auteur . '@gmail.com');
        $em->persist($auteurAdd);
        $livre = new Livre();
        $livre->setTitre($titre);
        $livre->setDescriptif("description");
        $livre->setISBN(mt_rand());
        $livre->setDateEdition(new \DateTime());
        $livre->setAuteur($auteurAdd);
        $em->persist($livre);
        $em->flush();
        return new View("Book Added Successfully", Response::HTTP_ACCEPTED);

    }

    /**
     * @Rest\Put("/books/{id}/")
     */

    public function updateAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $livre = $em->getRepository(Livre::class)->find($id);
        $livre->setTitre($request->get('titre'));
        $livre->setDescriptif($request->get('description'));
        $livre->setISBN($request->get('isbn'));
        $livre->setDateEdition(new \DateTime());

        
        $em->persist($livre);
        $em->flush();

        return new View("Book ".$id." Successfully Edited", Response::HTTP_ACCEPTED);

    }

    /**
     * @Rest\Patch("/books/{id}/")
     */

    public function modAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $livre = $em->getRepository(Livre::class)->find($id);
        $livre->setTitre($request->get('titre'));
        
        $em->persist($livre);
        $em->flush();

        return new View("Book ".$id." Successfully Edited", Response::HTTP_ACCEPTED);

    }

    /**
     * @Rest\Delete("/books/{id}/")
     */

    public function effAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $livre = $em->getRepository(Livre::class)->find($id);
        $em->remove($livre);
        $em->flush();

        return new View("Book ".$id." Successfully Deleted", Response::HTTP_ACCEPTED);

    }

}