<?php
/**
 * Copyright © PART <info@part-online.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Part\AttributeUnits\Observer;

use Magento\Framework\Module\Manager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddFieldsToAttributeObserver implements ObserverInterface
{
    private $moduleManager;

    public function __construct(Manager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    public function execute(Observer $observer)
    {
        if (!$this->moduleManager->isOutputEnabled('Part_AttributeUnits')) {
            return;
        }

        $form = $observer->getForm();
        $fieldset = $form->getElement('front_fieldset');
        $fieldset->addField(
            \Part\AttributeUnits\Model\Unit::ATTRIBUTE_UNIT_INPUT_KEY,
            'text',
            [
                'name' => 'attribute_unit',
                'label' => __('Attribute unit'),
                'title' => __('Attribute unit'),
                'note' => __('The unit that will be shown in frontend'),
            ]
        );
        $fieldset->addField(
            \Part\AttributeUnits\Model\Unit::ATTRIBUTE_DECIMAL_PLACES,
            'text',
            [
                'name' => 'attribute_decimal_places',
                'label' => __('Attribute Decimal Places'),
                'title' => __('Attribute Decimal Places'),
                'note' => __('The number of decimal places shown in frontend'),
            ]
        );
    }
}
