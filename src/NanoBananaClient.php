<?php

declare(strict_types=1);

namespace RunApi\NanoBanana;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\NanoBanana\Resources\EditImage;
use RunApi\NanoBanana\Resources\TextToImage;

/**
 * The NanoBanana image generation API client.
 *
 * Exposes typed model resources plus the universal files and account resources.
 */
final class NanoBananaClient extends BaseClient
{
    /**
     * Provides image generation operations.
     */
    public readonly TextToImage $textToImage;
    /**
     * Provides image editing operations.
     */
    public readonly EditImage $editImage;

    /**
     * Create a Nano Banana client with optional API key, base URL, and transport overrides.
     */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToImage = TextToImage::fromHttp($this->http);
        $this->editImage = EditImage::fromHttp($this->http);
    }
}
