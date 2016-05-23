<?php
/**
 * Copyright © PART <info@part-online.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Part\AttributeUnits\Test\Unit\Observer;

class AddAdditionalValuesToAttributeObserverTest extends \PHPUnit_Framework_TestCase
{
    private $moduleManagerMock;

    private $attributeMock;

    private $eventObserverMock;

    private $observer;

    public function setUp()
    {
        $this->moduleManagerMock = $this->getMock(
            '\Magento\Framework\Module\Manager',
            ['isOutputEnabled'],
            [],
            '',
            false
        );
        $this->attributeMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Eav\Attribute',
            ['getData', 'setData'],
            [],
            '',
            false
        );
        $this->eventObserverMock = $this->getMock(
            '\Magento\Framework\Event\Observer',
            ['getAttribute'],
            [],
            '',
            false
        );
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->observer = $objectManager->getObject(
            'Part\AttributeUnits\Observer\AddAdditionalValuesToAttributeObserver',
            [
                'moduleManager' => $this->moduleManagerMock,
            ]
        );
    }

    /**
     * @dataProvider dataExecute
     */
    public function testExecute($isOutputEnabled, array $data, $setDataCount, $setData)
    {
        $this->moduleManagerMock
            ->expects($this->once())
            ->method('isOutputEnabled')
            ->willReturn($isOutputEnabled);
        $this->eventObserverMock
            ->expects($this->exactly((int)$isOutputEnabled))
            ->method('getAttribute')
            ->willreturn($this->attributeMock);
        $this->attributeMock
            ->expects($this->exactly((int)$isOutputEnabled))
            ->method('getData')
            ->willReturn($data);
        $this->attributeMock
            ->expects($this->exactly($setDataCount))
            ->method('setData')
            ->with('attribute_unit', $setData);
        $this->observer->execute($this->eventObserverMock);
    }

    public function dataExecute()
    {
        return [
            [
                false,
                ['additional_data' => serialize(['attribute_unit' => 'mm'])],
                0,
                'mm',
            ],
            [
                true,
                [],
                0,
                'mm',
            ],
            [
                true,
                ['additional_data' => serialize([])],
                0,
                'mm',
            ],
            [
                true,
                ['additional_data' => serialize(['attribute_unit' => 'mm'])],
                1,
                'mm',
            ],
        ];
    }
}
