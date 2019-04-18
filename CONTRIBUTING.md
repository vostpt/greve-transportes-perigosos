# Contributing
First and foremost, we appreciate your interest in this project. This document contains essential information, should you want to contribute.

## Development discussion
For bugs, new features or improvements, open a new [issue](https://github.com/vostpt/api/issues/new).

## Which Branch?
Pull requests should always be done against the `master` branch.

## Coding Style
This project follows the [PSR-2](https://www.php-fig.org/psr/psr-2/) coding style guide and the [PSR-4](https://www.php-fig.org/psr/psr-4/) autoloader standard.

### PHP Coding Standards Fixer
A [PHP CS Fixer](https://cs.symfony.com/) script is hooked into the CI pipeline, so you'll be notified of any coding standard issue when pushing code.

#### Check
On each build, the `composer cs-check` script is executed to make sure the coding standards are followed.

#### Fix
If the build breaks due to coding standards, the following command fixes the issues:

```sh
composer cs-fix <file or directory name>
```

#### Pre-Commit Hook installation
To run the coding style check before each commit, install the bundled script in the project root with the following command:

```sh
cp pre-commit.sh .git/hooks/pre-commit
```

This prevents code from being committed if the check fails.

### PHPDoc
The following is a valid documentation block example:

```php
/**
 * Index Occurrences.
 *
 * @param Index                $request
 * @param OccurrenceFilter     $filter
 * @param OccurrenceRepository $occurrenceRepository
 *
 * @throws \InvalidArgumentException
 * @throws \OutOfBoundsException
 * @throws \RuntimeException
 *
 * @return \Illuminate\Http\JsonResponse
 */
public function index(Index $request, OccurrenceFilter $filter, OccurrenceRepository $occurrenceRepository): JsonResponse
{
    // ...
}
```
## Committing to git
Each commit **MUST** have a proper message describing the work that has been done.
This is called [Semantic Commit Messages](https://seesparkbox.com/foundry/semantic_commit_messages).

Here's what a commit message should look like:

```txt
feat(Occurrences): implement API client to fetch occurrence data
^--^ ^---------^   ^-------------------------------------------^
|    |             |
|    |             +-> Description of the work in the present tense.
|    |
|    +---------------> Scope of the work.
|
+--------------------> Type: chore, docs, feat, fix, hack, refactor, style, or test.
```

## Branching strategy
We will be using the **branch-per-issue** workflow.

This means, that for each open [issue](https://github.com/vostpt/api/issues), we'll create a corresponding **git** branch.

For instance, issue `#123` should have a corresponding `API-123/ShortTaskDescription` branch, which **MUST** branch off the latest code in `master`.
