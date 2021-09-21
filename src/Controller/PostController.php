<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post', name: 'post_')]
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();
        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $post = new Post();
        $post->setTitle('Variable');

        $em = $this->getDoctrine()->getManager();

        $em->persist($post);
        $em->flush();

        return new Response('Post was created');
    }

    #[Route('/show/{id}', name: 'show')]
    public function show(int $id, PostRepository $postRepository)
    {
        $post = $postRepository->find($id);
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @param int $id
     * @param PostRepository $postRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(int $id, PostRepository $postRepository)
    {
        $post = $postRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Post was removed');

        return $this->redirect($this->generateUrl('post_post'));
    }
}
