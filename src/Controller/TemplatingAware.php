<?php

declare(strict_types=1);

namespace Linio\Common\Symfony\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Templating\EngineInterface;

trait TemplatingAware
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    protected function getTemplating(): EngineInterface
    {
        return $this->templating;
    }

    public function setTemplating(EngineInterface $templating): void
    {
        $this->templating = $templating;
    }

    public function renderView(string $view, array $parameters = []): string
    {
        return $this->templating->render($view, $parameters);
    }

    public function render(string $view, array $parameters = [], Response $response = null): Response
    {
        return $this->templating->renderResponse($view, $parameters, $response);
    }

    public function stream(string $view, array $parameters = [], StreamedResponse $response = null): StreamedResponse
    {
        $templating = $this->templating;

        $callback = function () use ($templating, $view, $parameters): void {
            $templating->stream($view, $parameters);
        };

        if ($response === null) {
            return new StreamedResponse($callback);
        }

        $response->setCallback($callback);

        return $response;
    }
}
