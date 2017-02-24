<?php

namespace CashbackApi;

use CashbackApi\Exception\ApiException;
use CashbackApi\Media\Media;
use CashbackApi\Reseller\Categories;

/**
 * Class Api
 * @package CashbackApi
 */
class BaseApi
{
    /**
     * @var null|string
     */
    private static $apiKey = null;
    /**
     * @var null|string
     */
    private static $url = null;

    /**
     * @var null|string
     */
    private static $type = null;

    /**
     * @var null|string
     */
    private static $siteName = null;
    /**
     * @var null|string
     */
    private static $sessionToken = null;

    /**
     * @var null|object
     */
    private static $session = null;
    /**
     * @var null|object
     */
    private static $contract = null;
    /**
     * @var null|int
     */
    private static $timeSessionGenerated = null;
    /**
     * @var null|callable
     */
    private static $sessionTokenStorageFunction = null;
    /**
     * @var null|callable
     */
    private static $sessionTokenRetrievalFunction = null;

    /**
     * @var null|callable
     */
    private static $authFailureFunction = null;
    /**
     * @var
     */
    private $lastResponse;
    /**
     * @var
     */
    private $schema;
    /**
     * @var
     */
    private $lastRawResponse;
    /**
     * @var
     */
    private $lastErrorMessage;
    /**
     * @var
     */
    protected static $lastErrorMessageAll;
    /**
     * @var null|object
     */
    private static $errorData = null;
    /**
     * @var null|object
     */
    protected static $errorDataAll = null;

    /**
     * @var array
     */
    private static $errorMessages = [];

    /**
     * @var array
     */
    protected static $errorMessagesAll = [];
    /**
     * @var
     */
    private $lastStatus;

    /**
     * @var null|string
     */
    private static $initialSessionType = null;

    /**
     * @var array
     */
    private $files;

    /**
     * @var null|Media
     */
    private static $apiMedia = null;

    /**
     * @var null|Categories
     */
    private static $apiCategories = null;
    /**
     * @var string
     */
    private static $removeValue = '#----REMOVE#VALUE----#';

    /**
     * Api constructor.
     * @param null $apiKey
     * @param null $url
     * @param null $sessionToken
     * @param null $timeSessionGenerated
     */
    public function __construct($apiKey = null, $url = null, $sessionToken = null, $timeSessionGenerated = null)
    {
        $this->setUrl($url);
        $this->setApiKey($apiKey);
        $this->setSessionToken($sessionToken, false);
        $this->setTimeSessionGenerated($timeSessionGenerated);
    }

    /**
     * @return string
     */
    protected function realNullVal()
    {
        return static::$removeValue;
    }

    /**
     * @return string
     */
    public static function realNullValue()
    {
        return static::$removeValue;
    }

    /**
     * @param $type
     */
    public static function setInitalSessionType($type)
    {
        $allowed = ['visitor', 'system'];
        if (in_array($type, $allowed)) {
            self::$initialSessionType = $type;
        } else {
            throw new ApiException("Session Type '{$type}' not allowed!");
        }
    }

    /**
     * @param int|null $timeSessionGenerated
     */
    private function setTimeSessionGenerated($timeSessionGenerated)
    {

        if (isset($timeSessionGenerated) && is_numeric($timeSessionGenerated)) {
            self::$timeSessionGenerated = (int)$timeSessionGenerated;
        }
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        if (!isset(self::$type)) {
            $tokenPresent = $this->getSessionToken();
            if (!$tokenPresent) {
                $this->generateSessionToken();
            }
        }
        return self::$type;
    }

    /**
     * @param null|string $type
     */
    private function setType($type)
    {
        self::$type = $type;
    }

    /**
     * @return null|string
     */
    public function getSiteName()
    {
        if (!isset(self::$siteName)) {
            $tokenPresent = $this->getSessionToken();
            if (!$tokenPresent) {
                $this->generateSessionToken();
            }
        }
        return self::$siteName;
    }

    /**
     * @param null|string $siteName
     */
    public function setSiteName($siteName)
    {
        self::$siteName = $siteName;
    }

    /**
     * @return null
     */
    public static function getInitialSessionType()
    {
        return self::$initialSessionType;

    }

    /**
     * @return int|null
     */
    protected function getTimeSessionGenerated()
    {
        return self::$timeSessionGenerated;
    }


