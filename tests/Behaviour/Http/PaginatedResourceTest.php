<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/21/15
 * Time: 9:45 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Behaviour\Http;

use InvalidArgumentException;
use NilPortugues\Api\JsonApi\Http\PaginatedResource;

class PaginatedResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testPaginatedResource()
    {
        $elements = <<<COLLECTION
{
    "data": [
        {
            "type": "employee",
            "id": "1",
            "attributes": {
                "company": "Northwind Traders",
                "last_name": "Freehafer",
                "first_name": "Nancy",
                "email_address": "nancy@northwindtraders.com",
                "job_title": "Sales Representative",
                "business_phone": "(123)555-0100",
                "home_phone": "(123)555-0102",
                "mobile_phone": null,
                "fax_number": "(123)555-0103",
                "address": "123 1st Avenue",
                "city": "Seattle",
                "state_province": "WA",
                "zip_postal_code": "99999",
                "country_region": "USA",
                "web_page": "http://northwindtraders.com",
                "notes": null
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/1"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/1/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "2",
            "attributes": {
                "company": "Northwind Traders",
                "last_name": "Cencini",
                "first_name": "Andrew",
                "email_address": "andrew@northwindtraders.com",
                "job_title": "Vice President, Sales",
                "business_phone": "(123)555-0100",
                "home_phone": "(123)555-0102",
                "mobile_phone": null,
                "fax_number": "(123)555-0103",
                "address": "123 2nd Avenue",
                "city": "Bellevue",
                "state_province": "WA",
                "zip_postal_code": "99999",
                "country_region": "USA",
                "web_page": "http://northwindtraders.com",
                "notes": "Joined the company as a sales representative, was promoted to sales manager and was then named vice president of sales."
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/2"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/2/orders"
                }
            }
        }
    ]
}
COLLECTION;

        $links = [
            'self' => 'http://localhost:9000/api/v1/employees?page[number]=1&page[size]=10',
            'first' => 'http://localhost:9000/api/v1/employees?page[number]=1&page[size]=10',
            'last' => 'http://localhost:9000/api/v1/employees?page[number]=4&page[size]=10',
            'next' => 'http://localhost:9000/api/v1/employees?page[number]=2&page[size]=10',
        ];

        $paginated = new PaginatedResource($elements, 2, 10, 50, $links);
        $paginated->setMeta(['response_time' => '0.001 ms']);
        $paginated->setPageCursor(md5('cursor'));
        $paginated->setPageOffsetLimit(25);
        $paginated->setPageOffset(3);

        $json = <<<JSON
{
    "data": [
        {
            "type": "employee",
            "id": "1",
            "attributes": {
                "company": "Northwind Traders",
                "last_name": "Freehafer",
                "first_name": "Nancy",
                "email_address": "nancy@northwindtraders.com",
                "job_title": "Sales Representative",
                "business_phone": "(123)555-0100",
                "home_phone": "(123)555-0102",
                "mobile_phone": null,
                "fax_number": "(123)555-0103",
                "address": "123 1st Avenue",
                "city": "Seattle",
                "state_province": "WA",
                "zip_postal_code": "99999",
                "country_region": "USA",
                "web_page": "http://northwindtraders.com",
                "notes": null
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/1"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/1/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "2",
            "attributes": {
                "company": "Northwind Traders",
                "last_name": "Cencini",
                "first_name": "Andrew",
                "email_address": "andrew@northwindtraders.com",
                "job_title": "Vice President, Sales",
                "business_phone": "(123)555-0100",
                "home_phone": "(123)555-0102",
                "mobile_phone": null,
                "fax_number": "(123)555-0103",
                "address": "123 2nd Avenue",
                "city": "Bellevue",
                "state_province": "WA",
                "zip_postal_code": "99999",
                "country_region": "USA",
                "web_page": "http://northwindtraders.com",
                "notes": "Joined the company as a sales representative, was promoted to sales manager and was then named vice president of sales."
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/2"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/2/orders"
                }
            }
        }
    ],
    "links": {
        "self": {
            "href": "http://localhost:9000/api/v1/employees?page[number]=1&page[size]=10"
        },
        "first": {
            "href": "http://localhost:9000/api/v1/employees?page[number]=1&page[size]=10"
        },
        "last": {
            "href": "http://localhost:9000/api/v1/employees?page[number]=4&page[size]=10"
        },
        "next": {
            "href": "http://localhost:9000/api/v1/employees?page[number]=2&page[size]=10"
        }
    },
    "included" : [],
    "meta": {
        "page": {
            "total": 50,
            "last": 5,
            "number": 2,
            "size": 10,
            "limit" : 25,
            "offset" : 3,
            "cursor": "1791a97a8403730ee0760489a2aeb992"

        },
        "response_time" : "0.001 ms"
    },
    "jsonapi": {
        "version": "1.0"
    }
}
JSON;

        $this->assertEquals(json_decode($json, true), json_decode(json_encode($paginated), true));
    }

    public function testItWillThrowExceptionOnEmptyData()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        new PaginatedResource('', 2, 10, 50, []);
    }
}
