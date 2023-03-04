<?php

declare(strict_types=1);

namespace OpenAI\Symfony\Tests\DependencyInjection;

use OpenAI\Client;
use OpenAI\Symfony\DependencyInjection\OpenAIExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class OpenAIExtensionTest extends TestCase
{
    public function testService(): void
    {
        $container = new ContainerBuilder();
        $extension = new OpenAIExtension();
        $extension->load([
            'openai' => [
                'api_key' => 'pk-123456789',
            ],
        ], $container);

        $openai = $container->get('openai');
        self::assertInstanceOf(Client::class, $openai);
    }
}
