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
 * Modifies existing images based on text prompts.
 */
readonly class EditImage extends TypedConfiguredResource
{
    private const DEFAULT_MODEL = 'nano-banana-2-lite';

    /**
     * Submits an edit-image task and returns immediately with a task id.
     *
     * @param array{
     *   model?: string,
     *   prompt: string,
     *   source_image_urls: list<string>,
     *   aspect_ratio?: string,
     *   callback_url?: string,
     *   output_format?: string
     * } $params
     *
     * nano-banana-2-lite requires aspect_ratio and does not support output_format.
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($this->withDefaultModel($params), $options);
    }

    /**
     * Fetches the current status of an edit-image task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): ImageTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var ImageTaskResponse $response */
        return $response;
    }

    /**
     * Submits an edit-image task and polls until it completes.
     *
     * @param array{
     *   model?: string,
     *   prompt: string,
     *   source_image_urls: list<string>,
     *   aspect_ratio?: string,
     *   callback_url?: string,
     *   output_format?: string
     * } $params
     *
     * nano-banana-2-lite requires aspect_ratio and does not support output_format.
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedImageTaskResponse
    {
        $response = parent::run($this->withDefaultModel($params), $options);

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
            '/api/v1/nano_banana/edit_image',
            'nano-banana/edit-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
            Types::EDIT_IMAGE_MODELS,
            'edit-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    private function withDefaultModel(array $params): array
    {
        if (!array_key_exists('model', $params) || $params['model'] === null || $params['model'] === '') {
            $params['model'] = self::DEFAULT_MODEL;
        }

        return $params;
    }
}
