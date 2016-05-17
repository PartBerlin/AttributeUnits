<?php
/**
 * Copyright © PART <info@part-online.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Part\AttributeUnits\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function assembleAdditionalDataEavAttribute(\Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute)
    {
        $iniAdditionalData = [];
        $additionalData = (string) $attribute->getData('additional_data');
        if (!empty($additionalData)) {
            $additionalData = unserialize($additionalData);
            if (is_array($additionalData)) {
                $iniAdditionalData = $additionalData;
            }
        }
        $iniAdditionalData[\Part\AttributeUnits\Model\Unit::ATTRIBUTE_UNIT_INPUT_KEY] =
            $attribute->getData(\Part\AttributeUnits\Model\Unit::ATTRIBUTE_UNIT_INPUT_KEY);
        $attribute->setData('additional_data', serialize($iniAdditionalData));

        return $this;
    }
}
