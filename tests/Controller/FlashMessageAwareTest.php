<?php

declare(strict_types=1);

namespace Linio\Common\Symfony\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;

class FlashMessageAwareTest extends TestCase
{
    public function testIsSettingSession(): void
    {
        $session = $this->prophesize(Session::class);

        $controller = new class() {
            use FlashMessageAware;

            public function test()
            {
                return $this->getSession();
            }
        };
        $controller->setSession($session->reveal());

        $actual = $controller->test();

        $this->assertInstanceOf(Session::class, $actual);
    }

    public function testIsAddingFlashMessage(): void
    {
        $flashBag = new FlashBag();

        $session = $this->prophesize(Session::class);
        $session->getFlashBag()->willReturn($flashBag);

        $controller = new class() {
            use FlashMessageAware;

            public function test($severity, $message)
            {
                return $this->addFlash($severity, $message);
            }
        };
        $controller->setSession($session->reveal());

        $controller->test('notice', 'Foo bar');

        $this->assertSame(['Foo bar'], $flashBag->get('notice'));
    }
}
