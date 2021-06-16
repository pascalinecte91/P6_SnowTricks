<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Comment;
use App\Entity\Picture;
use App\Form\TrickType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManager;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\UploaderFileServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            
            $slugger = new AsciiSlugger();
            $trick->getName();
            $slug = $slugger->slug($trick->getName());
            $trick->setSlug($slug);


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
            
            $this->addFlash(
                'success',
                'Le nouveau trick <strong>' . $trick->getName()  . '</strong> a été crée .'
            );
            
            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{slug}", name="trick_show", methods={"GET","POST"})
     */
    public function show(Request $request, Trick $trick, PaginatorInterface $paginator): Response
    {

        $comments = $paginator->paginate(  // je pagine : si pas de page 1  et oui si page + 1 jusqu' 4
            $trick->getComments(),
            $request->query->getInt('page', 1),  //  pour la page 1 si ya page d'autres pages
            5
        );

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

            $this->addFlash(
                    'success',
                    ' Votre commentaire est publié !'
                );

            return $this->redirectToRoute('trick_index', ['id' => $trick->getId()]);
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $commentForm->createView(),
            'comments' => $comments,
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

            $slugger = new AsciiSlugger();
            $trick->getName();
            $slug = $slugger->slug($trick->getName());
            $trick->setSlug($slug);


            $trick->setUpdateAt(new \DateTime());

            $pictures = $trick->getPictures();
            // faire une boucle pour plusieurs images
            foreach ($pictures as $picture) {
                if ($picture->getId() != null) {
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

            $this->addFlash(
                'success',
                'Le trick <strong>' . $trick->getName()  . '</strong> a bien eté modifié avec succès.'
            );

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}/delete", name="trick_delete", methods={"POST"})
     */
    public function delete(Request $request, Trick $trick): Response
    {

        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trick);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le trick <strong>' . $trick->getName()  . '</strong> a bien eté  supprimé.'
            );

            return $this->redirectToRoute('trick_index');
        }
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
