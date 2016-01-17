<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/20/15
 * Time: 9:01 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Domain\Errors;

use InvalidArgumentException;
use NilPortugues\Api\JsonApi\Domain\Model\Errors\Error;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    const VALID_TITLE = 'title';
    const VALID_MESSAGE = 'message';
    const VALID_CODE = 'code';
    const VALID_ID = 'post.user.error';
    const VALID_STATUS = 403;
    const VALID_ABOUT_LINK = 'http://api.example.com/documentation/errors.html';

    const INVALID_STATUS = 'will fail';
    const INVALID_ABOUT_LINK = 'aaaa';
    const INVALID_KEY = 'b';
    const INVALID_MESSAGE = '';
    const INVALID_TITLE = '';

    public function testItCanConstruct()
    {
        $error = new Error(self::VALID_TITLE, self::VALID_MESSAGE, self::VALID_CODE);

        $this->assertEquals(self::VALID_CODE, $error->code());
        $this->assertEquals(self::VALID_TITLE, $error->title());
        $this->assertEquals(self::VALID_MESSAGE, $error->detail());
    }

    public function testItCanSetAdditionalData()
    {
        $error = new Error(self::VALID_TITLE, self::VALID_MESSAGE);
        $error->setCode(self::VALID_CODE);
        $error->setId(self::VALID_ID);
        $error->setStatus(self::VALID_STATUS);
        $error->setAboutLink(self::VALID_ABOUT_LINK);
        $error->setMeta(['time' => '0.001 ms']);

        $this->assertEquals(self::VALID_CODE, $error->code());
        $this->assertEquals(self::VALID_ID, $error->id());
        $this->assertEquals(self::VALID_STATUS, $error->status());
        $this->assertEquals(self::VALID_ABOUT_LINK,  $error->links()['about']['href']);
        $this->assertEquals(self::VALID_TITLE, $error->title());
        $this->assertEquals(self::VALID_MESSAGE, $error->detail());
        $this->assertEquals(['time' => '0.001 ms'], $error->meta());

    }

    public function testSetStatusWillThrowExceptionIfNotValidHttpCodeIsProvided()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $error = new Error(self::VALID_TITLE, self::VALID_MESSAGE);
        $error->setStatus(self::INVALID_STATUS);
    }

    public function testSetAboutLinkWillThrowExceptionIfNotValidUrlIsProvided()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $error = new Error(self::VALID_TITLE, self::VALID_MESSAGE);
        $error->setAboutLink(self::INVALID_ABOUT_LINK);
    }

    public function testSetSourceWillThrowExceptionInvalidKeyIsProvided()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $error = new Error(self::VALID_TITLE, self::VALID_MESSAGE);
        $error->setSource(self::INVALID_ABOUT_LINK, self::INVALID_KEY);
    }

    public function testItWillThrowExceptionIfTitleIsEmpty()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        new Error(self::VALID_TITLE, self::VALID_MESSAGE);
    }

    public function testItWillThrowExceptionIfMessageIsEmpty()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        new Error(self::VALID_TITLE, self::INVALID_MESSAGE);
    }
}
