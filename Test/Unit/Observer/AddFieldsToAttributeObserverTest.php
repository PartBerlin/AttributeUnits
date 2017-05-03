<?php
/**
 * Copyright © PART <info@part-online.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Part\AttributeUnits\Test\Unit\Observer;

class AddFieldsToAttributeObserverTest extends \PHPUnit_Framework_TestCase
{
    private $moduleManagerMock;

    private $formMock;

    private $eventObserverMock;

    private $observer;

    public function setUp()
    {
        $this->moduleManagerMock = $this->getMock(
            '\Magento\Framework\Module\Manager',
            [],
            [],
            '',
            false
        );
        $this->eventObserverMock = $this->getMock(
            '\Magento\Framework\Event\Observer',
            ['getForm'],
            [],
            '',
            false
        );
        $this->formMock = $this->getMock('\Magento\Framework\Data\Form', ['getElement'], [], '', false);

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->observer = $objectManager->getObject(
            'Part\AttributeUnits\Observer\AddFieldsToAttributeObserver',
            [
                'moduleManager' => $this->moduleManagerMock,
            ]
        );
    }

    /**
     * @dataProvider dataExecute
     */
    public function testExecute($isOutputEnabled, $methodCallCount)
    {
        $this->moduleManagerMock
            ->expects($this->once())
            ->method('isOutputEnabled')
            ->willReturn($isOutputEnabled);
        $this->eventObserverMock
            ->expects($this->exactly((int) $isOutputEnabled))
            ->method('getForm')
            ->willReturn($this->formMock);
        $element = $this->getMock('Magento\Framework\Data\Form\Element\AbstractElement', [], [], '', false);
        $this->formMock
            ->expects($this->exactly((int) $isOutputEnabled))
            ->method('getElement')
            ->with('front_fieldset')
            ->willReturn($element);
        $element->expects($this->exactly($methodCallCount))
            ->method('addField')
            ->with(
                $this->logicalOr($this->equalTo('attribute_unit'), $this->equalTo('attribute_decimal_places')),
                'text',
                $this->logicalOr(
                    $this->equalTo(
                        [
                            'name' => 'attribute_unit',
                            'label' => __('Attribute unit'),
                            'title' => __('Attribute unit'),
                            'note' => __('The unit that will be shown in frontend'),
                        ]
                    ),
                    $this->equalTo(
                        [
                            'name' => 'attribute_decimal_places',
                            'label' => __('Attribute Decimal Places'),
                            'title' => __('Attribute Decimal Places'),
                            'note' => __('The number of decimal places shown in frontend'),
                        ]
                    )
                )
            );
        $this->observer->execute($this->eventObserverMock);
    }

    public function dataExecute()
    {
        return [
            [
                false,
                0,
            ],
            [
                true,
                2,
            ],
        ];
    }
}
