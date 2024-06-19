<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use OpenAI;
use OpenAI\Client;
use OpenAI\Factory;
use Symfony\Component\HttpClient\Psr18Client;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('openai.http_client', Psr18Client::class)
        ->arg(0, service('http_client'))

        ->set(Factory::class)
        ->factory([OpenAI::class, 'factory'])
        ->call('withHttpClient', [service('openai.http_client')])
        ->call('withHttpHeader', ['OpenAI-Beta', 'assistants=v2'])

        ->set(Client::class)
        ->factory([service(Factory::class), 'make'])

        ->alias('openai', Client::class);
};
