# Nano Banana PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/nano-banana)](https://packagist.org/packages/runapi-ai/nano-banana)
[![License](https://img.shields.io/github/license/runapi-ai/nano-banana-php)](https://github.com/runapi-ai/nano-banana-php/blob/main/LICENSE)

The Nano Banana PHP SDK is the Composer package for Nano Banana on RunAPI. Use it when your PHP application needs associative-array request bodies, task status lookup, polling helpers, file helpers, and consistent RunAPI errors.

## Install

```bash
composer require runapi-ai/nano-banana
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\NanoBanana\NanoBananaClient;

$client = new NanoBananaClient(); // reads RUNAPI_API_KEY

$task = $client->textToImage->create([
    'model' => 'nano-banana',
    'prompt' => 'A precise product render on white marble',
]);

$status = $client->textToImage->get($task->id);

$result = $client->textToImage->run([
    'model' => 'nano-banana',
    'prompt' => 'A serene mountain lake at dawn',
]);

echo $result->images[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest task state, and `run()` when a script should create and poll until completion. In web request handlers, prefer `create()` plus webhook or later `get()` polling so a worker is not held open.

Returned file URLs are temporary. Download and store generated files in your own durable storage within the retention window.

All SDK exceptions inherit from `RunApi\Core\Errors\RunApiException`, including validation, authentication, rate limit, task failure, and task timeout errors.

## Links

- Model page: https://runapi.ai/models/nano-banana
- SDK docs: https://runapi.ai/docs#sdk-nano-banana
- Product docs: https://runapi.ai/docs#nano-banana
- Pricing and rate limits: https://runapi.ai/models/nano-banana/nano-banana
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/nano-banana-php
- Multi-language SDK repository: https://github.com/runapi-ai/nano-banana-sdk

## License

Licensed under the Apache License, Version 2.0.
