# Changelog

## [2.0.0] - 2025-10-01

### Changed
- **BREAKING**: Refactored `HashAttributeService` to follow SOLID principles
- **BREAKING**: Split `saved` event into `created` and `updated` events in `HasHashId` trait
- Changed default for `allowHashingAfterUpdate()` to `false` for safer behavior
- Improved code quality with strict types and better documentation

### Added
- Comprehensive PHPDoc blocks with usage examples
- Better exception handling with `InvalidArgumentException`

### Fixed
- Improved method naming and clarity throughout the package

## [1.0.0] - Previous version
...