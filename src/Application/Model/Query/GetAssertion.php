<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/28/15
 * Time: 12:12 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Server\Query;

use NilPortugues\Api\JsonApi\Domain\Contracts\MappingRepository;
use NilPortugues\Api\JsonApi\Domain\Model\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Domain\Model\Errors\InvalidParameterError;
use NilPortugues\Api\JsonApi\Domain\Model\Errors\InvalidParameterMemberError;
use NilPortugues\Api\JsonApi\Domain\Model\Errors\InvalidSortError;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Fields;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Included;
use NilPortugues\Api\JsonApi\Http\Request\Parameters\Sorting;
use NilPortugues\Api\JsonApi\Server\Exceptions\QueryException;

class GetAssertion
{
    /**
     * @var MappingRepository
     */
    private $mappingRepository;

    /**
     * DataObject constructor.
     *
     * @param MappingRepository $mappingRepository
     */
    public function __construct(MappingRepository $mappingRepository)
    {
        $this->mappingRepository = $mappingRepository;
    }

    /**
     * @param Fields   $fields
     * @param Included $included
     * @param Sorting  $sort
     * @param ErrorBag $errorBag
     * @param string   $className
     *
     * @throws QueryException
     */
    public function assert(Fields $fields, Included $included, Sorting $sort, ErrorBag $errorBag, $className)
    {
        $this->validateQueryParamsTypes($fields, 'Fields', $errorBag);
        $this->validateIncludeParams($included, 'include', $errorBag);

        if (!empty($className) && false === $sort->isEmpty()) {
            $this->validateSortParams($className, $sort, $errorBag);
        }

        if ($errorBag->count() > 0) {
            throw new QueryException();
        }
    }

    /**
     * @param Fields   $fields
     * @param          $paramName
     * @param ErrorBag $errorBag
     */
    protected function validateQueryParamsTypes(Fields $fields, $paramName, ErrorBag $errorBag)
    {
        if (false === $fields->isEmpty()) {
            $validateFields = $fields->types();

            foreach ($validateFields as $key => $type) {
                $mapping = $this->mappingRepository->findByAlias($type);
                if (null !== $mapping) {
                    $members = array_merge(
                        array_combine($mapping->getProperties(), $mapping->getProperties()),
                        $mapping->getAliasedProperties()
                    );

                    $invalidMembers = array_diff($fields->members($type), $members);
                    foreach ($invalidMembers as $extraField) {
                        $errorBag[] = new InvalidParameterMemberError($extraField, $type, strtolower($paramName));
                    }
                    unset($validateFields[$key]);
                }
            }

            if (false === empty($validateFields)) {
                foreach ($validateFields as $type) {
                    $errorBag[] = new InvalidParameterError($type, strtolower($paramName));
                }
            }
        }
    }

    /**
     * @param Included $included
     * @param string   $paramName
     * @param ErrorBag $errorBag
     */
    protected function validateIncludeParams(Included $included, $paramName, ErrorBag $errorBag)
    {
        foreach ($included->get() as $resource => $data) {
            if (null == $this->mappingRepository->findByAlias($resource)) {
                $errorBag[] = new InvalidParameterError($resource, strtolower($paramName));
                continue;
            }

            if (is_array($data)) {
                foreach ($data as $subResource) {
                    if (null == $this->mappingRepository->findByAlias($subResource)) {
                        $errorBag[] = new InvalidParameterError($subResource, strtolower($paramName));
                    }
                }
            }
        }
    }

    /**
     * @param string   $className
     * @param Sorting  $sorting
     * @param ErrorBag $errorBag
     */
    protected function validateSortParams($className, Sorting $sorting, ErrorBag $errorBag)
    {
        if (false === $sorting->isEmpty()) {
            if ($mapping = $this->mappingRepository->findByClassName($className)) {
                $aliased = (array) $mapping->getAliasedProperties();
                $sortsFields = str_replace(array_values($aliased), array_keys($aliased), $sorting->fields());

                $invalidProperties = array_diff($sortsFields, $mapping->getProperties());

                foreach ($invalidProperties as $extraField) {
                    $errorBag[] = new InvalidSortError($extraField);
                }
            }
        }
    }
}
