<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/2/15
 * Time: 9:37 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Api\JsonApi\Application\Command\Post;

use NilPortugues\Api\JsonApi\Domain\Model\Contracts\ResourceRepository;
use NilPortugues\Api\JsonApi\JsonApiSerializer;
use NilPortugues\Api\JsonApi\Server\Data\AttributeNameResolverService;
use NilPortugues\Api\JsonApi\Server\Data\PostAssertion;

class PostCommandHandler
{
    /**
     * @var JsonApiSerializer
     */
    protected $serializer;
    /**
     * @var ResourceRepository
     */
    protected $repository;
    /**
     * @var AttributeNameResolverService
     */
    protected $resolverService;
    /**
     * @var PostAssertion
     */
    protected $assertion;

    /**
     * PostCommandHandler constructor.
     *
     * @param ResourceRepository           $repository
     * @param PostAssertion                $assertion
     * @param AttributeNameResolverService $resolverService
     * @param JsonApiSerializer            $serializer
     */
    public function __construct(
        ResourceRepository $repository,
        PostAssertion $assertion,
        AttributeNameResolverService $resolverService,
        JsonApiSerializer $serializer
    ) {
        $this->assertion = $assertion;
        $this->resolverService = $resolverService;
        $this->serializer = $serializer;
        $this->repository = $repository;
    }

    /**
     * @param PostCommand $createResource
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(PostCommand $createResource)
    {
        $this->assertion->assert($createResource->data(), $createResource->className());

        $values = $this->resolverService->resolve($createResource->data());
        $this->repository->persist($createResource->data(), $values);
    }
}
