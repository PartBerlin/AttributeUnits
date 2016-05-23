<?php
/**
 * Copyright © PART <info@part-online.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Part\AttributeUnits\Observer;

use Magento\Framework\Module\Manager;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Part\AttributeUnits\Model\Unit;

class AddAdditionalValuesToAttributeObserver implements ObserverInterface
{
    private $moduleManager;

    public function __construct(Manager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    public function execute(EventObserver $observer)
    {
        if (!$this->moduleManager->isOutputEnabled('Part_AttributeUnits')) {
            return;
        }
        $attribute = $observer->getAttribute();
        $data = $attribute->getData();
        if (isset($data['additional_data'])) {
            $additionalData = unserialize($data['additional_data']);
            if (isset($additionalData[Unit::ATTRIBUTE_UNIT_INPUT_KEY])) {
                $attribute->setData(Unit::ATTRIBUTE_UNIT_INPUT_KEY, $additionalData[Unit::ATTRIBUTE_UNIT_INPUT_KEY]);
            }
        }
    }
}
