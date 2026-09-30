# Changelog

## [v0.2.0](https://github.com/runapi-ai/nano-banana-php/releases/tag/v0.2.0) - 2026-09-30

### Changed
- Send request parameters to the service without local validation. Model ids and parameter values the service supports work without an SDK upgrade; static types and enum constants remain for completion.
  Migration: Invalid parameters now throw `ValidationException` built from the service's 400 response, including its status and message, instead of a `ValidationException` thrown locally before the request.


## [v0.1.4](https://github.com/runapi-ai/nano-banana-php/releases/tag/v0.1.4) - 2026-07-16

### Changed
- Add Nano Banana 2 Lite support to PHP edit image types, validation, README examples, and tests.
- Keep Nano Banana 2 Lite as the default edit model in the PHP SDK.

## [v0.1.3](https://github.com/runapi-ai/nano-banana-php/releases/tag/v0.1.3) - 2026-07-08

### Changed
- Refresh Nano Banana PHP package metadata and type definitions for the current public API catalog.

## [v0.1.2](https://github.com/runapi-ai/nano-banana-php/releases/tag/v0.1.2) - 2026-07-07

### Added
- Add Nano Banana 2 Lite support.

### Changed
- Publish v0.1.2.

## [v0.1.0](https://github.com/runapi-ai/nano-banana-php/releases/tag/v0.1.0) - 2026-06-25

### Added
- Publish the first RunAPI PHP Composer package release for `runapi-ai/nano-banana`.
- Include typed PHP client resources, package README, Apache-2.0 license, and Composer CI.
