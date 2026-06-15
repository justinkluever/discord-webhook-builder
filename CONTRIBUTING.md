# CONTRIBUTING

Contributions are welcome, and are accepted via pull requests.
Please review these guidelines before submitting any pull requests.

## Process

1. Create Issue where we discuss the idea or bug first.
2. Fork the project
3. Create a new branch
4. Code, test, commit and push
5. Open a pull request detailing your changes. Make sure to follow the [template](.github/PULL_REQUEST_TEMPLATE.md)

## Guidelines

* Contributions that look heavily AI assisted will be rejected on my discretion, if you don't care to write it yourself
  how can i be sure i dont waste time reviewing.
* Please ensure the coding style running `composer lint`.
* Send a coherent commit history, making sure each individual commit in your pull request is meaningful.
* You may need to [rebase](https://git-scm.com/book/en/v2/Git-Branching-Rebasing) to avoid merge conflicts.
* Please remember that we follow [SemVer](http://semver.org/).

## License

By submitting a pull request, you agree that your contributions will be licensed under the
project's [MIT License](LICENSE.md).

## Setup

Clone your fork, then install the dev dependencies:

```bash
composer install
```

## Lint

Lint your code:

```bash
composer lint
```

## Tests

Run all tests:

```bash
composer test
```

Check types:

```bash
composer test:types
```

Unit tests:

```bash
composer test:unit
```
