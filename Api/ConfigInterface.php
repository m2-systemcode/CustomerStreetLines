<?php
/**
 * NOTICE OF LICENSE
 *
 * @category  SystemCode
 * @package   Systemcode_CustomerStreetLines
 * @author    Eduardo Diogo Dias <contato@systemcode.com.br>
 * @copyright System Code LTDA - ME
 * @license   http://opensource.org/licenses/osl-3.0.php
 */
declare(strict_types=1);

namespace SystemCode\CustomerStreetLines\Api;

interface ConfigInterface
{
    public const string XML_PATH_ENABLED = 'customerstreetlines/general/enabled';
    public const string XML_PATH_LINE = 'customerstreetlines/labels/line_%d';
    public const string XML_PATH_REQUIRED = 'customerstreetlines/required/line_%d_required';
    public const array ALLOWED_ACTIONS = [
        'customer_account_create',
        'customer_address_form',
        'checkout_index_index',
    ];

    /**
     * Check whether enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Retrieve line label.
     *
     * @param int $lineIndex
     * @return ?string
     */
    public function getLineLabel(int $lineIndex): ?string;

    /**
     * Check whether line required.
     *
     * @param int $lineIndex
     * @return bool
     */
    public function isLineRequired(int $lineIndex): bool;

    /**
     * Check whether entity has any label.
     *
     * @return bool
     */
    public function hasAnyLabel(): bool;

    /**
     * Check whether active.
     *
     * @return bool
     */
    public function isActive(): bool;
}
