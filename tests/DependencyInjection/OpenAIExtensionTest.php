<?php

declare(strict_types=1);

namespace OpenAI\Symfony\Tests\DependencyInjection;

use OpenAI\Client;
use OpenAI\Contracts\ClientContract;
use OpenAI\Symfony\DependencyInjection\OpenAIExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class OpenAIExtensionTest extends TestCase
{
    public function test_service(): void
    {
        // Using a mock to test the service configuration
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options = []) {
            self::assertSame('DELETE', $method);
            self::assertSame('https://api.openai.com/v1/files/file.txt', $url);
            self::assertContains('Authorization: Bearer pk-123456789', $options['headers']);

            return new MockResponse('{"id":"file.txt","object":"file","deleted":true}', [
                'http_code' => 200,
                'response_headers' => [
                    'content-type' => 'application/json',
                    'x-request-id' => '0123456789abcdef0123456789abcdef',
                ],
            ]);
        });

        $container = new ContainerBuilder;
        $container->set('http_client', $httpClient);

        $extension = new OpenAIExtension;
        $extension->load([
            'openai' => [
                'api_key' => 'pk-123456789',
            ],
        ], $container);

        $openai = $container->get('openai');
        self::assertInstanceOf(Client::class, $openai);

        $response = $openai->files()->delete('file.txt');
        self::assertSame('file.txt', $response->id);

        self::assertSame($openai, $container->get(ClientContract::class), 'Alias for the ClientContract interface');
    }
}
