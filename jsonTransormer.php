<?php
use NilPortugues\Api\Mapping\Mapping;
use NilPortugues\Api\Transformer\Json\JsonApiTransformer;
use NilPortugues\Serializer\Serializer;
use NilPortugues\Tests\Api\Dummy\ComplexObject\Comment;
use NilPortugues\Tests\Api\Dummy\ComplexObject\Post;
use NilPortugues\Tests\Api\Dummy\ComplexObject\User;
use NilPortugues\Tests\Api\Dummy\ComplexObject\ValueObject\CommentId;
use NilPortugues\Tests\Api\Dummy\ComplexObject\ValueObject\PostId;
use NilPortugues\Tests\Api\Dummy\ComplexObject\ValueObject\UserId;
use NilPortugues\Tests\Api\Dummy\SimpleObject\Post as SimplePost;

include 'vendor/autoload.php';

$comment1 = new Comment(
    new CommentId(1000),
    "Have no fear, sers, your king is safe.",
    new User(new UserId(2), "Barristan Selmy"),
    ['created_at' => (new DateTime('now -35 minutes'))->format('c'), 'accepted_at' => (new DateTime())->format('c')]
);

$post = new Post(
    new PostId(9),
    "Hello World",
    "Your first post",
    new User(new UserId(1), 'Post Author'),
    [
        $comment1
    ]
);


$postMapping = new Mapping(Post::class, 'http://example.com/posts/{postId}', ['postId']);
$postMapping->setClassAlias('Message');
$postMapping->addPropertyAlias('title', 'headline');
$postMapping->addPropertyAlias('content', 'body');
$postMapping->addRelationship(User::class, 'http://example.com/posts/relationships/author/{userId}');
$postMapping->hideProperty('comments');
$postMapping->setSelfUrl('http://example.com/posts/1');
$postMapping->setNextUrl('http://example.com/posts/2');




$postIdMapping = new Mapping(PostId::class, 'http://example.com/posts/{postId}', ['postId']);
$postIdMapping->addRelationship(Comment::class, 'http://example.com/posts/{postId}/relationships/comments');

$userMapping = new Mapping(User::class, 'http://example.com/users/{userId}', ['userId']);
$userIdMapping = new Mapping(UserId::class,  'http://example.com/users/{userId}', ['userId']);

$commentMapping = new Mapping(Comment::class, 'http://example.com/comments/{commentId}', ['commentId']);
$commentMapping->addRelationship(Post::class, 'http://example.com/posts/{postId}/relationships/comments');

$commentIdMapping = new Mapping(CommentId::class, 'http://example.com/comments/{commentId}', ['commentId']);



$apiMappingCollection = [
    $postMapping->getClassName() => $postMapping,
    $postIdMapping->getClassName() => $postIdMapping,
    $userMapping->getClassName() => $userMapping,
    $userIdMapping->getClassName() => $userIdMapping,
    $commentMapping->getClassName() => $commentMapping,
    $commentIdMapping->getClassName() => $commentIdMapping,
];


header('Content-Type: application/vnd.api+json; charset=utf-8');
/*
$serializer = new JsonApiTransformer($apiMappingCollection);
$serializer->setApiVersion('1.0');

$serializer->addMeta('author', [['name' => 'Nil Portugués Calderó', 'email' => 'contact@nilportugues.com']]);

echo (new Serializer($serializer))->serialize($post);

*/
/**
 * @return SimplePost
 */
function createSimplePost()
{
    $post = new SimplePost(1, 'post title', 'post body', 2);

    for ($i = 1; $i <= 5; ++$i) {
        $userId = $i * 5;
        $createdAt = new \DateTime("2015/07/18 12:48:00 + $i days", new \DateTimeZone('Europe/Madrid'));
        $post->addComment($i * 10, "User {$userId}", "I am writing comment no. {$i}", $createdAt->format('c'));
    }

    return $post;
}

$postArray = [
    createSimplePost(),
    createSimplePost(),
];

$postMapping = new Mapping(SimplePost::class, '/post/{postId}', ['postId']);
$postMapping->setFilterKeys(['body', 'title']);

$jsonApiSerializer = new JsonApiTransformer([$postMapping->getClassName() => $postMapping]);

echo ((new Serializer($jsonApiSerializer))->serialize($postArray));