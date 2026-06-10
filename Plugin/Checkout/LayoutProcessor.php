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

namespace SystemCode\CustomerStreetLines\Plugin\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessor as Subject;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use SystemCode\CustomerStreetLines\Api\ConfigInterface;

/**
 * Provide configured behavior.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class LayoutProcessor
{
    /**
     * Initialize dependencies.
     *
     * @param ConfigInterface $config
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ConfigInterface $config,
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Execute after process.
     *
     * @param Subject $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(Subject $subject, array $jsLayout): array
    {
        if (!$this->config->isActive()) {
            return $jsLayout;
        }

        $numStreetLines = (int) $this->scopeConfig->getValue(
            'customer/address/street_lines',
            ScopeInterface::SCOPE_STORE
        );

        $jsLayout = $this->applyToShipping($jsLayout, $numStreetLines);

        return $this->applyToBilling($jsLayout, $numStreetLines);
    }

    /**
     * Apply to shipping.
     *
     * @param array $jsLayout
     * @param int $numStreetLines
     * @return array
     */
    private function applyToShipping(array $jsLayout, int $numStreetLines): array
    {
        if (!isset(
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['street']
        )) {
            return $jsLayout;
        }

        $street = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['street'];

        $this->applyConfig($street, $numStreetLines);

        return $jsLayout;
    }

    /**
     * Apply to billing.
     *
     * @param array $jsLayout
     * @param int $numStreetLines
     * @return array
     */
    private function applyToBilling(array $jsLayout, int $numStreetLines): array
    {
        $paymentsList = $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
            ['children']['payment']['children']['payments-list']['children'] ?? null;

        if (!is_array($paymentsList)) {
            return $jsLayout;
        }

        foreach (array_keys($paymentsList) as $paymentMethodForm) {
            $paymentMethodCode = str_replace('-form', '', $paymentMethodForm);
            $formKey = $paymentMethodCode . '-form';

            if (!isset(
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                ['children']['payment']['children']['payments-list']['children'][$formKey]
                ['children']['form-fields']['children']['street']
            )) {
                continue;
            }

            $street = &$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                ['children']['payment']['children']['payments-list']['children'][$formKey]
                ['children']['form-fields']['children']['street'];

            $this->applyConfig($street, $numStreetLines);
        }

        return $jsLayout;
    }

    /**
     * Apply config.
     *
     * @param array $streetField
     * @param int $numStreetLines
     * @return void
     */
    private function applyConfig(array &$streetField, int $numStreetLines): void
    {
        for ($i = 0; $i < $numStreetLines; $i++) {
            $label = $this->config->getLineLabel($i);

            if (!isset($streetField['children'][$i])) {
                continue;
            }

            if ($label !== null) {
                $streetField['children'][$i]['label'] = $label;
            }

            if ($i === 0) {
                continue;
            }

            if (!$this->config->isLineRequired($i)) {
                $streetField['children'][$i]['required'] = false;
                unset($streetField['children'][$i]['validation']['required-entry']);
                continue;
            }

            $streetField['children'][$i]['required'] = true;
            $streetField['children'][$i]['validation']['required-entry'] = true;
            $streetField['children'][$i]['additionalClasses'] = trim(
                ($streetField['children'][$i]['additionalClasses'] ?? '') . ' required _required'
            );
        }
    }
}
