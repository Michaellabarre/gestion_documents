<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/document")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/", name="document_index", methods={"GET"})
     */
    public function index(DocumentRepository $documentRepository): Response
    {
        $documents = $this->getDoctrine()->getRepository(Document::class)->getAllowedDocuments($this->getUser());
        return $this->render('document/index.html.twig', [
            'documents' => $documents
        ]);
    }

    /**
     * @Route("/new", name="document_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $document = new Document();
        $document->setUser($this->getUser());
        
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $path = $this->getParameter('files_directory');
    
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $filePath = $path . '/'. $fileName;

            $file->move(
                $path,
                $fileName
            );

            $document->setFile($filePath);
            $document->setName($file->getClientOriginalName());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document);
            $entityManager->flush();

            return $this->redirectToRoute('document_index');
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Accès uniquement par le créateur du document
     * @Route("/edit/{id}", name="document_edit", methods={"GET","POST"})
     * @Security("document.isAuthor(user)")
     */
    public function edit(Request $request, Document $document): Response
    {
        $form = $this->createForm(DocumentType::class, $document, ['mode' => 'edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($document->getIsPublic()) {
                $document->clearAllowedUser();
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('document_index', [
                'id' => $document->getId(),
            ]);
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Accès uniquement par le créateur du document
     * @Route("/delete/{id}", name="document_delete", methods={"GET","DELETE"})
     * @Security("document.isAuthor(user)")
     */
    public function delete(Request $request, Document $document): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($document);
        $entityManager->flush();

        return $this->redirectToRoute('document_index');
    }

    /**
     * @Route("/download/{id}", name="document_download", methods={"GET","DELETE"})
     */
    public function download(Request $request, Document $document) : Response
    {
        return $this->file($document->getFile(), $document->getName());
    }
}
