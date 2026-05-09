# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this plugin.

## Project
Coupon Creator — WordPress plugin that creates a custom post type for coupons with a shortcode to display on websites and a single view template for printing.

## Stack
- PHP: 7.4+
- WordPress: 5.8+
- DI Container: `lucatume/di52`
- Test Framework: Codeception (via `lucatume/wp-browser`)
- Test Runner: SLIC (Docker-based)

## Plugin Info
- Slug: `coupon-creator`
- Namespace: `Cctor\Coupon\`
- Main File: `coupon_creator.php`
- Main Class: `Cctor__Coupon__Main`
- Post Type: `cctor_coupon`
- Taxonomy: `cctor_coupon_category`
- Version: 3.5.0
- Text Domain: `coupon-creator`

## Key Constants
- `COUPON_CREATOR_DIR` — plugin directory path
- `COUPON_CREATOR_MAIN_PLUGIN_FILE` — main plugin file path

## Architecture

### Class Naming
Classes use double-underscore `__` as namespace separator (legacy pattern):
- `Cctor__Coupon__Main` → `src/Cctor/Main.php`
- `Cctor__Coupon__Shortcode` → `src/Cctor/Shortcode.php`

PSR-4 autoloading maps `Cctor\Coupon\` → `src/Cctor/`

### Embedded Plugin Engine
The `plugin-engine/` subdirectory contains the shared framework (`Pngx\` namespace). It provides:
- DI Container (`Pngx__Container`)
- Service Provider base (`Pngx__Abstract_Plugin_Register`)
- Admin field system
- REST API framework
- Utility classes

### Key Directories
```
coupon-creator/
├── coupon_creator.php          # Main plugin file
├── plugin-engine/              # Shared framework (Pngx namespace)
│   ├── src/Pngx/              # Framework classes
│   └── pngx-common.php        # Framework bootstrap
├── src/
│   ├── Cctor/                 # Plugin classes (PSR-4)
│   │   ├── Main.php           # Singleton entry point
│   │   ├── Provider.php       # Service provider
│   │   ├── Hooks.php          # Hook registration
│   │   ├── Shortcode.php      # [coupon] shortcode
│   │   ├── Print.php          # Print view
│   │   ├── Post_Type_Coupon.php # CPT registration
│   │   ├── Admin/             # Admin UI classes
│   │   ├── Blocks/            # Gutenberg blocks
│   │   ├── Meta/              # Meta field handling
│   │   └── Templates/         # Template system
│   ├── functions/             # Procedural template functions
│   └── deprecated/            # Deprecated code
├── tests/                     # Codeception test suites
└── vendor/                    # Composer dependencies
```

### Singleton Pattern
Main class uses singleton: `Cctor__Coupon__Main::instance()`
Use `pngx()` helper to resolve classes from the DI container.

### Hook Registration
Hooks are registered in Provider classes (`Cctor\Coupon\Provider`), not scattered across files.
Filter callbacks should not have type hints (other plugins may change types).

## Dependencies
- Requires plugin-engine v4.0.0+ (embedded)
- Pro and Add-ons plugins extend this core
- Uses `lucatume/di52` for dependency injection
- Uses `firebase/php-jwt` for JWT handling
- Uses `monolog/monolog` for logging

## Related Plugins
- `plugin-engine` — embedded shared framework (`plugin-engine/`)
- `coupon-creator-pro` — pro features, filters, templates
- `coupon-creator-add-ons` — add-on features, locations, vendors, email

## Testing

### Running Tests
```bash
slic use coupon-creator
slic run tests              # all suites
slic run tests/unit         # unit only
slic run tests/wpunit       # WordPress integration
```

### Commands
- `/scaffold-tests coupon-creator --namespace Cctor\Coupon` — generate test structure
- `/run-tests` — run all tests
- `/run-tests wpunit --coverage` — with coverage report

### Conventions
- Unit: extend `Codeception\Test\Unit`, `_before()` for setup
- WPUnit: extend `Codeception\TestCase\WPTestCase`, `setUp()`/`parent::setUp()`
- Factories for test data: `$this->factory()->post->create()`
- Namespace tests: `Cctor\Coupon\Tests\{Unit,WPUnit,Functional}`

## Working Principles

Behavioral foundation. These shape *how* to approach work, before any code-style rule applies.

1. **Don't assume.** If scope, format, or behavior is ambiguous, ask before coding. Surface tradeoffs instead of picking silently.
2. **Minimum code that solves the problem.** No speculative abstractions. Refactor on the third duplication, not the first.
3. **Touch only what you must.** Every changed line should trace to the request. Clean up orphans your own change creates; leave pre-existing code alone.
4. **Define success criteria, then verify.** State what "done" looks like before implementing (a failing test, a checklist, an observable behavior). Loop until each criterion is met.

## Verification

Define success criteria before implementing. Then verify against them:
- Run `slic run tests/unit` after pure PHP changes
- Run `slic run tests/wpunit` after WordPress integration changes
- Run `slic run tests` before marking any task complete

## Learnings
<!-- Add patterns from PR reviews here. -->
