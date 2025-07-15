<p align="center">
    <p align="center">
        <a href="https://github.com/openai-php/symfony/actions"><img alt="GitHub Workflow Status (master)" src="https://img.shields.io/github/actions/workflow/status/openai-php/symfony/tests.yml?branch=main&label=tests&style=round-square"></a>
        <a href="https://packagist.org/packages/openai-php/symfony"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/openai-php/symfony"></a>
        <a href="https://packagist.org/packages/openai-php/symfony"><img alt="Latest Version" src="https://img.shields.io/packagist/v/openai-php/symfony"></a>
        <a href="https://packagist.org/packages/openai-php/symfony"><img alt="License" src="https://img.shields.io/github/license/openai-php/symfony"></a>
    </p>
</p>

------
**OpenAI PHP** for Symfony is a community-maintained PHP API client that allows you to interact with the [Open AI API](https://beta.openai.com/docs/api-reference/introduction). If you or your business relies on this package, it's important to support the developers who have contributed their time and effort to create and maintain this valuable tool:

- Nuno Maduro: **[github.com/sponsors/nunomaduro](https://github.com/sponsors/nunomaduro)**
- Sandro Gehri: **[github.com/sponsors/gehrisandro](https://github.com/sponsors/gehrisandro)**

> **Note:** This repository contains the integration code of the **OpenAI PHP** for Symfony. If you want to use the **OpenAI PHP** client in a framework-agnostic way, take a look at the [openai-php/client](https://github.com/openai-php/client) repository.

## Get Started

> **Requires [PHP 8.1+](https://php.net/releases/)**

First, install OpenAI via the [Composer](https://getcomposer.org/) package manager:

```bash
composer require openai-php/symfony
```

Next, register the bundle in your `config/bundles.php`:

```php
return [
    // ...
    OpenAI\Symfony\OpenAIBundle::class => ['all' => true],
]
```

This will create a `.env` configuration file in your project, which you can modify to your needs
using environment variables:

```env
OPENAI_API_KEY=sk-...
OPENAI_ORGANIZATION=...
```

Finally, you may use the `openai` service to access the OpenAI API:

```php
$result = $container->get('openai')->completions()->create([
    'model' => 'gpt-4o-mini',
    'prompt' => 'PHP is',
]);

echo $result['choices'][0]['text']; // an open-source, widely-used, server-side scripting language.
```

## Usage

For usage examples, take a look at the [openai-php/client](https://github.com/openai-php/client) repository.

---

OpenAI PHP for Symfony is an open-sourced software licensed under the **[MIT license](https://opensource.org/licenses/MIT)**.
