# DocCheck Access

DocCheck Access is a TYPO3 extension that provides a lightweight DocCheck OAuth login flow for frontend access.

It is built for projects where visitors should authenticate through DocCheck and then be logged into TYPO3 as one predefined frontend user.

## Documentation

- [About](About.md)
- [Installation](Installation.md)
- [For Administrators](ForAdministrators.md)
- [For Editors](ForEditors.md)
- [For Contributors](ForContributors.md)

## Quick Facts

| Property | Value |
|---|---|
| Extension key | `doccheck_access` |
| Composer package | `doc2k/doccheck-access` |
| PHP namespace | `Doc2k\DoccheckAccess` |
| TYPO3 compatibility | TYPO3 11.5 LTS to TYPO3 14.3 LTS |
| PHP compatibility | PHP 8.0 and newer |
| Frontend integration | Content elements, TypoScript, PSR-15 middleware |
| Extbase plugins | No |
| Backend modules | No |
| Site Sets required | No |

## Main Features

- DocCheck OAuth authentication
- TYPO3 frontend user login
- Language-aware login and redirect handling
- Language-specific DocCheck client overrides for multi-domain sites
- Configurable success and failure pages
- Session-based error handling
- Content elements for login and error output

## Scope

The extension focuses on a clear and lightweight access-gate scenario: visitors authenticate with DocCheck and TYPO3 grants access through one configured frontend user. More advanced account handling, profile synchronization or role mapping can be added in project-specific integrations if needed.

For installation and configuration, start with [Installation](Installation.md) and [For Administrators](ForAdministrators.md).
