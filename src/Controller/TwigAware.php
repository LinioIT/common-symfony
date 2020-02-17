<?php

declare(strict_types=1);

namespace Linio\Common\Symfony\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Twig\Environment as Twig;

trait TwigAware
{
    /**
     * @var Twig
     */
    protected $twig;

    public function getTwig(): Twig
    {
        return $this->twig;
    }

    public function setTwig(Twig $twig): void
    {
        $this->twig = $twig;
    }

    public function renderView(string $view, array $context = []): string
    {
        return $this->twig->render($view, $context);
    }

    public function render(string $view, array $context = [], Response $response = null): Response
    {
        $content = $this->twig->render($view, $context);

        if ($response === null) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
    }

    public function stream(string $view, array $context = [], StreamedResponse $response = null): StreamedResponse
    {
        $twig = $this->twig;

        $callback = function () use ($twig, $view, $context): void {
            $twig->display($view, $context);
        };

        if ($response === null) {
            return new StreamedResponse($callback);
        }

        $response->setCallback($callback);

        return $response;
    }
}
