**OpenAI PHP** for Symfony is a supercharged community PHP API client that allows you to interact with the [Open AI API](https://beta.openai.com/docs/api-reference/introduction).

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
```

Finally, you may use the `openai` service  to access the OpenAI API:

```php
$result = $container->get('openai')->completions()->create([
    'model' => 'text-davinci-003',
    'prompt' => 'PHP is',
]);

echo $result['choices'][0]['text']; // an open-source, widely-used, server-side scripting language.
```

## Usage

For usage examples, take a look at the [openai-php/client](https://github.com/openai-php/client) repository.

---

OpenAI PHP for Symfony is an open-sourced software licensed under the **[MIT license](https://opensource.org/licenses/MIT)**.
