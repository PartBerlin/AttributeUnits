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
                $this->eavConfigMock,
                $this->localeResolverMock,
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
}
