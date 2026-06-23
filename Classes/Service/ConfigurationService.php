<?php

declare(strict_types=1);

namespace Doc2k\DoccheckAccess\Service;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

final class ConfigurationService
{
    private ExtensionConfiguration $extensionConfiguration;

    public function __construct(ExtensionConfiguration $extensionConfiguration)
    {
        $this->extensionConfiguration = $extensionConfiguration;
    }

    public function getClientId(?string $language = null): string
    {
        return $this->getLanguageAwareStringValue('clientId', $language);
    }

    public function getClientSecret(?string $language = null): string
    {
        return $this->getLanguageAwareStringValue('clientSecret', $language);
    }

    public function getCallbackPath(?string $language = null): string
    {
        return $this->getLanguageAwareStringValue('callbackPath', $language);
    }

    public function getSuccessPid(): int
    {
        return $this->getIntegerValue('successPid');
    }

    public function getFailurePid(): int
    {
        return $this->getIntegerValue('failurePid');
    }

    public function getFrontendUserUid(): int
    {
        return $this->getIntegerValue('frontendUserUid');
    }

    public function getFrontendUserGroupUid(): int
    {
        return $this->getIntegerValue('frontendUserGroupUid');
    }

    public function getTokenEndpoint(): string
    {
        return $this->getStringValue('tokenEndpoint', 'https://auth.doccheck.com/token');
    }

    public function assertRequiredAdminConfiguration(?string $language = null): void
    {
        if (trim($this->getClientId($language)) === '') {
            throw new \RuntimeException(
                'DocCheck Access configuration error: clientId is missing.',
                1719001001
            );
        }

        if (trim($this->getClientSecret($language)) === '') {
            throw new \RuntimeException(
                'DocCheck Access configuration error: clientSecret is missing.',
                1719001002
            );
        }

        $redirectUri = trim($this->getConfiguredRedirectUri($language));
        if ($redirectUri === '' || !$this->isValidAbsoluteHttpUri($redirectUri)) {
            throw new \RuntimeException(
                'DocCheck Access configuration error: callbackPath is missing or invalid.',
                1719001003
            );
        }

        if ($this->getFrontendUserUid() <= 0) {
            throw new \RuntimeException(
                'DocCheck Access configuration error: frontendUserUid/userId must be greater than 0.',
                1719001004
            );
        }

        if ($this->getFailurePid() <= 0) {
            throw new \RuntimeException(
                'DocCheck Access configuration error: failurePid must be greater than 0.',
                1719001005
            );
        }
    }

    public function assertSuccessPidAvailable(int $successPid): void
    {
        if ($successPid > 0) {
            return;
        }

        throw new \RuntimeException(
            'DocCheck Access configuration error: no successPid is configured globally or on the content element.',
            1719001006
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getAll(): array
    {
        try {
            $configuration = $this->extensionConfiguration->get('doccheck_access');
        } catch (\Throwable $exception) {
            return [];
        }

        return is_array($configuration) ? $configuration : [];
    }

    private function getLanguageAwareStringValue(
        string $key,
        ?string $language = null,
        string $default = ''
    ): string {
        $language = strtolower(trim((string)$language));

        if ($language !== '') {
            $languageValue = $this->getStringValue($language . '_' . $key);

            if (trim($languageValue) !== '') {
                return trim($languageValue);
            }
        }

        return trim($this->getStringValue($key, $default));
    }

    private function getStringValue(string $key, string $default = ''): string
    {
        $value = $this->getAll()[$key] ?? $default;

        return is_scalar($value) ? (string)$value : $default;
    }

    private function getIntegerValue(string $key, int $default = 0): int
    {
        $value = $this->getAll()[$key] ?? $default;

        return is_numeric($value) ? (int)$value : $default;
    }

    private function getConfiguredRedirectUri(?string $language = null): string
    {
        return $this->getCallbackPath($language);
    }

    private function isValidAbsoluteHttpUri(string $uri): bool
    {
        if (filter_var($uri, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        $scheme = parse_url($uri, PHP_URL_SCHEME);

        return $scheme === 'http' || $scheme === 'https';
    }
}