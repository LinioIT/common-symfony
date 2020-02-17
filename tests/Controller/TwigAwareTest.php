<?php

declare(strict_types=1);

namespace Linio\Common\Symfony\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Twig\Environment;

class TwigAwareTest extends TestCase
{
    public function testIsSettingTwig(): void
    {
        $twig = $this->prophesize(Environment::class);

        $controller = new class() {
            use TwigAware;

            public function test()
            {
                return $this->getTwig();
            }
        };
        $controller->setTwig($twig->reveal());

        $actual = $controller->test();

        $this->assertInstanceOf(Environment::class, $actual);
    }

    public function testIsRenderingView(): void
    {
        $twig = $this->prophesize(Environment::class);
        $twig->render('view', ['parameters' => 'parameters'])
            ->willReturn('rendered_view');

        $controller = new class() {
            use TwigAware;

            public function test($view, $parameters)
            {
                return $this->renderView($view, $parameters);
            }
        };
        $controller->setTwig($twig->reveal());

        $actual = $controller->test('view', ['parameters' => 'parameters']);

        $this->assertSame('rendered_view', $actual);
    }

    public function testIsRenderingAResponse(): void
    {
        $twig = $this->prophesize(Environment::class);
        $twig->render('view', ['parameters' => 'parameters'])
            ->shouldBeCalled()
            ->willReturn('rendered_view');

        $controller = new class() {
            use TwigAware;

            public function test($view, $parameters)
            {
                return $this->render($view, $parameters);
            }
        };
        $controller->setTwig($twig->reveal());

        $actual = $controller->test('view', ['parameters' => 'parameters']);

        $this->assertInstanceOf(Response::class, $actual);
        $this->assertSame('rendered_view', $actual->getContent());
    }

    public function testIsStreaming(): void
    {
        $twig = $this->prophesize(Environment::class);

        $controller = new class() {
            use TwigAware;

            public function test($view, $parameters)
            {
                return $this->stream($view, $parameters);
            }
        };
        $controller->setTwig($twig->reveal());

        $actual = $controller->test('view', ['parameters' => 'parameters']);

        $this->assertInstanceOf(StreamedResponse::class, $actual);
    }
}
