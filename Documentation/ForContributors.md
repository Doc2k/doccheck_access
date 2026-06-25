# For Contributors

This section contains technical details for integrators and developers who want to understand or extend the extension.

## Main Classes

| Class | Purpose |
|---|---|
| `Middleware\LoginMiddleware` | Starts the DocCheck authorization flow |
| `Middleware\CallbackMiddleware` | Handles the DocCheck callback |
| `Service\ConfigurationService` | Reads and validates extension configuration |
| `Service\DocCheckApiService` | Builds authorization URLs and exchanges codes for tokens |
| `Service\FrontendLoginService` | Logs in the configured TYPO3 frontend user |
| `ValueObject\TokenResponse` | Represents the DocCheck token response |
| `ViewHelpers\ErrorMessageViewHelper` | Reads and clears session error messages |
| `ViewHelpers\LoginUrlViewHelper` | Builds language-aware local login URLs |

The extension uses strict PHP types and avoids Extbase-specific frontend plugins.

## Middleware Flow

### Login Middleware

Class:

```text
Doc2k\DoccheckAccess\Middleware\LoginMiddleware
```

Route:

```text
/doccheck-access/login/
```

Responsibilities:

1. Detect requests ending in `/doccheck-access/login/`.
2. Read the `ce` query parameter.
3. Resolve the current TYPO3 site language.
4. Resolve the DocCheck authorization language.
5. Load the configured login content element.
6. Read the content-element-specific success page.
7. Fall back to global `successPid` if required.
8. Store TYPO3 context in the frontend session.
9. Build the DocCheck authorization URL.
10. Redirect the visitor to DocCheck.

Session data is stored under:

```text
doccheck_access
```

Stored values:

| Key | Description |
|---|---|
| `contentElementUid` | UID of the login content element |
| `successPid` | Resolved success page UID |
| `languageId` | TYPO3 site language ID |
| `languageCode` | DocCheck language code |

### Callback Middleware

Class:

```text
Doc2k\DoccheckAccess\Middleware\CallbackMiddleware
```

Route:

```text
/doccheck-access/callback/
```

Responsibilities:

1. Detect `/doccheck-access/callback/`.
2. Read the OAuth `code` query parameter.
3. Restore TYPO3 context from the frontend session.
4. Resolve language-specific DocCheck configuration.
5. Exchange the authorization code for an access token.
6. Log in the configured TYPO3 frontend user.
7. Redirect to the configured success page in the stored TYPO3 language.
8. Redirect to the failure page and store an error code if authentication fails.

## OAuth2 Flow

The extension implements the DocCheck authorization-code flow.

### Authorization Request

The login middleware builds an authorization URL using:

- authorization endpoint: `https://auth.doccheck.com/{language}/authorize`
- `grant_type=authorization_code`
- `response_type=code`
- `client_id`
- `redirect_uri`

The redirect URI is taken from `callbackPath` or the language-specific override.

### State Handling

No OAuth state parameter is transmitted to DocCheck.

DocCheck Access Basic does not allow arbitrary additional parameters in the authorization request. TYPO3-specific state such as target page, content element UID and language is therefore stored in the TYPO3 frontend session before the redirect to DocCheck.

### Token Request

The callback middleware exchanges the returned `code` for a token using:

- token endpoint: `tokenEndpoint`
- `grant_type=authorization_code`
- `code`
- `client_id`
- `client_secret`
- `redirect_uri`

The token response is wrapped in:

```text
Doc2k\DoccheckAccess\ValueObject\TokenResponse
```

The extension currently requires a non-empty `access_token` to continue.

## Frontend User Login

After a successful token exchange, the extension logs in the configured TYPO3 frontend user.

The frontend user UID is configured through:

```text
frontendUserUid
```

The extension creates a TYPO3 frontend user session and fetches frontend user group data. Compatibility handling is included for TYPO3 versions where `fetchGroupData()` has different method signatures.

## Error ViewHelper

The error ViewHelper reads the session key:

```text
doccheck_access_error
```

It maps known error codes to fixed English messages, clears the session value and stores the session again.

## Upgrade Notes

### From Early 0.x Versions

Early development versions provided only a scaffold. Later versions added:

- real DocCheck token exchange preparation
- frontend user login
- language-aware redirects
- multi-domain configuration overrides
- session-based error handling
- an error message content element
- localized backend labels
- TYPO3 11 to 14 compatibility work

After upgrading from an early 0.x version:

1. Run the TYPO3 database compare.
2. Clear all TYPO3 caches.
3. Re-save the extension configuration.
4. Check `callbackPath` and language-specific callback overrides.
5. Verify that success and failure pages are visible in all configured languages.
6. Check existing DocCheck Login content elements for content-element-specific success pages.

### TYPO3 13.4 LTS and TYPO3 14.3 LTS

The extension remains usable without Site Sets. Required TypoScript and Page TSconfig are registered by the extension itself, so integrators do not need to include a Site Set or add manual TypoScript includes.

For TYPO3 14.3 LTS installations, pay particular attention to:

- visible translated success and failure pages
- valid localized page slugs
- matching callback domains in DocCheck
- frontend user session behavior

## Known Limitations

The extension intentionally keeps the authentication model small.

It does not:

- create TYPO3 frontend users
- synchronize DocCheck user data
- map DocCheck roles or permissions
- import DocCheck profiles
- support individual TYPO3 frontend user accounts per DocCheck user
- transmit TYPO3 state through OAuth parameters
- require or provide Site Sets in the current version

All successfully authenticated DocCheck visitors are logged in as the same configured TYPO3 frontend user.
