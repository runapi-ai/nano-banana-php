<?php

declare(strict_types=1);

namespace RunApi\NanoBanana\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\NanoBanana\Models\CompletedImageTaskResponse;
use RunApi\NanoBanana\Models\ImageTaskResponse;
use RunApi\NanoBanana\Types;

/**
 * Generates images from text prompts with various models and options.
 */
readonly class TextToImage extends TypedConfiguredResource
{
    /**
     * Submits a text-to-image task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   aspect_ratio?: string,
     *   callback_url?: string,
     *   output_format?: string,
     *   output_resolution?: string,
     *   reference_image_urls?: list<string>
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of a text-to-image task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): ImageTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var ImageTaskResponse $response */
        return $response;
    }

    /**
     * Submits a text-to-image task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   aspect_ratio?: string,
     *   callback_url?: string,
     *   output_format?: string,
     *   output_resolution?: string,
     *   reference_image_urls?: list<string>
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedImageTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedImageTaskResponse $response */
        return $response;
    }

    /**
     * Create the resource using the shared RunAPI HTTP transport.
     */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/nano_banana/text_to_image',
            'nano-banana/text-to-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
            Types::TEXT_TO_IMAGE_MODELS,
            'text-to-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
        );
    }
}
