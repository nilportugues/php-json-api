<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 17/01/16
 * Time: 13:10.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Server\Data;

use NilPortugues\Api\JsonApi\Domain\Contracts\MappingRepository;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use NilPortugues\Api\JsonApi\Server\Errors\InvalidAttributeError;
use NilPortugues\Api\JsonApi\Server\Errors\InvalidTypeError;
use NilPortugues\Api\JsonApi\Server\Errors\MissingAttributeError;
use NilPortugues\Api\JsonApi\Server\Errors\MissingDataError;
use NilPortugues\Api\JsonApi\Server\Errors\MissingTypeError;

/**
 * Class PostSpecification.
 */
class PostAssertion
{
    /**
     * @var MappingRepository
     */
    private $mappingRepository;
    /**
     * @var DataFormatAssertion
     */
    private $inputValidation;

    /**
     * PostSpecification constructor.
     *
     * @param MappingRepository   $mappingRepository
     * @param DataFormatAssertion $inputValidation
     */
    public function __construct(MappingRepository $mappingRepository, DataFormatAssertion $inputValidation)
    {
        $this->inputValidation = $inputValidation;
        $this->mappingRepository = $mappingRepository;
    }
    /**
     * @param array    $data
     * @param string   $className
     * @param ErrorBag $errorBag
     *
     * @throws DataException
     */
    public function assert($data, $className, ErrorBag $errorBag)
    {
        try {
            $this->inputValidation->assert($data, $className, $errorBag);
            $this->assertRelationshipData($data, $errorBag);
        } catch (DataException $e) {
        }

        $missing = $this->missingCreationAttributes($data);
        if (false === empty($missing)) {
            foreach ($missing as $attribute) {
                $errorBag[] = new MissingAttributeError($attribute);
            }
        }

        if ($errorBag->count() > 0) {
            throw new DataException();
        }
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function missingCreationAttributes(array $data)
    {
        $inputAttributes = array_keys($data[JsonApiTransformer::ATTRIBUTES_KEY]);

        $mapping = $this->mappingRepository->findByAlias($data[JsonApiTransformer::TYPE_KEY]);

        $diff = [];
        if (null !== $mapping) {
            $properties = str_replace(
                array_keys($mapping->getAliasedProperties()),
                array_values($mapping->getAliasedProperties()),
                $mapping->getProperties()
            );
            $properties = array_diff($properties, $mapping->getIdProperties());

            $diff = (array) array_diff($properties, $inputAttributes);
        }

        return $diff;
    }
    /**
     * @param array    $data
     * @param ErrorBag $errorBag
     *
     * @throws DataException
     */
    protected function assertRelationshipData(array $data, ErrorBag $errorBag)
    {
        if (!empty($data[JsonApiTransformer::RELATIONSHIPS_KEY])) {
            foreach ($data[JsonApiTransformer::RELATIONSHIPS_KEY] as $relationshipData) {
                if (empty($relationshipData[JsonApiTransformer::DATA_KEY]) || !is_array(
                        $relationshipData[JsonApiTransformer::DATA_KEY]
                    )
                ) {
                    $errorBag[] = new MissingDataError();
                    break;
                }

                $firstKey = key($relationshipData[JsonApiTransformer::DATA_KEY]);
                if (is_numeric($firstKey)) {
                    foreach ($relationshipData[JsonApiTransformer::DATA_KEY] as $inArrayRelationshipData) {
                        $this->relationshipDataAssert($inArrayRelationshipData, $errorBag);
                    }
                    break;
                }

                $this->relationshipDataAssert($relationshipData[JsonApiTransformer::DATA_KEY], $errorBag);
            }
        }
    }

    /**
     * @param array    $relationshipData
     * @param ErrorBag $errorBag
     */
    protected function relationshipDataAssert(array $relationshipData, ErrorBag $errorBag)
    {

        //Has type member.
        if (empty($relationshipData[JsonApiTransformer::TYPE_KEY])
            || !is_string($relationshipData[JsonApiTransformer::TYPE_KEY])
        ) {
            $errorBag[] = new MissingTypeError();

            return;
        }

        //Provided type value is supported.
        if (null === $this->mappingRepository->findByAlias(
                $relationshipData[JsonApiTransformer::TYPE_KEY]
            )
        ) {
            $errorBag[] = new InvalidTypeError($relationshipData[JsonApiTransformer::TYPE_KEY]);

            return;
        }

        //Validate if attributes passed in make sense.
        if (!empty($relationshipData[JsonApiTransformer::ATTRIBUTES_KEY])) {
            $mapping = $this->mappingRepository->findByAlias(
                $relationshipData[JsonApiTransformer::TYPE_KEY]
            );

            $properties = str_replace(
                array_keys($mapping->getAliasedProperties()),
                array_values($mapping->getAliasedProperties()),
                $mapping->getProperties()
            );

            foreach (array_keys($relationshipData[JsonApiTransformer::ATTRIBUTES_KEY]) as $property) {
                if (false === in_array($property, $properties, true)) {
                    $errorBag[] = new InvalidAttributeError($property, $relationshipData[JsonApiTransformer::TYPE_KEY]);
                }
            }
        }
    }
}
