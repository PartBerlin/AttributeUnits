<?php
/**
 * Copyright © PART <info@part-online.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Part\AttributeUnits\Model\Plugin;

class EavAttribute
{
    private $unitHelper;

    public function __construct(\Part\AttributeUnits\Helper\Data $unitHelper)
    {
        $this->unitHelper = $unitHelper;
    }

    public function beforeSave(\Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute)
    {
        $this->unitHelper->assembleAdditionalDataEavAttribute($attribute);
    }
}
