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

    /**
     * @var string UUID v4 form of request id
     */
    protected $requestId;

    /**
     * @param array|\ArrayAccess|null $serverData
     */
    function __construct($serverData = null)
    {
        if (null === $serverData) {
            $serverData = &$_SERVER;
        }

        if (!empty($serverData['HTTP_X_REQUEST_ID'])) {
            $candidate = $serverData['HTTP_X_REQUEST_ID'];
        } else if (!empty($serverData['UNIQUE_ID'])) {
            $candidate = $serverData['UNIQUE_ID'];
        } else {
            $candidate = null;
        }

        $this->requestId = $this->filter($candidate);
    }


    /**
     * validate of return default uuidv4
     * @param string $uuidv4 validate
     * @return string
     */
    public function filter($uuidv4)
    {
        $uuidv4 = (string);
        $uuidv4 = trim($uuidv4);

        if (preg_match('/[a-f0-9]{8}\-[a-f0-9]{4}\-4[a-f0-9]{3}\-(8|9|a|b)[a-f0-9]{3}\-[a-f0-9]{12}/', $uuidv4)) {
            return $uuidv4;
        }

        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
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

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }


}