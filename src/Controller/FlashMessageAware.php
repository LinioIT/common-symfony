<?php

declare(strict_types=1);

namespace Linio\Common\Symfony\Controller;

use Symfony\Component\HttpFoundation\Session\Session;

trait FlashMessageAware
{
    /**
     * @var Session
     */
    protected $session;

    protected function getSession(): Session
    {
        return $this->session;
    }

    public function setSession(Session $session): void
    {
        $this->session = $session;
    }

    /**
     * Adds a flash message to the current session for type.
     *
     * @param string $severity ['success', 'notice', 'warning', 'error']
     * @param string $message
     */
    protected function addFlash(string $severity, string $message): void
    {
        $this->session->getFlashBag()->add($severity, $message);
    }
}
