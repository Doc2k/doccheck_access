# About

`doccheck_access` provides a small TYPO3 integration for DocCheck based access control.

The extension authenticates visitors through DocCheck and logs them into TYPO3 as one predefined frontend user. TYPO3 access restrictions can then be handled with the usual frontend user and frontend user group mechanisms.

This extension implements the newer OAuth2-based DocCheck Login flow for TYPO3. It is intended to support existing TYPO3 installations and customers that previously relied on older DocCheck login integrations and currently need a practical solution while waiting for an official extension.

## Intended Use Case

Use this extension when:

- DocCheck should be used as an external login gate.
- All authenticated DocCheck visitors may share one TYPO3 frontend user.
- Protected TYPO3 content is already handled through frontend user access restrictions.
- A lightweight integration without Extbase plugins or backend modules is preferred.

## Not Included

The extension does not:

- create TYPO3 frontend users automatically
- synchronize DocCheck user data
- manage individual frontend user accounts
- map DocCheck roles or permissions
- import DocCheck profiles
- transmit TYPO3 state through OAuth parameters

If individual frontend users, profile synchronization or role mapping are required, this extension should be extended or replaced by a custom project-specific integration.

## Architecture

The extension uses:

- TYPO3 content elements for editor-facing output
- PSR-15 frontend middlewares for login and callback handling
- service classes for configuration, DocCheck API access and frontend user login
- TYPO3 frontend sessions for temporary flow state and error messages

It does not require Extbase plugins, backend modules or Site Sets.

## Compatibility

| Component | Compatibility |
|---|---|
| TYPO3 | 11.5 LTS to 14.3 LTS |
| PHP | 8.0 and newer |
| Installation | Composer or TER |

The extension remains usable without Site Sets so it can support TYPO3 11.5 and TYPO3 12 projects.
