# DocCheck Access

DocCheck Access is a lightweight TYPO3 extension that provides DocCheck OAuth authentication for frontend access.

The extension was created as a simple replacement for the former DocCheck Access Basic integration and focuses on one specific use case:

> Authenticate users via DocCheck and grant access to protected TYPO3 content using a predefined frontend user.

The implementation is intentionally lightweight and avoids unnecessary complexity. It uses TYPO3 frontend sessions, PSR-15 middlewares and standard TYPO3 content elements without requiring Extbase plugins or backend modules.

## Features

* DocCheck OAuth authentication
* TYPO3 frontend user login
* Language-aware login and redirect handling
* Configurable success and failure pages
* Session-based error handling
* Content elements for login and error output
* TYPO3 11.5 LTS to TYPO3 14 compatibility

## Scope and Limitations

This extension authenticates users through DocCheck and logs in a predefined TYPO3 frontend user.

The extension does **not**:

* create frontend users automatically
* synchronize DocCheck user data
* manage frontend user accounts
* map DocCheck roles or permissions
* import user profiles

If your project requires individual TYPO3 frontend users, profile synchronization or role mapping, a custom integration should be considered instead.

## Installation

Install the extension via Composer:

```bash
composer require doc2k/doccheck-access
```

Activate the extension in TYPO3 and execute the database compare to add the required database fields.

## Configuration

Open the TYPO3 Extension Configuration and provide the required DocCheck credentials and TYPO3 settings.

### Required Settings

| Setting           | Description                                    |
| ----------------- | ---------------------------------------------- |
| Client ID         | DocCheck OAuth Client ID                       |
| Client Secret     | DocCheck OAuth Client Secret                   |
| Callback URL      | Registered DocCheck callback URL               |
| Failure Page      | TYPO3 page shown when authentication fails     |
| Frontend User UID | TYPO3 frontend user used for successful logins |

### Optional Settings

| Setting                 | Description                            |
| ----------------------- | -------------------------------------- |
| Success Page            | Global fallback success page           |
| Frontend User Group UID | Reserved for future use                |
| Token Endpoint          | Alternative token endpoint if required |

A success page can be configured globally or individually on each login content element. The content element configuration always takes precedence.

## Content Elements

### DocCheck Login

Displays a login button that starts the DocCheck authentication process.

Available fields:

* Header
* Button Label
* Success Page
* Standard TYPO3 Appearance, Access and Language settings

### DocCheck Error Message

Displays the most recent DocCheck authentication error stored in the frontend session.

The content element is rendered uncached to ensure session-based messages are displayed correctly.

## Authentication Flow

1. A visitor clicks the DocCheck login button.
2. TYPO3 stores the current language and target page in the frontend session.
3. The visitor is redirected to DocCheck.
4. DocCheck redirects back to the configured callback URL.
5. TYPO3 exchanges the authorization code for an access token.
6. The configured frontend user is logged in.
7. The visitor is redirected to the configured success page.

If the authentication process fails, the visitor is redirected to the configured failure page.

## Error Handling

Configuration problems are treated as installation errors and will raise a `RuntimeException`.

Examples include:

* missing Client ID
* missing Client Secret
* missing Callback URL
* invalid frontend user configuration
* missing failure page

Authentication-related problems are stored in the TYPO3 frontend session and can be displayed using the **DocCheck Error Message** content element.

Current error codes:

* `missing_code`
* `token_exchange_failed`
* `frontend_login_failed`
* `missing_content_element`
* `invalid_content_element`

## Technical Notes

The extension stores TYPO3-specific context such as language and target page information in the frontend session during the authentication process.

No TYPO3-specific state information is transmitted to DocCheck.
