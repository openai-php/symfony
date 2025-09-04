<?php

declare(strict_types=1);

namespace OpenAI\Symfony\Tests;

use OpenAI\Client;
use OpenAI\Contracts\ClientContract;
use OpenAI\Symfony\OpenAIBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpKernel\Kernel;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

final class OpenAIBundleTest extends TestCase
{
    public function test_service(): void
    {
        $kernel = new class('test', true) extends Kernel
        {
            use MicroKernelTrait;

            public function registerBundles(): iterable
            {
                yield new FrameworkBundle;
                yield new OpenAIBundle;
            }

            protected function configureContainer(ContainerConfigurator $container): void
            {
                $container->extension('framework', [
                    'secret' => 'S0ME_SECRET',
                ]);

                $container->extension('openai', [
                    'api_key' => 'pk-123456789',
                    'organization' => 'org-123456789',
                ]);

                $container->services()
                    ->set('http_client', MockHttpClient::class)
                    ->public()

                    ->set('tested_services', \ArrayObject::class)
                    ->args([[
                        'openai' => service('openai'),
                        Client::class => service(Client::class),
                        ClientContract::class => service(ClientContract::class),
                    ]])
                    ->public();
            }
        };

        // Using a mock to test the service configuration
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options = []): MockResponse {
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

        $kernel->boot();
        $container = $kernel->getContainer();
        $container->set('http_client', $httpClient);

        $testedServices = $container->get('tested_services');
        assert($testedServices instanceof \ArrayObject);
        $openai = $testedServices['openai'];
        self::assertInstanceOf(Client::class, $openai);
        self::assertSame($openai, $testedServices[Client::class]);
        self::assertSame($openai, $testedServices[ClientContract::class]);

        $response = $openai->files()->delete('file.txt');
        self::assertSame('file.txt', $response->id);
    }
}
