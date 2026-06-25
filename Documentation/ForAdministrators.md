# For Administrators

This section covers extension configuration, DocCheck client setup, multi-domain handling and operational notes.

## Extension Configuration

Configure the extension in TYPO3 Extension Configuration.

### Required Settings

| Key | Description |
|---|---|
| `clientId` | DocCheck OAuth client ID |
| `clientSecret` | DocCheck OAuth client secret |
| `callbackPath` | Absolute callback URL registered at DocCheck |
| `failurePid` | Page UID used when authentication fails |
| `frontendUserUid` | TYPO3 frontend user UID used for successful logins |

The `callbackPath` key stores the redirect URI used for DocCheck OAuth. It must be the full callback URL, for example:

```text
https://example.com/doccheck-access/callback/
```

### Optional Settings

| Key | Description |
|---|---|
| `successPid` | Global fallback success page UID |
| `frontendUserGroupUid` | Reserved for future use |
| `tokenEndpoint` | Token endpoint, defaults to `https://auth.doccheck.com/token` |

The success page can be configured globally with `successPid` or individually on each DocCheck Login content element. The content element setting takes precedence.

## Configuration Validation

Administration configuration errors are treated as installation errors and throw `RuntimeException`.

The extension validates:

- missing `clientId`
- missing `clientSecret`
- missing or invalid `callbackPath`
- `frontendUserUid <= 0`
- `failurePid <= 0`
- missing success page when neither the content element nor global configuration provides one

## Multi-Domain and Language-Specific Overrides

By default, the extension uses the global `clientId`, `clientSecret` and `callbackPath` for all languages.

This works for installations where languages share one domain:

```text
https://example.com/
https://example.com/de/
https://example.com/fr/
```

For multi-domain language setups, DocCheck usually requires one OAuth client per callback domain:

```text
https://example.de/
https://example.com/
https://example.fr/
```

For these setups, configure language-specific overrides:

```text
de_clientId
de_clientSecret
de_callbackPath

en_clientId
en_clientSecret
en_callbackPath

fr_clientId
fr_clientSecret
fr_callbackPath

it_clientId
it_clientSecret
it_callbackPath

nl_clientId
nl_clientSecret
nl_callbackPath

es_clientId
es_clientSecret
es_callbackPath
```

If an override is configured and non-empty, it takes precedence over the global value. If an override is empty, the global configuration is used as fallback.

Supported DocCheck authorization languages:

- `de`
- `en`
- `fr`
- `it`
- `nl`
- `es`

Other site languages fall back to `en` for the DocCheck authorization endpoint.

## Callback URL

The local callback path handled by the extension is:

```text
/doccheck-access/callback/
```

For multi-domain installations, configure different absolute callback URLs through language-specific `*_callbackPath` settings, but keep the path itself as `/doccheck-access/callback/`.

Example:

```text
de_callbackPath = https://example.de/doccheck-access/callback/
en_callbackPath = https://example.com/doccheck-access/callback/
fr_callbackPath = https://example.fr/doccheck-access/callback/
```

## Frontend User

After a successful token exchange, the configured TYPO3 frontend user is logged in.

The configured user must:

- exist
- not be deleted
- not be disabled
- have the frontend user groups required to access protected content

All successfully authenticated DocCheck visitors share this TYPO3 frontend user identity.

## Success and Failure Pages

Success and failure pages must be:

- visible
- accessible
- translated if used in translated site languages
- equipped with valid localized slugs

If a translated target page is hidden, TYPO3 will not generate or resolve it as an accessible localized URL.

## Error Handling

Authentication-related errors are stored in the frontend session under:

```text
doccheck_access_error
```

The DocCheck Error Message content element can display the following error codes:

| Code | Meaning |
|---|---|
| `missing_code` | DocCheck returned no authorization code or the login was cancelled |
| `token_exchange_failed` | The authorization code could not be exchanged for a token |
| `frontend_login_failed` | The configured TYPO3 frontend user could not be logged in |
| `missing_content_element` | The login request did not contain a valid content element UID |
| `invalid_content_element` | The referenced content element does not exist or is not a DocCheck Login element |

## Security Notes

- Client secrets are stored in TYPO3 extension configuration.
- Access to TYPO3 system configuration should be restricted to trusted administrators.
- The configured callback URL must exactly match the URL registered at DocCheck.
- The failure page should not expose sensitive implementation details.
- The error messages shown to visitors are intentionally generic.

## Troubleshooting

### Callback returns 404

Check that the configured DocCheck callback URL points to:

```text
/doccheck-access/callback/
```

Also verify that the language-specific `*_callbackPath` override matches the domain registered at DocCheck.

### Success redirect returns 404

Check that the success page:

- exists
- is visible
- is translated if used in a translated site language
- has a valid localized slug
- is accessible for the logged-in frontend user

### Error message does not appear

Check that the DocCheck Error Message content element is present on the failure page and that TypoScript is included.

The element is rendered uncached. If it is manually integrated elsewhere, ensure the output is not cached.

### Login succeeds but protected content is still inaccessible

Check the configured frontend user and its frontend user groups. Access to protected content depends on TYPO3 frontend group permissions.
