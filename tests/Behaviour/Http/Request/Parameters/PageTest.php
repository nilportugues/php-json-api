<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/15/15
 * Time: 2:19 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Behaviour\Http\Request\Parameters;

use NilPortugues\Api\JsonApi\Http\Request\Parameters\Page;

class PageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $page = new Page(1, 2, 3, 4, 5);

        $this->assertEquals(1, $page->number());
        $this->assertEquals(2, $page->cursor());
        $this->assertEquals(3, $page->limit());
        $this->assertEquals(4, $page->offset());
        $this->assertEquals(5, $page->size());
    }
}
