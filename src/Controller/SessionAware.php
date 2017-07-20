<?php

declare(strict_types=1);

namespace Linio\Common\Symfony\Controller;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

trait SessionAware
{
    /**
     * @var SessionInterface
     */
    protected $session;

    protected function getSession(): SessionInterface
    {
        return $this->session;
    }

    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }
}
