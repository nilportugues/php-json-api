<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/28/15
 * Time: 8:03 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\JsonApi\Server\Actions;

use Exception;
use NilPortugues\Api\JsonApi\Http\PaginatedResource;
use NilPortugues\Api\JsonApi\Http\Request\Request;
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\Server\Actions\Traits\RequestTrait;
use NilPortugues\Api\JsonApi\Server\Actions\Traits\ResponseTrait;
use NilPortugues\Api\JsonApi\Server\Errors\Error;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Server\Errors\OufOfBoundsError;
use NilPortugues\Api\JsonApi\Server\Query\QueryException;
use NilPortugues\Api\JsonApi\Server\Query\QueryObject;

/**
 * Class ListResource.
 */
class ListResource
{
    use RequestTrait;
    use ResponseTrait;

    /**
     * @var \NilPortugues\Api\JsonApi\Server\Errors\ErrorBag
     */
    private $errorBag;
    /**
     * @var int
     */
    private $pageNumber;
    /**
     * @var int
     */
    private $pageSize;

    /**
     * @var JsonApiSerializer
     */
    private $serializer;

    /**
     * @param JsonApiSerializer $serializer
     */
    public function __construct(JsonApiSerializer $serializer)
    {
        $this->serializer = $serializer;
        $this->errorBag = new ErrorBag();
        $this->pageNumber = $this->apiRequest()->getPageNumber();
        $this->pageSize = $this->apiRequest()->getPageSize();
    }

    /**
     * @param callable $totalAmountCallable
     * @param callable $resultsCallable
     * @param string   $route
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function get(callable $totalAmountCallable, callable $resultsCallable, $route)
    {
        try {
            QueryObject::assert($this->serializer, $this->errorBag);
            $totalAmount = $totalAmountCallable();

            if ($totalAmount > 0 && $this->pageNumber > ceil($totalAmount / $this->pageSize)) {
                return $this->resourceNotFound(
                    new ErrorBag([new OufOfBoundsError($this->pageNumber, $this->pageSize)])
                );
            }

            $links = $this->pagePaginationLinks(
                $route,
                $this->pageNumber,
                $this->pageSize,
                $totalAmount
            );

            $results = $resultsCallable();

            $paginatedResource = new PaginatedResource(
                $this->serializer->serialize($results),
                $this->pageNumber,
                $this->pageSize,
                $totalAmount,
                $links
            );

            $response = $this->response($paginatedResource);
        } catch (Exception $e) {
            $response = $this->getErrorResponse($e);
        }

        return $response;
    }

    /**
     * @param string $route
     * @param int    $pageNumber
     * @param int    $pageSize
     * @param int    $totalPages
     *
     * @return array
     */
    protected function pagePaginationLinks($route, $pageNumber, $pageSize, $totalPages)
    {
        $next = $pageNumber + 1;
        $previous = $pageNumber - 1;
        $last = ceil($totalPages / $pageSize);

        $links = array_filter([
                'self' => $pageNumber,
                'first' => 1,
                'next' => ($next <= $last) ? $next : null,
                'previous' => ($previous > 1) ? $previous : null,
                'last' => $last,
            ]);

        foreach ($links as &$numberedLink) {
            $numberedLink = $this->pagePaginatedRoute($this->apiRequest(), $route, $numberedLink, $pageSize);
        }

        return $links;
    }

    /**
     * Build the URL using Laravel's route facade method.
     *
     * @param Request $request
     * @param string  $route
     * @param int     $pageNumber
     * @param int     $pageSize
     *
     * @return string
     */
    private function pagePaginatedRoute(Request $request, $route, $pageNumber, $pageSize)
    {
        $queryParams = urldecode(http_build_query(
                [
                    'page' => array_filter(
                        [
                            'number' => $pageNumber,
                            'size' => $pageSize,
                        ]
                    ),
                    'fields' => $request->getQueryParam('fields'),
                    'filter' => $request->getQueryParam('filter'),
                    'sort' => $request->getQueryParam('sort'),
                    'include' => $request->getQueryParam('include'),
                ]
            ));

        if ($route[strlen($route) - 1] === '?' || $route[strlen($route) - 1] === '&') {
            return sprintf('%s%s', $route, $queryParams);
        }

        return sprintf('%s?%s', $route, $queryParams);
    }

    /**
     * @param Exception $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function getErrorResponse(Exception $e)
    {
        switch (get_class($e)) {
            case QueryException::class:
                $response = $this->errorResponse($this->errorBag);
                break;

            default:
                $response = $this->errorResponse(
                    new ErrorBag([new Error('Bad Request', 'Request could not be served.')])
                );

                return $response;
        }

        return $response;
    }
}
