<?php
/**
 * Copyright © PART <info@part-online.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Part\AttributeUnits\Test\Unit\Helper;

class DataTest extends \PHPUnit_Framework_TestCase
{
    private $attributeMock;

    private $helper;

    public function setUp()
    {
        $this->attributeMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Eav\Attribute',
            ['getData', 'setData'],
            [],
            '',
            false
        );
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->helper = $objectManager->getObject('Part\AttributeUnits\Helper\Data', []);
    }

    /**
     * @dataProvider dataAssembleAdditionalDataEavAttribute
     */
    public function testAssembleAdditionalDataEavAttribute($additionData, $unit, $decimalPlaces, $expectedSetData)
    {
        $this->attributeMock
            ->expects($this->exactly(3))
            ->method('getData')
            ->will($this->returnValueMap(
                [
                    ['additional_data', null, serialize($additionData)],
                    ['attribute_unit', null, $unit],
                    ['attribute_decimal_places', null, $decimalPlaces],
                ]
            ));
        $this->attributeMock
            ->expects($this->once())
            ->method('setData')
            ->with('additional_data', serialize($expectedSetData));
        $this->helper->assembleAdditionalDataEavAttribute($this->attributeMock);
    }

    public function dataAssembleAdditionalDataEavAttribute()
    {
        return [
            [
                [],
                'cm',
                '3',
                ['attribute_unit' => 'cm', 'attribute_decimal_places' => '3'],
            ],
            [
                ['foo' => 'bar'],
                'cm',
                '4',
                ['foo' => 'bar', 'attribute_unit' => 'cm', 'attribute_decimal_places' => '4'],
            ],
            [
                ['foo' => 'bar', 'attribute_unit' => 'm', 'attribute_decimal_places' => '3'],
                'cm',
                '5',
                ['foo' => 'bar', 'attribute_unit' => 'cm', 'attribute_decimal_places' => '5'],
            ],
        ];
    }
}
