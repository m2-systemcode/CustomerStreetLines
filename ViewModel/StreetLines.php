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

namespace SystemCode\CustomerStreetLines\ViewModel;

use Magento\Customer\Helper\Address;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use SystemCode\CustomerStreetLines\Api\ConfigInterface;

class StreetLines implements ArgumentInterface
{
    /**
     * Initialize dependencies.
     *
     * @param ConfigInterface $config
     * @param Address $addressHelper
     */
    public function __construct(
        private readonly ConfigInterface $config,
        private readonly Address $addressHelper
    ) {
    }

    /**
     * Retrieve street validation class.
     *
     * @return ?string
     */
    public function getStreetValidationClass(): ?string
    {
        return $this->addressHelper->getAttributeValidationClass('street');
    }

    /**
     * Retrieve street line count.
     *
     * @return int
     */
    public function getStreetLineCount(): int
    {
        return $this->addressHelper->getStreetLines();
    }

    /**
     * Retrieve line label.
     *
     * @param int $lineIndex
     * @return ?string
     */
    public function getLineLabel(int $lineIndex): ?string
    {
        return $this->config->getLineLabel($lineIndex);
    }

    /**
     * Check whether line required.
     *
     * @param int $lineIndex
     * @return bool
     */
    public function isLineRequired(int $lineIndex): bool
    {
        return $this->config->isLineRequired($lineIndex);
    }

    /**
     * Retrieve required field class.
     *
     * @param int $lineIndex
     * @return string
     */
    public function getRequiredFieldClass(int $lineIndex): string
    {
        return $this->isLineRequired($lineIndex) ? ' required _required' : '';
    }

    /**
     * Retrieve required input class.
     *
     * @param int $lineIndex
     * @return string
     */
    public function getRequiredInputClass(int $lineIndex): string
    {
        return $this->isLineRequired($lineIndex) ? ' required-entry' : '';
    }

    /**
     * Retrieve address edit line label.
     *
     * @param int $lineIndex
     * @return string
     */
    public function getAddressEditLineLabel(int $lineIndex): string
    {
        $custom = $this->getLineLabel($lineIndex);

        if ($custom !== null) {
            return $custom;
        }

        return (string) __('Street Address: Line %1', $lineIndex + 1);
    }

    /**
     * Retrieve register first line label.
     *
     * @param string $defaultStreetLabel
     * @return string
     */
    public function getRegisterFirstLineLabel(string $defaultStreetLabel): string
    {
        $custom = $this->getLineLabel(0);

        if ($custom !== null) {
            return $custom;
        }

        return $defaultStreetLabel;
    }

    /**
     * Retrieve register additional line label.
     *
     * @param int $lineIndex
     * @return string
     */
    public function getRegisterAdditionalLineLabel(int $lineIndex): string
    {
        $custom = $this->getLineLabel($lineIndex);

        if ($custom !== null) {
            return $custom;
        }

        return (string) __('Address');
    }
}
