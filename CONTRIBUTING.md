# Contributing

Thank you for contributing to Events (`t3events`). This document describes how the
repository is organised and how changes get from an idea into a release.

## Development environment

A DDEV based environment with one TYPO3 installation per supported major
version is available in
[t3events-bundle](https://github.com/dwenzel/t3events-bundle).
It checks out this repository into `packages/t3events` and lets you
switch between TYPO3 versions (`ddev t3-switch 12`) and run the tests of the
active branch (`ddev ext test:unit`).

## Branching model

The repository follows a branching model adapted from GitLab Flow.

### main

* `main` supports the newest TYPO3 version.
* `main` is protected. Changes are integrated only through pull requests,
  after the automated checks have passed.
* There are no `develop` or `release/*` branches.

### Support branches for older TYPO3 versions

Older TYPO3 major versions are maintained in support branches:

```
support/typo3-v{major}        e.g. support/typo3-v12
```

* A support branch is created from the last tag that supports that TYPO3
  version, as soon as a fix or change for that version is needed.
* Fixes and features for an older TYPO3 version are merged into its support
  branch through pull requests.
* Releases from a support branch continue the version line of that branch
  (e.g. 3.0.0 -> 3.0.1 on `support/typo3-v12`).
* Support branches are protected like `main`. They are deleted at the earliest
  when the TYPO3 major version is no longer maintained (no ELTS anymore).

The current branches and releases are listed in the
[support matrix](README.md#support-matrix).

### Feature branches

All work happens in feature branches. Every change starts with a GitHub issue;
name the branch after it:

```
feature/{issue-number}-{title-in-kebab-case}
feature/{title-in-kebab-case}        # only if there is no issue
```

Examples: `feature/94-typo3-14-compatibility`, `feature/fix-date-format`

* Branch from `main`, or from `support/typo3-v{major}` if the change only
  concerns an older TYPO3 version.
* Branch names consist of lowercase alphanumeric characters and `-`. Other
  characters are not allowed, apart from the `/` after the prefix.
* The prefixes `environment/`, `hotfix/` and `renovate/` and the names `live`,
  `production`, `pre-production`, `staging`, `integration`, `hardening` and
  `acceptance` are reserved.

Branches that are no longer maintained but should not be lost are kept as tags
under `archive/` (for example `archive/develop`).

### Which branch does my pull request target?

| Change                                              | Target                       |
|-----------------------------------------------------|------------------------------|
| Feature or fix for the newest TYPO3 version         | `main`                       |
| Fix that also affects older TYPO3 versions          | `main` first, then one pull request per affected `support/typo3-v{major}` (cherry-pick) |
| Fix that only affects an older TYPO3 version        | `support/typo3-v{major}`     |
| Compatibility with a new TYPO3 major version        | `main` (results in a new major version, see below) |

## Workflow

1. Create or pick a GitHub issue.
2. Create a feature branch from the target branch.
3. Make the change, including tests.
4. Run tests and linters locally.
5. Open a pull request into the target branch and reference the issue
   (`Closes #12`).
6. The pull request is merged after the checks have passed and it has been reviewed.
7. Delete the feature branch after merging.

## Commit messages

Commit messages are short and start with one of these tags:

`[FEATURE]` `[TASK]` `[BUGFIX]` `[DOC]` `[CI]` `[SECURITY]` `[RELEASE]` `[WIP]` `[DRAFT]`

Example: `[BUGFIX] Fix sending notifications in NotificationService`

`[WIP]` and `[DRAFT]` commits are not merged into `main` or a support branch.

## Releases

* Only tags are released. Tags follow [Semantic Versioning](https://semver.org/)
  without prefix (e.g. `4.0.0`).
* Support for a new TYPO3 major version always results in a new major version
  of the extension. Before that, a support branch is created for the previous
  TYPO3 version from its last tag.
* Release steps:
  1. Update the version in `ext_emconf.php` and the `ChangeLog` in a feature
     branch and merge it through a pull request (commit tag `[RELEASE]`).
  2. Tag the merge commit on `main` or on the support branch.
  3. Update the support matrix in `README.md` on `main`.
* Tags are published to the TYPO3 Extension Repository (TER) by the workflow
  `publish.yml`. It checks that the tag matches the version in `ext_emconf.php`
  and needs the repository secret `TYPO3_API_TOKEN`. An existing tag can be
  published by starting the workflow manually.
