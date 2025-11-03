# Contributing to Jinom Helpers

We love your input! We want to make contributing to Jinom Helpers as easy and transparent as possible, whether it's:

- Reporting a bug
- Discussing the current state of the code
- Submitting a fix
- Proposing new features
- Becoming a maintainer

## Development Process

We use GitHub to host code, to track issues and feature requests, as well as accept pull requests.

## Pull Requests

1. Fork the repo and create your branch from `main`.
2. If you've added code that should be tested, add tests.
3. If you've changed APIs, update the documentation.
4. Ensure the test suite passes.
5. Make sure your code lints.
6. Issue that pull request!

## Setting Up Development Environment

1. Clone your fork:
```bash
git clone https://github.com/your-username/helpers.git
cd helpers
```

2. Install dependencies:
```bash
composer install
```

3. Run tests:
```bash
composer test
```

4. Run code style fixes:
```bash
composer format
```

5. Run static analysis:
```bash
composer analyse
```

## Coding Standards

- Follow PSR-12 coding standards
- Write comprehensive PHPDoc comments
- Add tests for new functionality
- Use meaningful variable and method names
- Keep methods focused and single-purpose

## Testing

We use [Pest PHP](https://pestphp.com/) for testing. Please ensure:

- All new features have corresponding tests
- All tests pass before submitting PR
- Maintain or improve code coverage
- Test both success and edge cases

### Running Tests

```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage
```

## Code Style

We use [Laravel Pint](https://laravel.com/docs/pint) for code formatting:

```bash
# Fix code style issues
composer format
```

## Static Analysis

We use [PHPStan](https://phpstan.org/) for static analysis:

```bash
# Run static analysis  
composer analyse
```

## Adding New Features

When adding new features:

1. **Discuss first**: Open an issue to discuss the feature before implementing
2. **Follow patterns**: Look at existing code patterns and follow them
3. **Document thoroughly**: Add comprehensive PHPDoc comments and update README
4. **Test extensively**: Include unit tests and integration tests
5. **Consider backwards compatibility**: Avoid breaking changes when possible

### Tax Rate Changes

If you need to modify the tax rate:
- Consider making it configurable rather than hardcoded
- Update all tests that depend on the 11% rate
- Update documentation examples
- Consider backwards compatibility

### Currency Formatting

For currency formatting improvements:
- Maintain Indonesian Rupiah format standards
- Consider locale-specific variations
- Test with various number ranges
- Update examples in documentation

## Bug Reports

We use GitHub issues to track public bugs. Report a bug by [opening a new issue](https://github.com/jinomdeveloper/helpers/issues).

**Great Bug Reports** tend to have:

- A quick summary and/or background
- Steps to reproduce
  - Be specific!
  - Give sample code if you can
- What you expected would happen
- What actually happens
- Notes (possibly including why you think this might be happening, or stuff you tried that didn't work)

## Feature Requests

We welcome feature requests! Please:

1. Check existing issues first
2. Explain the use case clearly
3. Provide examples of how it would work
4. Consider implementation complexity
5. Think about backwards compatibility

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

## Questions?

Feel free to contact the maintainer at rupadanawayan@gmail.com or open a GitHub issue for any questions about contributing.