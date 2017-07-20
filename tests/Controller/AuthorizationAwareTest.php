<?php

declare(strict_types=1);

namespace Linio\Common\Symfony\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AuthorizationAwareTest extends TestCase
{
    public function testIsSettingAuthorizationChecker()
    {
        $authorizationChecker = $this->prophesize(AuthorizationCheckerInterface::class);

        $controller = new class {
            use AuthorizationAware;

            public function test()
            {
                return $this->getAuthorizationChecker();
            }
        };
        $controller->setAuthorizationChecker($authorizationChecker->reveal());

        $actual = $controller->test();

        $this->assertInstanceOf(AuthorizationCheckerInterface::class, $actual);
    }

    public function testIsGrantedARoleIsTrue()
    {
        $authorizationChecker = $this->prophesize(AuthorizationCheckerInterface::class);
        $authorizationChecker->isGranted('ROLE_TEST', null)->willReturn(true);

        $controller = new class {
            use AuthorizationAware;

            public function test($role)
            {
                return $this->isGranted($role);
            }
        };
        $controller->setAuthorizationChecker($authorizationChecker->reveal());

        $actual = $controller->test('ROLE_TEST');

        $this->assertTrue($actual);
    }

    public function testIsGrantedARoleIsFalse()
    {
        $authorizationChecker = $this->prophesize(AuthorizationCheckerInterface::class);
        $authorizationChecker->isGranted('ROLE_TEST', null)->willReturn(false);

        $controller = new class {
            use AuthorizationAware;

            public function test($role)
            {
                return $this->isGranted($role);
            }
        };
        $controller->setAuthorizationChecker($authorizationChecker->reveal());

        $actual = $controller->test('ROLE_TEST');

        $this->assertFalse($actual);
    }

    public function testItDeniesAccessWhenNotGrantedRole()
    {
        $authorizationChecker = $this->prophesize(AuthorizationCheckerInterface::class);
        $authorizationChecker->isGranted('ROLE_TEST', null)->willReturn(false);

        $controller = new class {
            use AuthorizationAware;

            public function test($role)
            {
                return $this->denyAccessUnlessGranted($role);
            }
        };
        $controller->setAuthorizationChecker($authorizationChecker->reveal());

        $this->expectException(AccessDeniedException::class);
        $controller->test('ROLE_TEST');
    }

    public function testItAllowsAccessWhenGrantedRole()
    {
        $authorizationChecker = $this->prophesize(AuthorizationCheckerInterface::class);
        $authorizationChecker->isGranted('ROLE_TEST', null)->willReturn(true);

        $controller = new class {
            use AuthorizationAware;

            public function test($role)
            {
                return $this->denyAccessUnlessGranted($role);
            }
        };
        $controller->setAuthorizationChecker($authorizationChecker->reveal());

        $actual = $controller->test('ROLE_TEST');
        $this->assertNull($actual);
    }
}
