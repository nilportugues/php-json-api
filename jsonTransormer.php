<?php
use NilPortugues\Api\Mapping\Mapping;
use NilPortugues\Api\Transformer\Json\JsonApiTransformer;
use NilPortugues\Serializer\Serializer;
use NilPortugues\Serializer\Transformer\Json\JsonTransformer;

include 'vendor/autoload.php';

class UserId
{
    /**
     * @param $id
     */
    public function __construct($id)
    {
        $this->userId = $id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }
}

class User
{
    /**
     * @var UserId
     */
    private $userId;
    /**
     * @var
     */
    private $name;

    /**
     * @param UserId $id
     * @param $name
     */
    public function __construct(UserId $id, $name)
    {
        $this->userId = $id;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}

class CommentId
{
    /**
     * @param $id
     */
    public function __construct($id)
    {
        $this->commentId = $id;
    }

    /**
     * @return mixed
     */
    public function getCommentId()
    {
        return $this->commentId;
    }
}


class Comment
{
    /**
     * @var
     */
    private $commentId;
    /**
     * @var array
     */

    private $dates;
    /**
     * @var string
     */
    private $comment;

    /**
     * @param CommentId $id
     * @param           $comment
     * @param User      $user
     */
    public function __construct(CommentId $id, $comment, User $user, array $dates)
    {
        $this->commentId = $id;
        $this->comment = $comment;
        $this->user = $user;
        $this->dates = $dates;
    }

    /**
     * @return mixed
     */
    public function getCommentId()
    {
        return $this->commentId;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return UserId
     */
    public function getUser()
    {
        return $this->user;
    }
}


class PostId
{
    /**
     * @param $id
     */
    public function __construct($id)
    {
        $this->postId = $id;
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->postId;
    }
}

class Post
{
    /**
     * @param PostId $id
     * @param $title
     * @param $content
     * @param User $user
     * @param array $comments
     */
    public function __construct(PostId $id, $title, $content, User $user, array $comments)
    {
        $this->postId = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $user;
        $this->comments = $comments;
    }

    /**
     * @return array
     */
    public function getComments()
    {
        return $this->comments;
    }


    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return PostId
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return UserId
     */
    public function getUserId()
    {
        return $this->author;
    }
}

$comment1 = new Comment(
    new CommentId(1000),
    "Have no fear, sers, your king is safe.",
    new User(new UserId(2), "Barristan Selmy"),
    //['created_at' => new DateTime('now -35 minutes'), 'accepted_at' => new DateTime()]
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


$postMapping = new Mapping('Post', 'http://example.com/posts/{postId}', ['postId']);
$postIdMapping = new Mapping('PostId', 'http://example.com/posts/{postId}', ['postId']);

$userMapping = new Mapping('User', 'http://example.com/users/{userId}', ['userId']);
$userIdMapping = new Mapping('UserId',  'http://example.com/users/{userId}', ['userId']);

$commentMapping = new Mapping('Comment', 'http://example.com/comments/{commentId}', ['commentId']);
$commentIdMapping = new Mapping('CommentId', 'http://example.com/comments/{commentId}', ['commentId']);


/*
$array = [];
for ($i = 1; $i <= 5; $i++) {
    $array[] = new DateTime("now +$i days");
}

*/
$dateTimeMapping = new Mapping('DateTime', '/date-time/{timezone_type}', ['timezone_type']);
$dateTimeMapping->setHiddenProperties(['timezone_type']);
$dateTimeMapping->setPropertyNameAliases(['date' => 'fecha']);

$apiMappingCollection = [
    $postMapping->getClassName() => $postMapping,
    $postIdMapping->getClassName() => $postIdMapping,
    $userMapping->getClassName() => $userMapping,
    $userIdMapping->getClassName() => $userIdMapping,
    $commentMapping->getClassName() => $commentMapping,
    $commentIdMapping->getClassName() => $commentIdMapping,
];


header('Content-Type: application/vnd.api+json; charset=utf-8');


echo '-------------------------------------------------------------';
echo 'JSON Format';
echo '-------------------------------------------------------------';
echo PHP_EOL;
echo PHP_EOL;
echo (new Serializer(new JsonTransformer()))->serialize($post);
echo PHP_EOL;
echo PHP_EOL;
echo '-------------------------------------------------------------';
echo 'JSON API Format';
echo '-------------------------------------------------------------';
echo PHP_EOL;
echo PHP_EOL;
$serializer = new JsonApiTransformer($apiMappingCollection);
$serializer->setApiVersion('1.0');
$serializer->setSelfUrl('http://example.com/posts/1');
$serializer->setNextUrl('http://example.com/posts/2');
$serializer->addMeta('author', [['name' => 'Nil Portugués Calderó', 'email' => 'contact@nilportugues.com']]);

echo (new Serializer($serializer))->serialize($post);
echo PHP_EOL;
echo PHP_EOL;

