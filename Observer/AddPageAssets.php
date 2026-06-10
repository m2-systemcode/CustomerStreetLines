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

namespace SystemCode\CustomerStreetLines\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Page\Config as PageConfig;
use SystemCode\CustomerStreetLines\Api\ConfigInterface;
use Magento\Framework\App\Request\Http as HttpRequest;

class AddPageAssets implements ObserverInterface
{
    /**
     * Initialize dependencies.
     *
     * @param ConfigInterface $config
     * @param PageConfig $pageConfig
     * @param HttpRequest $request
     */
    public function __construct(
        private readonly ConfigInterface $config,
        private readonly PageConfig $pageConfig,
        private readonly HttpRequest $request
    ) {
    }
    
    /**
     * Handle execute.
     *
     * @param Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(Observer $observer): void
    {
        if (!in_array($this->request->getFullActionName(), ConfigInterface::ALLOWED_ACTIONS, true)) {
            return;
        }

        if (!$this->config->isActive()) {
            return;
        }

        $this->pageConfig->addPageAsset('SystemCode_CustomerStreetLines::css/customer-street-lines.css');
    }
}
