<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/27/15
 * Time: 11:40 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Server\Data;

use NilPortugues\Api\JsonApi\Domain\Contracts\MappingRepository;
use NilPortugues\Api\JsonApi\Domain\Model\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Domain\Model\Errors\InvalidAttributeError;
use NilPortugues\Api\JsonApi\Domain\Model\Errors\InvalidTypeError;
use NilPortugues\Api\JsonApi\Domain\Model\Errors\MissingAttributesError;
use NilPortugues\Api\JsonApi\Domain\Model\Errors\MissingDataError;
use NilPortugues\Api\JsonApi\Domain\Model\Errors\MissingTypeError;
use NilPortugues\Api\JsonApi\JsonApiTransformer;

/**
 * Class DataAssertions.
 */
class DataFormatAssertion
{
    /**
     * @var MappingRepository
     */
    private $mappingRepository;

    /**
     * DataInputValidations constructor.
     *
     * @param MappingRepository $mappingRepository
     */
    public function __construct(MappingRepository $mappingRepository)
    {
        $this->mappingRepository = $mappingRepository;
    }

    /**
     * @param array  $data
     * @param string $className
     */
    public function assert($data, $className)
    {
        $errorBag = new ErrorBag();

        $this->assertItIsArray($data, $errorBag);
        $this->assertItHasTypeMember($data, $errorBag);
        $this->assertItTypeMemberIsExpectedValue($data, $className, $errorBag);
        $this->assertItHasAttributeMember($data, $errorBag);
        $this->assertAttributesExists($data, $errorBag);
    }

    /**
     * @param          $data
     * @param ErrorBag $errorBag
     *
     * @throws DataException
     */
    protected function assertItIsArray($data, ErrorBag $errorBag)
    {
        if (empty($data) || !is_array($data)) {
            $errorBag[] = new MissingDataError();
            throw new DataException();
        }
    }

    /**
     * @param array    $data
     * @param ErrorBag $errorBag
     *
     * @throws DataException
     */
    protected function assertItHasTypeMember(array $data, ErrorBag $errorBag)
    {
        if (empty($data[JsonApiTransformer::TYPE_KEY]) || !is_string($data[JsonApiTransformer::TYPE_KEY])) {
            $errorBag[] = new MissingTypeError();
            throw new DataException();
        }
    }

    /**
     * @param array    $data
     * @param          $className
     * @param ErrorBag $errorBag
     *
     * @throws DataException
     */
    protected function assertItTypeMemberIsExpectedValue(
        array $data,
        $className,
        ErrorBag $errorBag
    ) {
        $mapping = $this->mappingRepository->findByAlias($data[JsonApiTransformer::TYPE_KEY]);

        if (null === $mapping || $mapping->getClassName() !== $className) {
            $errorBag[] = new InvalidTypeError($data[JsonApiTransformer::TYPE_KEY]);
            throw new DataException();
        }
    }

    /**
     * @param          $data
     * @param ErrorBag $errorBag
     *
     * @throws DataException
     */
    protected function assertItHasAttributeMember($data, ErrorBag $errorBag)
    {
        if (empty($data[JsonApiTransformer::ATTRIBUTES_KEY]) || !is_array($data[JsonApiTransformer::ATTRIBUTES_KEY])) {
            $errorBag[] = new MissingAttributesError();
            throw new DataException();
        }
    }

    /**
     * @param array    $data
     * @param ErrorBag $errorBag
     *
     * @throws DataException
     */
    protected function assertAttributesExists(array $data, ErrorBag $errorBag)
    {
        $inputAttributes = array_keys($data[JsonApiTransformer::ATTRIBUTES_KEY]);

        $mapping = $this->mappingRepository->findByAlias($data[JsonApiTransformer::TYPE_KEY]);

        $properties = str_replace(
            array_keys($mapping->getAliasedProperties()),
            array_values($mapping->getAliasedProperties()),
            $mapping->getProperties()
        );
        $properties = array_diff($properties, $mapping->getIdProperties());
        $properties = array_merge($properties, $mapping->getHiddenProperties());

        $hasErrors = false;
        foreach ($inputAttributes as $property) {
            if (false === in_array($property, $properties)) {
                $hasErrors = true;
                $errorBag[] = new InvalidAttributeError($property, $data[JsonApiTransformer::TYPE_KEY]);
            }
        }

        if ($hasErrors) {
            throw new DataException();
        }
    }
}