    /**
     * @param $Function
     */
    public function setSessionTokenStorageFunction($Function)
    {
        self::$sessionTokenStorageFunction = $Function;
    }

    /**
     * @param $Function
     */
    public function setAuthFailureFunction($Function)
    {
        self::$authFailureFunction = $Function;
    }

    /**
     * @return bool|mixed
     */
    public function runAuthFailureFunction()
    {
        if (is_callable(self::$authFailureFunction)) {
            return call_user_func_array(self::$authFailureFunction, array());
        }
        return false;
    }

    /**
     * @param $Function
     */
    public function setSessionTokenRetrievalFunction($Function)
    {
        self::$sessionTokenRetrievalFunction = $Function;
    }

    /**
     * @param $tokenWithTime
     * @return bool|mixed
     */
    public function runSessionTokenStorageFunction($tokenWithTime)
    {
        if (is_callable(self::$sessionTokenStorageFunction)) {
            return call_user_func_array(self::$sessionTokenStorageFunction, array($tokenWithTime));
        }
        return false;
    }

    /**
     * @param string $path
     * @param null $data
     * @param bool $autoGenerateSessionToken
     * @return bool|object
     */
    public function doRequest($path = '', $data = null, $autoGenerateSessionToken = true)
    {
        $this->checkInitialType();

        $url = $this->getUrl() . '/' . $path;
        $sendData = $this->parseSendData($data);

        $content = json_encode($sendData);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            $this->getRequestHeader($autoGenerateSessionToken)
        );
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);


        return $this->parseCurlResponse($curl);

    }

    /**
     * @param string $path
     * @param null $data
     * @param bool $autoGenerateSessionToken
     * @return bool
     * @throws ApiException
     */
    public function doUpload($path = '', $data = null, $autoGenerateSessionToken = true)
    {


        $this->checkInitialType();

        $url = $this->getUrl() . '/' . $path;
        $sendData = $this->parseSendData($data, true);
        $content = $sendData;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getRequestHeader($autoGenerateSessionToken, 'multipart/form-data'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_INFILESIZE, $sendData->totalFileSize);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        return $this->parseCurlResponse($curl);
    }


    /**
     * @param $curl
     * @return bool|object
     */
    private function parseCurlResponse($curl)
    {
        $this->lastRawResponse = curl_exec($curl);
        $this->lastStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $this->lastResponse = json_decode($this->lastRawResponse);

        return $this->parseResponse($this->lastResponse);
    }

    /**
     * @param bool $autoGenerateSessionToken
     * @param string $contentType
     * @return array
     */
    private function getRequestHeader($autoGenerateSessionToken = true, $contentType = 'application/json')
    {

        $header = array(
            "Content-Type: " . $contentType,
            "Api-Key: " . $this->getApiKey()
        );

        $sessionToken = $this->getRequestSessionToken($autoGenerateSessionToken);
        if ($sessionToken) {
            $header[] = "Session-Token: " . $sessionToken;
        }
        return $header;
    }


    /**
     * @throws ApiException
     */
    private function checkInitialType()
    {
        if (self::getInitialSessionType() == null) {
            throw new ApiException('Initial Session Type Needs to be set to visitor or system');
        }
    }

    /**
     * @param bool $autoGenerateSessionToken
     * @return bool|mixed|string
     * @throws ApiException
     */
    private function getRequestSessionToken($autoGenerateSessionToken = true)
    {
        if ($this->sessionTokenReady()) {
            return $this->getSessionToken();
        } elseif ($autoGenerateSessionToken) {
            $token = $this->generateSessionToken();
            if ($token) {
                return $token;
            } else {
                throw new ApiException('Could not generate Token - ' . $this->getLastErrorMessageAll());
            }
        }
    }

    /**
     * @param $data
     * @param $autoGenerateSessionToken
     * @return \stdClass
     */
    private function parseSendData($data, $uploadFile = false)
    {
        $sendData = new \stdClass();
        if (is_object($data)) {
            foreach ($data as $key => $value) {
                if (($uploadFile && !is_object($value)) || !$uploadFile) {

                    /**
                     * Normal Parse Send Data
                     */

                    $sendData->$key = $value;
                } elseif ($uploadFile && is_object($value)) {
                    /**
                     * Deal with Parse Send Data for
                     * multidimensional Objects on Upload File
                     */
                    $sendData->$key = json_encode($value);
                }
            }
        }
        if ($uploadFile) {
            $this->processFileData($sendData);

        }

        return $sendData;
    }


    private function processFileData(&$sendData)
    {
        $sendData->totalFileSize = 0;
        if (!(isset($this->files) && is_array($this->files) && count($this->files))) {
            $this->setLastErrorMessage('No files Attached');
            return false;
        }

        foreach ($this->files as $key => $value) {
            $filePath = $this->files[$key]['tmp_name'];
            $fileType = @mime_content_type($filePath);
            $sendData->totalFileSize = $sendData->totalFileSize + $this->files[$key]['size'];
            $fileName = $this->files[$key]['name'];
            $curlFile = new \CURLFile($filePath, $fileType, $fileName);
            $sendData->$key = $curlFile;
        }

    }

    /**
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @return mixed
     */
    public function getLastRawResponse()
    {
        return $this->lastRawResponse;
    }

    /**
     * @return mixed
     */
    public function getLastStatus()
    {
        return $this->lastStatus;
    }

    /**
     * @return mixed
     */
    public function getLastErrorMessage()
    {
        return $this->lastErrorMessage;
    }

    /**
     * @return mixed
     */
    public function getLastErrorMessageAll()
    {
        return static::$lastErrorMessageAll;
    }

    public function getErrorMessages()
    {
        return (count(self::$errorMessages)) ? self::$errorMessages : false;
    }

    public function getErrorMessagesAll()
    {
        return (count(static::$errorMessagesAll)) ? static::$errorMessagesAll : false;
    }

    /**
     * @param $errorMessage
     */
    protected function setLastErrorMessage($errorMessage)
    {
        $this->lastErrorMessage = $errorMessage;
        static::$lastErrorMessageAll = $errorMessage;
        $this->setErrorMessage($errorMessage);

    }

    protected function setErrorMessage($errorMessage)
    {
        self::$errorMessages[] = $errorMessage;
        static::$errorMessagesAll[] = $errorMessage;
    }

    protected function setErrorData($errorData)
    {
        self::$errorData = $errorData;
        static::$errorDataAll = $errorData;
    }

    public function getErrorData()
    {
        return (isset(self::$errorData)) ? self::$errorData : false;
    }

    public function getErrorDataAll()
    {
        return (isset(static::$errorDataAll)) ? static::$errorDataAll : false;
    }

    /**
     * @return mixed
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @return bool|string
     */
    private function generateSessionToken()
    {
        $data = new \stdClass();
        $data->type = self::getInitialSessionType();
        $data->ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        $data->user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;

        $tokenObject = $this->doRequest('session/open', $data, false);

        if (is_object($tokenObject) && isset($tokenObject->token) && $tokenObject->token) {

            $this->setSessionToken($tokenObject->token, true, $tokenObject);
            return $tokenObject->token;
        }
        return false;
    }

    public function getContract()
    {
        if (self::$contract == null) {
            return self::$contract = $this->doRequest('contract/get');
        }

        return self::$contract;

    }

    public function getSession()
    {
        if (self::$session == null) {

            return self::$session = $this->doRequest('session/get');
        }

        return self::$session;

    }

    protected function parseResponse($response)
    {

        if (is_object($response) && isset($response->response) &&
            isset($response->success) && $response->success == true
        ) {
            return $response->response;
        } elseif ((isset($response->message) || isset($response->error_data)) && isset($response->success) && $response->success == false) {
            if (isset($response->message)) {
                $this->setLastErrorMessage($response->message);
            }
            if (isset($response->error_data)) {
                $this->setErrorData($response->error_data);
            }
        } elseif ($this->getLastStatus() != 200) {
            $message = isset($response->message) ? ' (' . $response->message . ')' : '';
            $this->setLastErrorMessage('Server Error ' . $this->getLastStatus() . '!' . $message);
        }

        if ($this->getLastStatus() == 401) {
            $this->runAuthFailureFunction();
        }

        if (isset($response->schema)) {
            $this->requestSchema = $response->schema;
        }
        return false;
    }


    /**
     * @return bool
     */
    public function sessionTokenReady()
    {

        if ($this->getSessionToken() != null) {
            if ($this->sessionInTime()) {
                return true;
            }
        }

        return false;
    }

    private function sessionInTime()
    {
        $time = $this->getTimeSessionGenerated();

        if ($time == null) {
            return false;
        }

        if (is_int($time) && $time > (time() - (60 * 60 * 24))) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        if (!isset(self::$apiKey)) {
            throw new ApiException('API Key not set!');
        }
        return self::$apiKey;
    }

    /**
     * @param mixed $apiKey
     */
    protected function setApiKey($apiKey)
    {
        if (isset($apiKey)) {
            self::$apiKey = $apiKey;
        }
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        if (!isset(self::$url)) {
            throw new ApiException('API URL not set!');
        }
        return self::$url;
    }

    /**
     * @param mixed $url
     */
    protected function setUrl($url)
    {
        if (isset($url)) {
            self::$url = $url;
        }
    }


    /**
     * @return null|string
     */
    public function getSessionToken()
    {
        if (is_callable(self::$sessionTokenRetrievalFunction)) {
            $tokenWithTime = call_user_func(self::$sessionTokenRetrievalFunction);
            if (isset($tokenWithTime) && is_string($tokenWithTime)) {
                $tokenWithTimeParts = explode(':::', $tokenWithTime);
                $token = (isset($tokenWithTimeParts[0])) ? $tokenWithTimeParts[0] : false;
                $time = (isset($tokenWithTimeParts[1])) ? $tokenWithTimeParts[1] : false;
                $type = (isset($tokenWithTimeParts[2])) ? $tokenWithTimeParts[2] : false;
                $name = (isset($tokenWithTimeParts[3])) ? $tokenWithTimeParts[3] : false;
                if ($time) {
                    $this->setTimeSessionGenerated($time);
                }
                if ($token) {
                    $this->setSessionToken($token, false);
                }
                if ($type) {
                    $this->setType($type);
                }
                if ($name) {
                    $this->setSiteName($name);
                }
            }
        }
        return self::$sessionToken;
    }


    /**
     * @param $sessionToken
     * @return bool|string
     */
    private function setSessionToken($sessionToken, $newGeneration = false, $tokenObject = null)
    {


        if (isset($sessionToken) && is_string($sessionToken)) {

            if ($newGeneration) {
                $type = (isset($tokenObject->type)) ? $tokenObject->type : 'no-type';
                $name = (isset($tokenObject->name)) ? $tokenObject->name : 'no-name';
                $time = time();
                $this->runSessionTokenStorageFunction($sessionToken . ':::' . $time . ':::' . $type . ':::' . $name);
                $this->setTimeSessionGenerated($time);
                $this->setType($type);
                $this->setSiteName($name);
            }
            return self::$sessionToken = $sessionToken;
        }

        return false;
    }


    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }


    /**
     * @param array $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }


    public function addFile($inputName, $path, $name, $size = null)
    {
        if ($size === null) {
            $size = sizeof($path);
        }
        $file = array(
            'tmp_name' => $path,
            'size' => $size,
            'name' => $name,
        );
        $this->files[$inputName] = $file;
    }

    /**
     * @return bool|object
     */
    protected function closeSession()
    {
        return $this->doRequest('session/close');
    }

    /**
     * @param $data
     * @param $newData
     */
    protected function mapData(&$data, $newData)
    {
        if (isset($newData) && is_object($newData)) {
            foreach ($newData as $key => $value) {
                if (property_exists($data, $key)) {
                    $data->$key = $value;
                }
            }
        }
    }

    /**
     * @param $data
     * @param null $validateFields
     * @param array $alwaysKeep
     */
    protected function formatDataWithValidateFields(&$data, $validateFields = null, $alwaysKeep = [])
    {
        if (is_array($validateFields) && count($validateFields)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $validateFields) && !in_array($key, $alwaysKeep)) {
                    unset($data->$key);
                }
            }
        }
    }

    /**
     * @return Media|null
     */
    public function getMedia()
    {
        if (isset(self::$apiMedia)) {
            return self::$apiMedia;
        }
        $apiMedia = new Media();
        self::$apiMedia = $apiMedia;
        return self::$apiMedia;
    }

    /**
     * @return Categories|null
     */
    public function getApiCategories()
    {
        if (isset(self::$apiCategories)) {
            return self::$apiCategories;
        }
        $apiCategories = new Categories();
        self::$apiCategories = $apiCategories;
        return self::$apiCategories;
    }


}