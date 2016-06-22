<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/20/15
 * Time: 9:01 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Server\Errors;

use InvalidArgumentException;
use NilPortugues\Api\JsonApi\Server\Errors\Error;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testItCanConstruct()
    {
        $error = new Error('title', 'message', 'code');

        $json = <<<JSON
{
    "title": "title",
    "code": "code",
    "detail": "message"
}
JSON;

        $this->assertEquals(
            json_decode($json, true),
            json_decode(json_encode($error), true)
        );
    }

    public function testItCanSetAdditionalData()
    {
        $error = new Error('title', 'message');
        $error->setCode('code');
        $error->setId('post.user.error');
        $error->setStatus(403);
        $error->setAboutLink('http://api.example.com/documentation/errors.html');
        $error->setMeta(['time' => '0.001 ms']);

        $json = <<<JSON
{
   "id":"post.user.error",
   "status":403,
   "code":"code",
   "title":"title",
   "detail":"message",
   "links":{
      "about":{
         "href":"http://api.example.com/documentation/errors.html"
      }
   },
   "meta":{
      "time":"0.001 ms"
   }
}
JSON;

        $this->assertEquals(
            json_decode($json, true),
            json_decode(json_encode($error), true)
        );
    }

    public function testSetStatusWillThrowExceptionIfNotValidHttpCodeIsProvided()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $error = new Error('title', 'message');
        $error->setStatus('will fail');
    }

    public function testSetAboutLinkWillThrowExceptionIfNotValidUrlIsProvided()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $error = new Error('title', 'message');
        $error->setAboutLink('aaaa');
    }

    public function testSetSourceWillThrowExceptionInvalidKeyIsProvided()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $error = new Error('title', 'message');
        $error->setSource('aaaa', 'b');
    }

    public function testItWillThrowExceptionIfTitleIsEmpty()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        new Error('', 'message');
    }

    public function testItWillThrowExceptionIfMessageIsEmpty()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        new Error('title', '');
    }
}
