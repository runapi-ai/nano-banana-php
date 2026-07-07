<?php

declare(strict_types=1);

namespace RunApi\NanoBanana\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\NanoBanana\Models\CompletedImageTaskResponse;
use RunApi\NanoBanana\NanoBananaClient;
use RunApi\NanoBanana\Resources\EditImage;
use RunApi\NanoBanana\Resources\TextToImage;

final class NanoBananaClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new NanoBananaClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToImage::class, $client->textToImage);
        self::assertInstanceOf(EditImage::class, $client->editImage);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
        ]);
        $client = new NanoBananaClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->textToImage->create([
            'model' => 'nano-banana',
            'prompt' => 'A product render',
            'callback_url' => '',
            'seed' => null,
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/nano_banana/text_to_image', $transport->requests[0]->getUri()->getPath());
        self::assertSame('nano-banana', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testCreatePostsLiteBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_lite"}'),
        ]);
        $client = new NanoBananaClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->textToImage->create([
            'model' => 'nano-banana-2-lite',
            'prompt' => 'A product render',
            'aspect_ratio' => 'auto',
            'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_lite', $task->id);
        self::assertSame('/api/v1/nano_banana/text_to_image', $transport->requests[0]->getUri()->getPath());
        self::assertSame('nano-banana-2-lite', $body['model']);
        self::assertSame('auto', $body['aspect_ratio']);
        self::assertArrayNotHasKey('output_resolution', $body);
        self::assertArrayNotHasKey('output_format', $body);
    }

    public function testRejectsLiteOutputControls(): void
    {
        $client = new NanoBananaClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('output_resolution is not allowed when model is nano-banana-2-lite');

        $client->textToImage->create([
            'model' => 'nano-banana-2-lite',
            'prompt' => 'A product render',
            'aspect_ratio' => 'auto',
            'output_resolution' => '1k',
        ]);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","images":[{"url":"https://file.runapi.ai/result"}],"extra_field":"kept"}'),
        ]);
        $client = new NanoBananaClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToImage->run([
            'model' => 'nano-banana',
            'prompt' => 'A product render',
        ]);

        self::assertInstanceOf(CompletedImageTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->images[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/nano_banana/text_to_image/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed"}'),
        ]);
        $client = new NanoBananaClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('images is required');

        $client->textToImage->run([
            'model' => 'nano-banana',
            'prompt' => 'A product render',
        ]);
    }

    public function testRejectsInvalidContractEnum(): void
    {
        $client = new NanoBananaClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('aspect_ratio must be one of the allowed values');

        $client->textToImage->create([
        'model' => 'nano-banana',
        'prompt' => 'A product render',
        'aspect_ratio' => 'not-valid',
        ]);
    }

    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_2"}'),
        ]);
        $client = new NanoBananaClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->editImage->create([
            'model' => 'nano-banana-edit',
            'prompt' => 'A product render',
            'source_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
        ]);

        self::assertSame('/api/v1/nano_banana/edit_image', $transport->requests[0]->getUri()->getPath());
    }
}
