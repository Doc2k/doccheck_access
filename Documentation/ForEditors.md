# For Editors

This section explains how to use the DocCheck content elements in the TYPO3 backend.

## Available Content Elements

The extension provides two content elements:

| Content element | CType | Purpose |
|---|---|---|
| DocCheck Login | `doccheckaccess_login` | Displays the DocCheck login button |
| DocCheck Error Message | `doccheckaccess_error_message` | Displays the latest authentication error |

Both content elements are available in the new content element wizard after Page TSconfig has been included.

## DocCheck Login

The DocCheck Login content element displays a login button that starts the DocCheck authentication process.

The button does not link directly to DocCheck. It links to the local TYPO3 login route:

```text
/doccheck-access/login/?ce={contentElementUid}
```

TYPO3 then prepares the session and redirects the visitor to DocCheck.

### Fields

| Field | Description |
|---|---|
| Header | Standard TYPO3 content element header |
| Button Label | Optional custom button text |
| Success Page | Optional success page for this login element |
| Button Size | Medium, small or large |
| Button Alignment | Left, centered or right |

The content element also provides standard TYPO3 tabs and palettes such as Appearance, Language, Access, Categories and Notes.

### Success Page

The success page can be set directly on the login content element.

If no success page is selected on the content element, the global success page from extension configuration is used.

The content-element-specific success page is useful in multilingual sites because each translated login element can point to a different translated target page.

## DocCheck Error Message

The DocCheck Error Message content element displays the latest DocCheck authentication error stored in the visitor's frontend session.

Typical placement:

- on the configured failure page
- above or near a DocCheck Login content element

The message is removed from the session after it has been displayed once.

## Multilingual Editing

When translating a DocCheck Login content element, TYPO3 uses the localized content element UID for the login link.

This allows each language version to define:

- its own button label
- its own success page
- language-specific page slugs

Important:

- The translated success page must be visible.
- The translated failure page must be visible.
- Hidden translated pages can cause 404 redirects after login.
- Localized page slugs must be valid and routable.
