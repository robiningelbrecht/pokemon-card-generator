<?php

namespace App\Controller;

use App\Domain\Card\CardRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class GalleryController
{
    public function __construct(
        private readonly CardRepository $cardRepository,
        private readonly Environment $twig,
    ) {
    }

    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
        $template = $this->twig->load('gallery.html.twig');
        $response->getBody()->write($template->render([
            'cards' => $this->cardRepository->findAll(),
        ]));

        return $response;
    }
}
