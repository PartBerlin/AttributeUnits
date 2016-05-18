<?php
/**
 * Copyright © PART <info@part-online.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Part\AttributeUnits\Helper\Plugin;

use Part\AttributeUnits\Model\Unit;

class Output
{
    private $eavConfig;

    private $localeResolver;

    public function __construct(
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\Locale\ResolverInterface $localeResolver
    ) {
        $this->eavConfig = $eavConfig;
        $this->localeResolver = $localeResolver;
    }

    public function beforeProcess(\Magento\Catalog\Helper\Output $outputHelper, $method, $result, $params)
    {
        $productAttributeMethod = 'productAttribute';
        if ($method === $productAttributeMethod &&
            !in_array($this, $outputHelper->getHandlers($productAttributeMethod))) {
            $outputHelper->addHandler($productAttributeMethod, $this);
        }
    }

    public function productAttribute(\Magento\Catalog\Helper\Output $outputHelper, $result, $params)
    {
        $attribute = $this->eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $params['attribute']);
        if ($attribute && $attribute->getId()) {
            $additionalData = unserialize($attribute->getAdditionalData());
            if (is_array($additionalData) &&
                isset($additionalData[Unit::ATTRIBUTE_UNIT_INPUT_KEY]) &&
                $additionalData[Unit::ATTRIBUTE_UNIT_INPUT_KEY]) {
                $numberFormatter = new \NumberFormatter($this->localeResolver->getLocale(), \NumberFormatter::DECIMAL);
                $numberFormatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 2);
                $result = sprintf(
                    "%s %s",
                    $numberFormatter->format($result),
                    $additionalData[Unit::ATTRIBUTE_UNIT_INPUT_KEY]
                );
            }
        }

        return $result;
    }
}
