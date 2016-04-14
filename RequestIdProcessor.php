<?php
/**
 * User: evaisse
 * Date: 14/04/2016
 * Time: 14:36
 */


namespace evaisse\MonologRequestIdProcessor;

/**
 * Class RequestIdProcessor
 */
class RequestIdProcessor
{

    protected $requestId;

    /**
     * @param array|\ArrayAccess|null $serverData
     */
    function __construct($serverData = null)
    {
        if (null === $serverData) {
            $serverData = &$_SERVER;
        }

        if (!empty($_SERVER['HTTP_X_REQUEST_ID'])) {
            $candidate = $_SERVER['HTTP_X_REQUEST_ID'];
        } else if (!empty($_SERVER['UNIQUE_ID'])) {
            $candidate = $_SERVER['UNIQUE_ID'];
        }

        $this->requestId = (string)$candidate;
        $this->requestId = trim($this->requestId);
    }


    /**
     * @param  array $record
     * @return array transformed record
     */
    public function __invoke(array $record)
    {
        $record['extra']['request_id'] = $this->requestId;

        return $record;
    }

}