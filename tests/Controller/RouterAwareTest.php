<?php

declare(strict_types=1);

namespace Linio\Common\Symfony\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class RouterAwareTest extends TestCase
{
    use RouterAware;

    public function testIsSettingRouter()
    {
        $router = $this->prophesize(RouterInterface::class);

        $controller = new class {
            use RouterAware;

            public function test()
            {
                return $this->getRouter();
            }
        };
        $controller->setRouter($router->reveal());

        $actual = $controller->test();

        $this->assertInstanceOf(RouterInterface::class, $actual);
    }

    public function testIsGeneratingUrl()
    {
        $router = $this->prophesize(RouterInterface::class);
        $router->generate('route', ['parameters' => 'parameters'], RouterInterface::ABSOLUTE_PATH)
            ->willReturn('generated_url');

        $controller = new class {
            use RouterAware;

            public function test($route, $parameters, $referenceType)
            {
                return $this->generateUrl($route, $parameters, $referenceType);
            }
        };
        $controller->setRouter($router->reveal());

        $actual = $controller->test('route', ['parameters' => 'parameters'], RouterInterface::ABSOLUTE_PATH);

        $this->assertSame('generated_url', $actual);
    }

    public function testIsGeneratingUrlWithoutReferenceTypeParam()
    {
        $router = $this->prophesize(RouterInterface::class);
        $router->generate('route', ['parameters' => 'parameters'], RouterInterface::ABSOLUTE_PATH)
            ->willReturn('generated_url');

        $controller = new class {
            use RouterAware;

            public function test($route, $parameters)
            {
                return $this->generateUrl($route, $parameters);
            }
        };
        $controller->setRouter($router->reveal());

        $actual = $controller->test('route', ['parameters' => 'parameters']);

        $this->assertSame('generated_url', $actual);
    }

    public function testIsCreatingRedirectResponse()
    {
        $router = $this->prophesize(RouterInterface::class);

        $controller = new class {
            use RouterAware;

            public function test($url)
            {
                return $this->redirect($url);
            }
        };
        $controller->setRouter($router->reveal());

        $redirectUrl = 'http://test.local/redirect';

        $actual = $controller->test($redirectUrl);

        $this->assertInstanceOf(RedirectResponse::class, $actual);
        $this->assertSame($redirectUrl, $actual->getTargetUrl());
        $this->assertSame(302, $actual->getStatusCode());
    }

    public function testIsCreatingRedirectResponseWithCustomStatusCode()
    {
        $router = $this->prophesize(RouterInterface::class);

        $controller = new class {
            use RouterAware;

            public function test($url, $statusCode)
            {
                return $this->redirect($url, $statusCode);
            }
        };
        $controller->setRouter($router->reveal());

        $redirectUrl = 'http://test.local/redirect';

        $actual = $controller->test($redirectUrl, 302);

        $this->assertInstanceOf(RedirectResponse::class, $actual);
        $this->assertSame($redirectUrl, $actual->getTargetUrl());
        $this->assertSame(302, $actual->getStatusCode());
    }

    public function testIsCreatingRedirectResponseToRoute()
    {
        $router = $this->prophesize(RouterInterface::class);
        $router->generate('route', ['parameters' => 'parameters'], RouterInterface::ABSOLUTE_PATH)
            ->willReturn('generated_url');

        $controller = new class {
            use RouterAware;

            public function test($route, $parameters)
            {
                return $this->redirectToRoute($route, $parameters);
            }
        };
        $controller->setRouter($router->reveal());

        $actual = $controller->test('route', ['parameters' => 'parameters']);

        $this->assertInstanceOf(RedirectResponse::class, $actual);
        $this->assertSame('generated_url', $actual->getTargetUrl());
        $this->assertSame(302, $actual->getStatusCode());
    }

    public function testIsCreatingRedirectResponseToRouteWithCustomStatusCode()
    {
        $router = $this->prophesize(RouterInterface::class);
        $router->generate('route', ['parameters' => 'parameters'], RouterInterface::ABSOLUTE_PATH)
            ->willReturn('generated_url');

        $controller = new class {
            use RouterAware;

            public function test($route, $parameters, $statusCode)
            {
                return $this->redirectToRoute($route, $parameters, $statusCode);
            }
        };
        $controller->setRouter($router->reveal());

        $actual = $controller->test('route', ['parameters' => 'parameters'], 301);

        $this->assertInstanceOf(RedirectResponse::class, $actual);
        $this->assertSame('generated_url', $actual->getTargetUrl());
        $this->assertSame(301, $actual->getStatusCode());
    }
}
