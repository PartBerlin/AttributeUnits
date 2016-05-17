<?php
/**
 * Copyright © PART <info@part-online.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Part\AttributeUnits\Test\Unit\Model\Plugin;

class EavAttributeTest extends \PHPUnit_Framework_TestCase
{
    private $attributeMock;

    private $helperMock;

    private $eavAttribute;

    public function setUp()
    {
        $this->attributeMock = $this->getMock(
            '\Magento\Catalog\Model\ResourceModel\Eav\Attribute',
            null,
            [],
            '',
            false
        );
        $this->helperMock = $this->getMock(
            '\Part\AttributeUnits\Helper\Data',
            ['assembleAdditionalDataEavAttribute'],
            [],
            '',
            false
        );
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->eavAttribute = $objectManager->getObject(
            'Part\AttributeUnits\Model\Plugin\EavAttribute',
            [
                'unitHelper' => $this->helperMock,
            ]
        );
    }

    public function testBeforeSave()
    {
        $this->helperMock
            ->expects($this->once())
            ->method('assembleAdditionalDataEavAttribute')
            ->with($this->attributeMock);
        $this->eavAttribute->beforeSave($this->attributeMock);
    }
}
