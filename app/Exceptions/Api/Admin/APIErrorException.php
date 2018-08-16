<?php
namespace App\Exceptions\Api\Admin;

use App\Http\Responses\Api\V1\Status;

class APIErrorException extends \App\Exceptions\Api\APIErrorException
{
    /** @var string */
    protected $userMessage = '';

    protected $errorName   = '';

    protected $extraData   = [];

    protected $config      = [];

    /**
     * APIErrorException constructor.
     *
     * @param string $error
     * @param string $message
     * @param array  $extraData
     */
    public function __construct($error, $message, $extraData = [])
    {
        $this->errorName   = $error;
        $this->userMessage = $message;
        $this->extraData   = $extraData;
        $this->config      = $this->errorConfig();
        parent::__construct($message, $this->config['code'], null);
    }

    /**
     * @return \Response|\Illuminate\Http\JsonResponse
     */
    public function getErrorResponse()
    {
        return Status::error($this->errorName, $this->userMessage, $this->extraData)->response();
    }

    /**
     * @return array
     */
    protected function errorConfig()
    {
        $error = config('api.errors.'.$this->errorName);
        if (empty($error)) {
            $error = config('api.errors.unknown');
        }

        return $error;
    }
}
