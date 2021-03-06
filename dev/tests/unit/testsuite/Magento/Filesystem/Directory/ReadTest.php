<?php
/**
 * Unit Test for \Magento\Filesystem\Directory\Read
 *
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Filesystem\Directory;

class ReadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * \Magento\Filesystem\Driver
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $driver;

    /**
     * @var \Magento\Filesystem\Directory\Read
     */
    protected $read;

    /**
     * \Magento\Filesystem\File\ReadFactory
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $fileFactory;

    /**
     * Set up
     */
    protected function setUp()
    {
        $this->driver = $this->getMock('Magento\Filesystem\Driver\File', array(), array(), '', false);
        $this->fileFactory = $this->getMock('Magento\Filesystem\File\ReadFactory', array(), array(), '', false);
        $this->read = new \Magento\Filesystem\Directory\Read(array(), $this->fileFactory, $this->driver);
    }

    /**
     * Tear down
     */
    protected function tearDown()
    {
        $this->driver = null;
        $this->fileFactory = null;
        $this->read = null;
    }

    public function testIsExist()
    {
        $this->driver->expects($this->once())->method('isExists')->will($this->returnValue(true));
        $this->assertTrue($this->read->isExist('correct-path'));
    }

    public function testStat()
    {
        $this->driver->expects($this->once())->method('stat')->will($this->returnValue(array('some-stat-data')));
        $this->assertEquals(array('some-stat-data'), $this->read->stat('correct-path'));
    }
}
