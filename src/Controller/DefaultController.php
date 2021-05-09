<?php

namespace App\Controller;

use App\Exception\BookException;
use App\Helper\DtoHelper;
use App\Model\DefaultModel;
use ImagickException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/book/{filename}", name="get_book", methods={"GET"})
     * @param Request $request
     * @param string $filename
     * @return Response
     * @throws BookException
     */
    public function getBookAction(Request $request, string $filename)
    {
        // Processing
        $bookData = DefaultModel::getBookData($filename);

        // Response
        return new Response($bookData['data'], Response::HTTP_OK, [
            'Content-Type' => 'application/' . $bookData['extension'],
        ]);
    }

    /**
     * @Route("/cover/{filename}", name="get_cover", methods={"GET"})
     * @param Request $request
     * @param string $filename
     * @return Response
     * @throws BookException
     * @throws ImagickException
     */
    public function getCoverAction(Request $request, string $filename)
    {
        // Processing
        $coverData = DefaultModel::getCoverData($filename);

        // Response
        return new Response($coverData['data'], Response::HTTP_OK, [
            'Content-Type' => 'image/jpeg'
        ]);
    }

    /**
     * @Route("/list", name="list_all_books", methods={"GET"})
     * @param Request $request
     * @return \App\Dto\Response\Response
     */
    public function listAllBooksAction(Request $request)
    {
        // Processing
        $data = DefaultModel::listAllBooks();

        // Response
        return DtoHelper::createResponseDto(Response::HTTP_OK, $data, []);
    }
}
