<?php

declare(strict_types=1);

namespace Linio\Common\Symfony\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Templating\EngineInterface;

class TemplatingAwareTest extends TestCase
{
    public function testIsSettingTemplating(): void
    {
        $templating = $this->prophesize(EngineInterface::class);

        $controller = new class() {
            use TemplatingAware;

            public function test()
            {
                return $this->getTemplating();
            }
        };
        $controller->setTemplating($templating->reveal());

        $actual = $controller->test();

        $this->assertInstanceOf(EngineInterface::class, $actual);
    }

    public function testIsRenderingView(): void
    {
        $templating = $this->prophesize(EngineInterface::class);
        $templating->render('view', ['parameters' => 'parameters'])
            ->willReturn('rendered_view');

        $controller = new class() {
            use TemplatingAware;

            public function test($view, $parameters)
            {
                return $this->renderView($view, $parameters);
            }
        };
        $controller->setTemplating($templating->reveal());

        $actual = $controller->test('view', ['parameters' => 'parameters']);

        $this->assertSame('rendered_view', $actual);
    }

    public function testIsRenderingAResponse(): void
    {
        $templating = $this->prophesize(EngineInterface::class);
        $templating->render('view', ['parameters' => 'parameters'])
            ->shouldBeCalled()
            ->willReturn('rendered_view');

        $controller = new class() {
            use TemplatingAware;

            public function test($view, $parameters)
            {
                return $this->render($view, $parameters);
            }
        };
        $controller->setTemplating($templating->reveal());

        $actual = $controller->test('view', ['parameters' => 'parameters']);

        $this->assertInstanceOf(Response::class, $actual);
        $this->assertSame('rendered_view', $actual->getContent());
    }

    public function testIsStreaming(): void
    {
        $templating = $this->prophesize(EngineInterface::class);

        $controller = new class() {
            use TemplatingAware;

            public function test($view, $parameters)
            {
                return $this->stream($view, $parameters);
            }
        };
        $controller->setTemplating($templating->reveal());

        $actual = $controller->test('view', ['parameters' => 'parameters']);

        $this->assertInstanceOf(StreamedResponse::class, $actual);
    }
}
