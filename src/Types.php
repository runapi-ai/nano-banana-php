<?php

declare(strict_types=1);

namespace RunApi\NanoBanana;

/**
 * Constants for model slugs supported by the Nano Banana PHP SDK.
 */
final class Types
{
    /** @var list<string> */
    public const TEXT_TO_IMAGE_MODELS = ['nano-banana', 'nano-banana-2', 'nano-banana-pro'];

    /** @var list<string> */
    public const EDIT_IMAGE_MODELS = ['nano-banana-edit'];

    private function __construct()
    {
    }
}
