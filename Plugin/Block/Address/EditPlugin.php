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

namespace SystemCode\CustomerStreetLines\Plugin\Block\Address;

use Magento\Customer\Block\Address\Edit;
use SystemCode\CustomerStreetLines\Api\ConfigInterface;
use SystemCode\CustomerStreetLines\ViewModel\StreetLines;

class EditPlugin
{
    /**
     * Initialize dependencies.
     *
     * @param ConfigInterface $config
     * @param StreetLines $streetLinesViewModel
     */
    public function __construct(
        private readonly ConfigInterface $config,
        private readonly StreetLines $streetLinesViewModel
    ) {
    }

    /**
     * Execute before to html.
     *
     * @param Edit $subject
     * @return void
     */
    public function beforeToHtml(Edit $subject): void
    {
        if (!$this->config->isActive()) {
            return;
        }

        $subject->setData('street_lines_view_model', $this->streetLinesViewModel);

        if ($subject->getTemplate() === 'Magento_Customer::address/edit.phtml') {
            $subject->setTemplate('SystemCode_CustomerStreetLines::address/edit.phtml');
        }
    }
}
