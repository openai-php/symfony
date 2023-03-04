<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use OpenAI;
use OpenAI\Client;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(Client::class)
            ->factory([OpenAI::class, 'client'])
            ->args([
                abstract_arg('API Key'),
                abstract_arg('Organisation'),
            ])
        ->alias('openai', Client::class);
};
