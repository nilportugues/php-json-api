<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/20/15
 * Time: 7:22 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\JsonApi\Http;

/**
 * Class ErrorBag.
 */
class Error implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var array
     */
    private $links = [];
    /**
     * @var int
     */
    private $status;
    /**
     * @var string
     */
    private $code;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $detail;
    /**
     * @var array
     */
    private $source = [];
    /**
     * @var mixed
     */
    private $meta;

    /**
     * @param string $title
     * @param string $message
     * @param string $code
     */
    public function __construct($title, $message, $code = '')
    {
        $this->setTitle($title);
        $this->setDetail($message);
        $this->setCode($code);
    }

    /**
     * A unique identifier for this particular occurrence of the problem.
     *
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * The HTTP status code applicable to this problem, expressed as a string value.
     *
     * @param $status
     *
     * @throws \InvalidArgumentException
     */
    public function setStatus($status)
    {
        $validStatus = [
            100,
            101,
            102,
            200,
            201,
            202,
            203,
            204,
            205,
            206,
            207,
            208,
            300,
            301,
            302,
            303,
            304,
            305,
            306,
            307,
            400,
            401,
            402,
            403,
            404,
            405,
            406,
            407,
            408,
            409,
            410,
            411,
            412,
            413,
            414,
            415,
            416,
            417,
            418,
            422,
            423,
            424,
            425,
            426,
            428,
            429,
            431,
            500,
            501,
            502,
            503,
            504,
            505,
            506,
            507,
            508,
            511,
        ];

        if (false === in_array($status, $validStatus, false)) {
            throw new \InvalidArgumentException(sprintf(
                'Provided status does not match a valid HTTP Status Code.',
                $status
            ));
        }
        $this->status = (int) $status;
    }

    /**
     * A short, human-readable summary of the problem that SHOULD NOT change from occurrence to
     * occurrence of the problem, except for purposes of localization.
     *
     * @param string $title
     *
     * @throws \InvalidArgumentException
     */
    private function setTitle($title)
    {
        if (0 === strlen(trim($title))) {
            throw new \InvalidArgumentException('Provided title cannot be empty');
        }

        $this->title = (string) $title;
    }

    /**
     * A human-readable explanation specific to this occurrence of the problem.
     *
     * @param $detail
     *
     * @throws \InvalidArgumentException
     */
    private function setDetail($detail)
    {
        if (0 === strlen(trim($detail))) {
            throw new \InvalidArgumentException('Provided error message cannot be empty');
        }

        $this->detail = (string) $detail;
    }

    /**
     * An application-specific error code, expressed as a string value.
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = (string) $code;
    }

    /**
     * A link that leads to further details about this particular occurrence of the problem.
     *
     * @param string $link
     *
     * @throws \InvalidArgumentException
     */
    public function setAboutLink($link)
    {
        if (false === filter_var($link, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException(sprintf('Provided link %s is not a valid resource', $link));
        }

        $this->links['about']['href'] = (string) $link;
    }

    /**
     * A meta object containing non-standard meta-information about the error.
     *
     * @param mixed $meta
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array_filter(
            [
                'id' => $this->id,
                'links' => $this->links,
                'status' => $this->status,
                'code' => $this->code,
                'title' => $this->title,
                'detail' => $this->detail,
                'source' => $this->source,
                'meta' => $this->meta,
            ]
        );
    }
}
