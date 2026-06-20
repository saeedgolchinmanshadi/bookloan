<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * @return list<string> An array of allowed values for APP_ENV
     */
    private function getAllowedEnvs(): array
    {
        return ['prod', 'dev', 'test'];
    }
    
    public function getCacheDir(): string
    {
        if ($this->environment === 'dev') {
            return '/tmp/symfony/cache/' .  $this->environment;
        }
        return parent::getCacheDir();
    }

    public function getLogDir(): string
    {
        if ($this->environment === 'dev') {
            return '/tmp/symfony/log';
        }
        return parent::getLogDir();
    }
}
