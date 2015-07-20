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
$postMapping->addRelationship('comments', ['href' => 'http://example.com/posts/{postId}/relationships/comments']);


$postIdMapping = new Mapping(PostId::class, 'http://example.com/posts/{postId}', ['postId']);

$userMapping = new Mapping(User::class, 'http://example.com/users/{userId}', ['userId']);
$userIdMapping = new Mapping(UserId::class,  'http://example.com/users/{userId}', ['userId']);

$commentMapping = new Mapping(Comment::class, 'http://example.com/comments/{commentId}', ['commentId']);
$postMapping->addRelationship('users', ['href' => 'http://example.com/comments/{commentId}/relationships/users']);

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
echo (new Serializer(new \NilPortugues\Api\Transformer\Json\JsonTransformer()))->serialize($post);

$serializer = new JsonApiTransformer($apiMappingCollection);
$serializer->setApiVersion('1.0');
$serializer->setSelfUrl('http://example.com/posts/1');
$serializer->setNextUrl('http://example.com/posts/2');
$serializer->addMeta('author', [['name' => 'Nil Portugués Calderó', 'email' => 'contact@nilportugues.com']]);

echo (new Serializer($serializer))->serialize($post);
