<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class LoggedInUserListener
{
    private $router;
    private $security;

    public function __construct(RouterInterface $router, Security $security)
    {
        $this->router       = $router;
        $this->security     = $security;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request    = $event->getRequest();
        $path       = $request->getPathInfo();

        if ($this->security->getUser() && $this->isAnonymouslyPath($path)) {
            $response = new RedirectResponse($this->router->generate('document_index'));
            $event->setResponse($response);
        }
    }

    private function isAnonymouslyPath(string $path): bool
    {
        return preg_match('/^\/$|^\/register\/$/', $path);
    }
}
