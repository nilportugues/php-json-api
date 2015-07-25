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


$postMapping = new Mapping(Post::class, 'http://api.example.com/posts/{postId}', ['postId']);
$postMapping->setClassAlias('Message');
$postMapping->addPropertyAlias('title', 'headline');
$postMapping->addPropertyAlias('content', 'body');
$postMapping->setRelatedUrl("http://api.example.com/posts/{postId}/author");
$postMapping->setRelationshipUrl("http://api.example.com/posts/{postId}/relationships/author");




$postIdMapping = new Mapping(PostId::class, 'http://api.example.com/posts/{postId}', ['postId']);
$postIdMapping->addAdditionalRelationship(Comment::class, 'http://api.example.com/posts/{postId}/relationships/comments');

$userMapping = new Mapping(User::class, 'http://api.example.com/users/{userId}', ['userId']);
$userIdMapping = new Mapping(UserId::class,  'http://api.example.com/users/{userId}', ['userId']);

$commentMapping = new Mapping(Comment::class, 'http://api.example.com/comments/{commentId}', ['commentId']);
$commentMapping->addAdditionalRelationship(Post::class, 'http://api.example.com/posts/{postId}/relationships/comments');

$commentIdMapping = new Mapping(CommentId::class, 'http://api.example.com/comments/{commentId}', ['commentId']);



$apiMappingCollection = [
    $postMapping->getClassName() => $postMapping,
    $postIdMapping->getClassName() => $postIdMapping,
    $userMapping->getClassName() => $userMapping,
    $userIdMapping->getClassName() => $userIdMapping,
    $commentMapping->getClassName() => $commentMapping,
    $commentIdMapping->getClassName() => $commentIdMapping,
];


header('Content-Type: application/vnd.api+json; charset=utf-8');
$serializer = new JsonApiTransformer($apiMappingCollection);
$serializer->setApiVersion('1.0');

$serializer->setSelfUrl('http://api.example.com/posts/9');
$serializer->setNextUrl('http://api.example.com/posts/10');

$serializer->addMeta('author', [['name' => 'Nil Portugués Calderó', 'email' => 'contact@nilportugues.com']]);

echo (new Serializer($serializer))->serialize($post);
