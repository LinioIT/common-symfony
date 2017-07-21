<?php

declare(strict_types=1);

namespace Linio\Common\Symfony\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionAwareTest extends TestCase
{
    public function testIsSettingSession(): void
    {
        $session = $this->prophesize(SessionInterface::class);

        $controller = new class() {
            use SessionAware;

            public function test()
            {
                return $this->getSession();
            }
        };
        $controller->setSession($session->reveal());

        $actual = $controller->test();

        $this->assertInstanceOf(SessionInterface::class, $actual);
    }
}
