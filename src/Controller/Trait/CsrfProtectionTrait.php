<?php

namespace App\Controller\Trait;

use Symfony\Component\HttpFoundation\Request;

trait CsrfProtectionTrait
{
    protected function isCsrfTokenValidOrFlash(string $tokenId, Request $request): bool
    {
        if ($this->isCsrfTokenValid($tokenId, $request->getPayload()->getString('_token'))) {
            return true;
        }

        $this->addFlash('danger', 'نشست شما منقضی شده یا توکن امنیتی نامعتبر است. لطفاً دوباره تلاش کنید.');

        return false;
    }
}
