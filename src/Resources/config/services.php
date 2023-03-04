<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use OpenAI;
use OpenAI\Client;
use Symfony\Component\HttpClient\Psr18Client;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('openai.http_client', Psr18Client::class)
            ->arg(0, service('http_client'))

        ->set(Client::class)
            ->factory([OpenAI::class, 'client'])
            ->args([
                abstract_arg('API Key'),
                abstract_arg('Organisation'),
                service('openai.http_client'),
            ])

        ->alias('openai', Client::class);
};
