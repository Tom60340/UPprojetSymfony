<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Article;

class ArticleController extends AbstractController
{
    /**
     * Visualiser un article
     * 
     * @param int $id Identifiant de l'article
     *
     * @return Response
     */
    public function index(int $id): Response
    {   
        //Entity Manager de Symfony
        $em = $this->getDoctrine()->getMAnager();
        // On récupère l'article qui correspond à l'id passé dans l'URL
        $article = $em->getRepository(Article::class)->findBy(['id'=> $id]);

        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * Modifier / ajouter un article
     * 
     */
    public function edit(Request $request, int $id=null): Response
    {
        //Entity Manager de Symfony
        $em = $this->getDoctrine()->getManager();

        if($id){
            $mode = 'update';
            $article = $em->getRepository(Article::class)->findBy(['id' => $id]);
        }
        else{
            $mode = 'new';
            $article = new Article();
        }

        $form = $this->createForm(Article::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->saveArticle($article, $mode);

            return $this->redirectToRoute('article_edit',array('id' => $article->getId()));
        }

        $parameters = array(
            'form' => $form,
            'article' => $article,
            'mode' => $mode
        );

        return $this->render('article/edit.html.twig', $parameters);
    }


    /**
     * Compléter l'article avec des informations avant enregistrement
     * 
     * @param Article $article
     * @param string $mode
     * 
     * @return Article
     */
    private function completeArticleBeforeSave(Article $article, string $mode){
        if($article->getIsPublished()){
            $article->setPublishedAt(new \DateTime());
        }
        $article->setAuthor($this->getUser());

        return $article;
    }



    /**
     *  Supprimer un article
     * 
     */
    public function remove(int $id): Response
    {
        //Entity Manager de Symfony
        $em = $this->getDoctrine()->getManager();
        //On récupère l'article qui correspond à l'id passé dans l'URL
        $article = $em->getRepository()(Article::class)->findBy(['id => $id'])[0];

        //Suppression de l'article
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }


    /**
     * Enregistrer un article en base de données
     * 
     * @param Article $article
     * @param string $mode
     */
    private function saveArticle(Article $article, string $mode){
        $article = $this->completeArticleBeforeSave($article, $mode);

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();
    }

}