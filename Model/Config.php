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

namespace SystemCode\CustomerStreetLines\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use SystemCode\CustomerStreetLines\Api\ConfigInterface;

class Config implements ConfigInterface
{
    /**
     * Initialize dependencies.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Check whether enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve line label.
     *
     * @param int $lineIndex
     * @return ?string
     */
    public function getLineLabel(int $lineIndex): ?string
    {
        $lineNumber = $lineIndex + 1;
        if ($lineNumber < 1 || $lineNumber > 4) {
            return null;
        }

        $value = trim((string) $this->scopeConfig->getValue(
            sprintf(self::XML_PATH_LINE, $lineNumber),
            ScopeInterface::SCOPE_STORE
        ));

        return $value !== '' ? $value : null;
    }

    /**
     * Check whether line required.
     *
     * @param int $lineIndex
     * @return bool
     */
    public function isLineRequired(int $lineIndex): bool
    {
        $lineNumber = $lineIndex + 1;
        if ($lineNumber < 2 || $lineNumber > 4) {
            return false;
        }

        return $this->scopeConfig->isSetFlag(
            sprintf(self::XML_PATH_REQUIRED, $lineNumber),
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check whether entity has any label.
     *
     * @return bool
     */
    public function hasAnyLabel(): bool
    {
        for ($i = 0; $i < 4; $i++) {
            if ($this->getLineLabel($i) !== null) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check whether active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isEnabled();
    }
}
