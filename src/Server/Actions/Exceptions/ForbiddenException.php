<?php
namespace NilPortugues\Api\JsonApi\Server\Actions\Exceptions;

/**
 * Class ForbiddenException.
 */
class ForbiddenException extends \Exception
{
    private $title = "Forbidden";

    /**
     * @param string $message
     * @param Exception $previous
     */
    public function __construct($message, $previous = null)
    {
        parent::__construct($message, 403, $previous);
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
