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

    public function getClientId(): string
    {
        return $this->getStringValue('clientId');
    }

    public function getClientSecret(): string
    {
        return $this->getStringValue('clientSecret');
    }

    public function getCallbackPath(): string
    {
        return $this->getStringValue('callbackPath');
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

    public function assertRequiredAdminConfiguration(): void
    {
        if (trim($this->getClientId()) === '') {
            throw new \RuntimeException(
                'DocCheck Access configuration error: clientId is missing.',
                1719001001
            );
        }

        if (trim($this->getClientSecret()) === '') {
            throw new \RuntimeException(
                'DocCheck Access configuration error: clientSecret is missing.',
                1719001002
            );
        }

        $redirectUri = trim($this->getConfiguredRedirectUri());
        if ($redirectUri === '' || !$this->isValidAbsoluteHttpUri($redirectUri)) {
            throw new \RuntimeException(
                'DocCheck Access configuration error: redirectUri or callbackPath is missing or invalid.',
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

    private function getConfiguredRedirectUri(): string
    {
        return $this->getStringValue('callbackPath');
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
