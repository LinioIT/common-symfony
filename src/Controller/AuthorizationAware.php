<?php

declare(strict_types=1);

namespace Linio\Common\Symfony\Controller;

use LogicException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

trait AuthorizationAware
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    protected function getAuthorizationChecker(): AuthorizationCheckerInterface
    {
        return $this->authorizationChecker;
    }

    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker): void
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied object.
     *
     * @throws LogicException
     */
    protected function isGranted($attributes, $object = null): bool
    {
        return $this->authorizationChecker->isGranted($attributes, $object);
    }

    /**
     * Throws an exception unless the attributes are granted against the current authentication token and optionally
     * supplied object.
     *
     * @throws AccessDeniedException
     */
    protected function denyAccessUnlessGranted($attributes, $object = null, string $message = 'Access Denied.'): void
    {
        if (!$this->isGranted($attributes, $object)) {
            throw new AccessDeniedException($message);
        }
    }
}
