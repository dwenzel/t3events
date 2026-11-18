[![CI](https://github.com/dwenzel/t3events/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/dwenzel/t3events/actions/workflows/ci.yml)

Events
======

Manage events, show them in teasers, list and single views in TYPO3.
This is an extension for the TYPO3 CMS.

## Support matrix

| TYPO3       | PHP (composer) | Branch              | Version line | Latest release |
|-------------|----------------|---------------------|--------------|----------------|
| 13.4 / 14.3 | `^8.3`         | `main`              | 4.x          | 4.0.1          |
| 12.4        | `^8.1`         | `support/typo3-v12` | 3.x          | 3.0.1          |
| 11.5        | not declared   | `support/typo3-v11` | 2.x          | 2.0.2          |
| 9.5 / 10.4  |                | -                   | 1.x          | 1.1.0 (not maintained) |

Releases are published to [Packagist](https://packagist.org/packages/dwenzel/t3events)
and the [TYPO3 Extension Repository](https://extensions.typo3.org/extension/t3events).

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for the branching model and the release
process. A development environment with one TYPO3 instance per supported version
is available in [t3events-bundle](https://github.com/dwenzel/t3events-bundle).

## Documentation

The documentation is published on
[docs.typo3.org](https://docs.typo3.org/p/dwenzel/t3events/main/en-us/).
Its sources are in [Documentation/](./Documentation/Index.rst); render them locally with

```bash
docker run --rm --pull always -v $(pwd):/project ghcr.io/typo3-documentation/render-guides:latest --config=Documentation
```

## Credits 
Thanks for contribution and feedback
* Michael Kasten
* [Sebastian Kreideweiss](https://github.com/kreidewe)
* [Christian Matthes](https://github.com/chrimath)
* [Vladimir Falcón Piva](https://github.com/vladimirfalconpiva)
* [Erik Rauchstein](https://github.com/erauchstein)
* [Jasmina Ließmann](https://github.com/frauliessmann)
* [Tobias Kummer](https://github.com/tobikummer)
* [Steffen Mevius](https://github.com/smeviuscps)
* [kaywalker](https://github.com/kaywalker)

This extension has been developed at [Agentur Webfox GmbH](https://www.agentur-webfox.de/) and [CPS IT GmbH](https://cps-it.de).

* Icon set: http://www.famfamfam.com/lab/icons/silk/
* Performance Icon: http://www.softicons.com/free-icons/object-icons/the-passed-day-icons-by-jj-maxer/stage-icon
* Ticket Icon: http://findicons.com/icon/178211/ticket
