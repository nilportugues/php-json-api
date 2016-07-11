<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 8/1/15
 * Time: 12:29 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Api\JsonApi\Behaviour\Http\Response;

use NilPortugues\Api\JsonApi\Http\Response\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testResponse()
    {
        $json = \json_encode([]);
        $response = new Response($json);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['application/vnd.api+json'], $response->getHeader('Content-type'));
    }

    public function testLinksHeader()
    {
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
        },
        {
            "type": "employee",
            "id": "3",
            "attributes": {
                "company": "Northwind Traders",
                "last_name": "Kotas",
                "first_name": "Jan",
                "email_address": "jan@northwindtraders.com",
                "job_title": "Sales Representative",
                "business_phone": "(123)555-0100",
                "home_phone": "(123)555-0102",
                "mobile_phone": null,
                "fax_number": "(123)555-0103",
                "address": "123 3rd Avenue",
                "city": "Redmond",
                "state_province": "WA",
                "zip_postal_code": "99999",
                "country_region": "USA",
                "web_page": "http://northwindtraders.com",
                "notes": "Was hired as a sales associate and was promoted to sales representative."
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/3"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/3/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "4",
            "attributes": {
                "company": "Northwind Traders",
                "last_name": "Sergienko",
                "first_name": "Mariya",
                "email_address": "mariya@northwindtraders.com",
                "job_title": "Sales Representative",
                "business_phone": "(123)555-0100",
                "home_phone": "(123)555-0102",
                "mobile_phone": null,
                "fax_number": "(123)555-0103",
                "address": "123 4th Avenue",
                "city": "Kirkland",
                "state_province": "WA",
                "zip_postal_code": "99999",
                "country_region": "USA",
                "web_page": "http://northwindtraders.com",
                "notes": null
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/4"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/4/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "5",
            "attributes": {
                "company": "Northwind Traders",
                "last_name": "Thorpe",
                "first_name": "Steven",
                "email_address": "steven@northwindtraders.com",
                "job_title": "Sales Manager",
                "business_phone": "(123)555-0100",
                "home_phone": "(123)555-0102",
                "mobile_phone": null,
                "fax_number": "(123)555-0103",
                "address": "123 5th Avenue",
                "city": "Seattle",
                "state_province": "WA",
                "zip_postal_code": "99999",
                "country_region": "USA",
                "web_page": "http://northwindtraders.com",
                "notes": "Joined the company as a sales representative and was promoted to sales manager.  Fluent in French."
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/5"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/5/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "6",
            "attributes": {
                "company": "Northwind Traders",
                "last_name": "Neipper",
                "first_name": "Michael",
                "email_address": "michael@northwindtraders.com",
                "job_title": "Sales Representative",
                "business_phone": "(123)555-0100",
                "home_phone": "(123)555-0102",
                "mobile_phone": null,
                "fax_number": "(123)555-0103",
                "address": "123 6th Avenue",
                "city": "Redmond",
                "state_province": "WA",
                "zip_postal_code": "99999",
                "country_region": "USA",
                "web_page": "http://northwindtraders.com",
                "notes": "Fluent in Japanese and can read and write French, Portuguese, and Spanish."
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/6"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/6/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "7",
            "attributes": {
                "company": "Northwind Traders",
                "last_name": "Zare",
                "first_name": "Robert",
                "email_address": "robert@northwindtraders.com",
                "job_title": "Sales Representative",
                "business_phone": "(123)555-0100",
                "home_phone": "(123)555-0102",
                "mobile_phone": null,
                "fax_number": "(123)555-0103",
                "address": "123 7th Avenue",
                "city": "Seattle",
                "state_province": "WA",
                "zip_postal_code": "99999",
                "country_region": "USA",
                "web_page": "http://northwindtraders.com",
                "notes": null
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/7"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/7/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "8",
            "attributes": {
                "company": "Northwind Traders",
                "last_name": "Giussani",
                "first_name": "Laura",
                "email_address": "laura@northwindtraders.com",
                "job_title": "Sales Coordinator",
                "business_phone": "(123)555-0100",
                "home_phone": "(123)555-0102",
                "mobile_phone": null,
                "fax_number": "(123)555-0103",
                "address": "123 8th Avenue",
                "city": "Redmond",
                "state_province": "WA",
                "zip_postal_code": "99999",
                "country_region": "USA",
                "web_page": "http://northwindtraders.com",
                "notes": "Reads and writes French."
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/8"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/8/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "9",
            "attributes": {
                "company": "Northwind Traders",
                "last_name": "Hellung-Larsen",
                "first_name": "Anne",
                "email_address": "anne@northwindtraders.com",
                "job_title": "Sales Representative",
                "business_phone": "(123)555-0100",
                "home_phone": "(123)555-0102",
                "mobile_phone": null,
                "fax_number": "(123)555-0103",
                "address": "123 9th Avenue",
                "city": "Seattle",
                "state_province": "WA",
                "zip_postal_code": "99999",
                "country_region": "USA",
                "web_page": "http://northwindtraders.com",
                "notes": "Fluent in French and German."
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/9"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/9/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "10",
            "attributes": {
                "company": "Nil",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@microsoft.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/10"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/10/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "11",
            "attributes": {
                "company": "Nil",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/11"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/11/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "18",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/18"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/18/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "19",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/19"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/19/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "20",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/20"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/20/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "21",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/21"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/21/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "22",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/22"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/22/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "23",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/23"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/23/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "24",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/24"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/24/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "25",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/25"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/25/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "26",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/26"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/26/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "27",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/27"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/27/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "28",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/28"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/28/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "29",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/29"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/29/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "30",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/30"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/30/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "31",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/31"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/31/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "32",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/32"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/32/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "33",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/33"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/33/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "34",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/34"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/34/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "35",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/35"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/35/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "36",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/36"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/36/orders"
                }
            }
        },
        {
            "type": "employee",
            "id": "37",
            "attributes": {
                "company": "Nilo",
                "last_name": "Nil",
                "first_name": "Nil",
                "email_address": "nilopc@gmail.com",
                "job_title": "Nil",
                "business_phone": "Nil",
                "home_phone": "Nil",
                "mobile_phone": "Nil",
                "fax_number": "Nil",
                "address": "Nil",
                "city": "Nil",
                "state_province": "Nil",
                "zip_postal_code": "Nil",
                "country_region": "Nil",
                "web_page": "Nil",
                "notes": "Nil"
            },
            "links": {
                "self": {
                    "href": "http://localhost:9000/api/v1/employees/37"
                },
                "employee_orders": {
                    "href": "http://localhost:9000/api/v1/employees/37/orders"
                }
            }
        }
    ],
    "links": {
        "self": {
            "href": "http://localhost:9000/api/v1/employees?page[number]=1&page[size]=50"
        },
        "first": {
            "href": "http://localhost:9000/api/v1/employees?page[number]=1&page[size]=50"
        },
        "last": {
            "href": "http://localhost:9000/api/v1/employees?page[number]=1&page[size]=50"
        }
    },
    "meta": {
        "page": {
            "total": 31,
            "number": 1,
            "size": 50
        }
    },
    "jsonapi": {
        "version": "1.0"
    }
}
JSON;
        $response = new Response($json);
        $headers = $response->getHeaders();

        $expected = '<http://localhost:9000/api/v1/employees?page[number]=1&page[size]=50>; rel="last", '.
            '<http://localhost:9000/api/v1/employees?page[number]=1&page[size]=50>; rel="first"';

        $this->assertEquals($expected, $headers['Link'][0]);
    }
}
