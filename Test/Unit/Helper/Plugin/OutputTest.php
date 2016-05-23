<?php
/**
 * Copyright © PART <info@part-online.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Part\AttributeUnits\Test\Unit\Helper\Plugin;

class OutputTest extends \PHPUnit_Framework_TestCase
{
    private $eavConfigMock;

    private $localeResolverMock;

    private $outputHelperMock;

    private $outputHandler;

    public function setUp()
    {
        $this->eavConfigMock = $this->getMock(
            '\Magento\Eav\Model\Config',
            ['getAttribute'],
            [],
            '',
            false
        );
        $this->localeResolverMock = $this->getMock(
            '\Magento\Framework\Locale\ResolverInterface',
            [
                'getDefaultLocalePath',
                'setDefaultLocale',
                'getLocale',
                'getDefaultLocale',
                'setLocale',
                'emulate',
                'revert'
            ],
            [],
            '',
            false
        );
        $this->outputHelperMock = $this->getMock(
            '\Magento\Catalog\Helper\Output',
            ['getHandlers', 'addHandler'],
            [],
            '',
            false
        );
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->outputHandler = $objectManager->getObject(
            'Part\AttributeUnits\Helper\Plugin\Output',
            [
                'eavConfig' => $this->eavConfigMock,
                'localeResolver' => $this->localeResolverMock,
            ]
        );
    }

    /**
     * @dataProvider dataBeforeProcess
     */
    public function testBeforeProcess($method, $getHandlersCount, $getHandlerContains, $addHandlerCount)
    {
        $getHandlersReturn = [];
        if ($getHandlerContains) {
            $getHandlersReturn[] = $this->outputHandler;
        }
        $this->outputHelperMock
            ->expects($this->exactly($getHandlersCount))
            ->method('getHandlers')
            ->willReturn($getHandlersReturn);
        $this->outputHelperMock
            ->expects($this->exactly($addHandlerCount))
            ->method('addHandler')
            ->with('productAttribute', $this->outputHandler);
        $this->outputHandler->beforeProcess($this->outputHelperMock, $method, null, null);
    }

    /**
     * @dataProvider dataProductAttribute
     */
    public function testProductAttribute($result, array $params, array $additionalData, $locale, $expectedResult)
    {
        $attributeMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Eav\Attribute',
            ['getId', 'getAdditionalData'],
            [],
            '',
            false
        );
        $attributeMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn(5);
        $attributeMock
            ->expects($this->once())
            ->method('getAdditionalData')
            ->willReturn(serialize($additionalData));
        $this->eavConfigMock
            ->expects($this->once())
            ->method('getAttribute')
            ->with(\Magento\Catalog\Model\Product::ENTITY, $params['attribute'])
            ->willReturn($attributeMock);
        $this->localeResolverMock
            ->expects($this->any())
            ->method('getLocale')
            ->willReturn($locale);
        $this->assertSame(
            $expectedResult,
            $this->outputHandler->productAttribute($this->outputHelperMock, $result, $params)
        );
    }

    public function dataBeforeProcess()
    {
        return [
            [
                'categoryAttribute',
                0,
                false,
                0,
            ],
            [
                'productAttribute',
                1,
                true,
                0,
            ],
            [
                'productAttribute',
                1,
                false,
                1,
            ],
        ];
    }

    public function dataProductAttribute()
    {
        return [
            [
                1.2,
                ['attribute' => 'weight'],
                [],
                'de_DE',
                1.2,
            ],
            [
                1.2,
                ['attribute' => 'weight'],
                ['attribute_unit' => 'mm'],
                'de_DE',
                '1,20 mm',
            ],
            [
                1.564,
                ['attribute' => 'weight'],
                ['attribute_unit' => 'mm'],
                'en_US',
                '1.564 mm',
            ],
            [
                11236.5,
                ['attribute' => 'weight'],
                ['attribute_unit' => 'mm'],
                'en_US',
                '11,236.50 mm',
            ],
        ];
    }
}
