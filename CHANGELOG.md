# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## v0.17.0
### Changed
- Refactored into a single `OpenAI\Symfony\OpenAIBundle` class
- Add `project` and `base_uri` configuration options
- Drop support for unsupported Symfony versions. Now requires Symfony 6.4 or 7.3+
- Add support for Symfony 8.0

## v0.12.0 (2025-05-06)
### Changed
- Changed underlying `openai/client` package version to 0.12.0
- Add an alias for the `ClientContract` interface

## v0.10.0 (2024-06-19)
### Changed
- Specify header `OpenAI-Beta: assistants=v2`
- Changed underlying `openai/client` package version to 0.10.0

## v0.7.10 (2023-11-14)
### Changed
- Changed underlying `openai/client` package version to 0.7.10

## v0.4.1 (2023-03-24)
### Changed
- Changed underlying `openai/client` package version from 0.4.0 to 0.4.1

## v0.4.0 (2023-03-20)
### Changed
- Changed underlying `openai/client` package version from 0.3.5 to 0.4.0

## v0.3.5 (2023-03-09)
### Added
- First version
