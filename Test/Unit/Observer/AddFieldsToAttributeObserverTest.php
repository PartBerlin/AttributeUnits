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
            ->expects($this->exactly($methodCallCount))
            ->method('getForm')
            ->willReturn($this->formMock);
        $element = $this->getMock('Magento\Framework\Data\Form\Element\AbstractElement', [], [], '', false);
        $this->formMock
            ->expects($this->exactly($methodCallCount))
            ->method('getElement')
            ->with('front_fieldset')
            ->willReturn($element);
        $element->expects($this->exactly($methodCallCount))
            ->method('addField')
            ->with(
                'attribute_unit',
                'text',
                [
                    'name' => 'attribute_unit',
                    'label' => __('Attribute unit'),
                    'title' => __('Attribute unit'),
                    'note' => __('The unit that will be shown in frontend'),
                ]
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
                1,
            ],
        ];
    }
}
