<?php

declare(strict_types=1);

defined('TYPO3') or die();

call_user_func(static function (): void {
    $additionalColumns = [
        'tx_doccheckaccess_button_label' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:doccheck_access/Resources/Private/Language/locallang_db.xlf:tt_content.tx_doccheckaccess_button_label',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
                'default' => '',
            ],
        ],
        'tx_doccheckaccess_success_pid' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:doccheck_access/Resources/Private/Language/locallang_db.xlf:tt_content.tx_doccheckaccess_success_pid',
            'config' => [
                'type' => 'group',
                'allowed' => 'pages',
                'size' => 1,
                'maxitems' => 1,
                'minitems' => 0,
                'default' => 0,
            ],
        ],
        'tx_doccheckaccess_buttonsize' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:doccheck_access/Resources/Private/Language/locallang_db.xlf:tt_content.tx_doccheckaccess_buttonsize',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:doccheck_access/Resources/Private/Language/locallang_db.xlf:tt_content.tx_doccheckaccess_buttonsize.default', 'default'],
                    ['LLL:EXT:doccheck_access/Resources/Private/Language/locallang_db.xlf:tt_content.tx_doccheckaccess_buttonsize.small', 'small'],
                    ['LLL:EXT:doccheck_access/Resources/Private/Language/locallang_db.xlf:tt_content.tx_doccheckaccess_buttonsize.big', 'big'],
                ],
            ],
        ],
        'tx_doccheckaccess_buttonalign' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:doccheck_access/Resources/Private/Language/locallang_db.xlf:tt_content.tx_doccheckaccess_buttonalign',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:doccheck_access/Resources/Private/Language/locallang_db.xlf:tt_content.tx_doccheckaccess_buttonalign.left', ''],
                    ['LLL:EXT:doccheck_access/Resources/Private/Language/locallang_db.xlf:tt_content.tx_doccheckaccess_buttonalign.center', 'center'],
                    ['LLL:EXT:doccheck_access/Resources/Private/Language/locallang_db.xlf:tt_content.tx_doccheckaccess_buttonalign.right', 'right'],
                ],
            ],
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $additionalColumns);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
        [
            'LLL:EXT:doccheck_access/Resources/Private/Language/locallang_db.xlf:tt_content.CType.doccheckaccess_login',
            'doccheckaccess_login',
            'mimetypes-x-content-login',
        ],
        'CType',
        'doccheck_access'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
        [
            'LLL:EXT:doccheck_access/Resources/Private/Language/locallang_db.xlf:tt_content.CType.doccheckaccess_error_message',
            'doccheckaccess_error_message',
            'mimetypes-x-content-text',
        ],
        'CType',
        'doccheck_access'
    );

    $GLOBALS['TCA']['tt_content']['types']['doccheckaccess_login'] = [
        'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                --palette--;;general,
                --palette--;;headers,
                tx_doccheckaccess_buttonsize,
                tx_doccheckaccess_buttonalign,
                tx_doccheckaccess_button_label,
                tx_doccheckaccess_success_pid,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                --palette--;;frames,
                --palette--;;appearanceLinks,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;hidden,
                --palette--;;access,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                categories,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                rowDescription,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        ',
    ];
    $GLOBALS['TCA']['tt_content']['types']['doccheckaccess_error_message'] = [
        'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                --palette--;;general,
                --palette--;;headers,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                --palette--;;frames,
                --palette--;;appearanceLinks,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;hidden,
                --palette--;;access,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                categories,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                rowDescription,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        ',
    ];
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['doccheckaccess_login'] = 'mimetypes-x-content-login';
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['doccheckaccess_error_message'] = 'mimetypes-x-content-text';
});
