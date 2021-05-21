<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Entity\Picture;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use App\Service\UploaderFileServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/trick")
 */
class TrickController extends AbstractController
{
    private UploaderFileServiceInterface $uploaderFileService;
    private EntityManagerInterface $entityManager;

    public function __construct(UploaderFileServiceInterface $uploaderFileService, EntityManagerInterface $entityManager)
    {
        $this->uploaderFileService = $uploaderFileService;
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="trick_index", methods={"GET"})
     */
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="trick_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $trick = new Trick();
        $trick->setUser($this->getUser());
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // recuperation des images transmises
            $pictures = $trick->getPictures();

            // faire une boucle pour plusieurs images
            foreach ($pictures as $picture) {
                $filename = $this->uploaderFileService->upload($picture->getFile());
                $subtitle = $picture->getSubtitle();
                $picture->setName($filename);
                $picture->setSubtitle($subtitle);

                $this->entityManager->persist($picture);
                $trick->addPicture($picture);
            }
            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="trick_show", methods={"GET", "POST"})
     */
    public function show(Request $request, Trick $trick): Response
    {

        $user = $this->getUser();

        //  creation du commentaire vide
        $comment = new Comment();

        // generer le formulaire
        $commentForm = $this->createForm(CommentType::class, $comment);

        // handleRequest pour recuperer de la variable  les differents champs
        $commentForm->handleRequest($request);

        // envoi traitement formulaire : 
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {

            $comment->setTrick($trick);
            $comment->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            //  je fais mon flush  pour inscrire dans ma db
            $em->flush();

            $this->addFlash('message', ' Votre message est en attente de verification du moderateur');
            return $this->redirectToRoute('trick_index', ['id' => $trick->getId()]);
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $commentForm->createView(),
        ]);
    }
    /**
     * @Route("/{id}/edit", name="trick_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Trick $trick): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setUpdateAt(new \DateTime());

            $pictures = $trick->getPictures() ;
            // faire une boucle pour plusieurs images
            foreach ($pictures as $picture) {
                if ($picture->getId()!=null){
                    continue;
                }
                $filename = $this->uploaderFileService->upload($picture->getFile());
                $subtitle = $picture->getSubtitle();
                $picture->setName($filename);
                $picture->setSubtitle($subtitle);

                $this->entityManager->persist($picture);
                $trick->addPicture($picture);
            }
            $this->entityManager->flush();

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trick_delete", methods={"POST"})
     */
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trick_index');
    }
    /**
     * @Route("/delete/picture/{id}", name="trick_delete_picture", methods={"DELETE"})
     */
    public function deletePicture(Picture $picture, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        //verification du token  valid ou pas
        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $data['_token'])) {
            //je recupere le nom de l image
            $nom = $picture->getName();
            // puis on supprme l image avec le nom
            if (file_exists($this->getParameter('pictures_directory') . '/' . $nom)) {
                unlink($this->getParameter('pictures_directory') . '/' . $nom);
            }


            // suppression de la db
            $em = $this->getDoctrine()->getManager();

            $em->remove($picture);
            $em->flush();

            //reponse en json_decode
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}
