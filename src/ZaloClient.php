<?php
/**
 * Zalo © 2017
 *
 */

namespace Zalo;

use Zalo\Exceptions\ZaloSDKException;
use Zalo\HttpClients\ZaloCurlHttpClient;
use Zalo\HttpClients\ZaloHttpClientInterface;

/**
 * Class ZaloClient
 *
 * @package Zalo
 */
class ZaloClient {

    /**
     * @const string Production Graph API URL.
     */
    const BASE_GRAPH_URL = 'https://graph.zalo.me';

    /**
     * @const string Production OAuth API URL.
     */
    const BASE_AUTHEN_URL = 'https://oauth.zaloapp.com';

    /**
     * @const string Production OfficalAccount API URL.
     */
    const BASE_OA_URL = 'https://openapi.zaloapp.com/oa';

    /**
     * @const int The timeout in seconds for a normal request.
     */
    const DEFAULT_REQUEST_TIMEOUT = 60;

    /**
     * @var bool Toggle to use Graph beta url.
     */
    protected $enableBetaMode = false;

    /**
     * @var ZaloHttpClientInterface HTTP client handler.
     */
    protected $httpClientHandler;

    /**
     * @var int The number of calls that have been made to Graph.
     */
    public static $requestCount = 0;

    /**
     * Instantiates a new ZaloClient object.
     *
     * @param ZaloHttpClientInterface|null $httpClientHandler
     * @param boolean                          $enableBeta
     */
    public function __construct(ZaloHttpClientInterface $httpClientHandler = null, $enableBeta = false) {
        $this->httpClientHandler = $httpClientHandler ? : $this->detectHttpClientHandler();
        $this->enableBetaMode = $enableBeta;
    }

    /**
     * Sets the HTTP client handler.
     *
     * @param ZaloHttpClientInterface $httpClientHandler
     */
    public function setHttpClientHandler(ZaloHttpClientInterface $httpClientHandler) {
        $this->httpClientHandler = $httpClientHandler;
    }

    /**
     * Returns the HTTP client handler.
     *
     * @return ZaloHttpClientInterface
     */
    public function getHttpClientHandler() {
        return $this->httpClientHandler;
    }

    /**
     * Detects which HTTP client handler to use.
     *
     * @return ZaloHttpClientInterface
     */
    public function detectHttpClientHandler() {
        return new ZaloCurlHttpClient();
    }

    /**
     * Toggle beta mode.
     *
     * @param boolean $betaMode
     */
    public function enableBetaMode($betaMode = true) {
        $this->enableBetaMode = $betaMode;
    }

    /**
     * Returns the base Graph URL.
     *
     * @param boolean $postToVideoUrl Post to the video API if videos are being uploaded.
     *
     * @return string
     */
    public function getBaseUrl($apiType) {
        if ($apiType == Zalo::API_TYPE_AUTHEN) {
            return static::BASE_AUTHEN_URL;
        } else if ($apiType == Zalo::API_TYPE_GRAPH) {
            return static::BASE_GRAPH_URL;
        }
        return static::BASE_OA_URL;
    }

    /**
     * Prepares the request for sending to the client handler.
     *
     * @param ZaloRequest $request
     *
     * @return array
     */
    public function prepareRequestMessage(ZaloRequest $request) {
        $url = $this->getBaseUrl($request->getApiType()) . $request->getUrl();

        // If we're sending files they should be sent as multipart/form-data
        if ($request->containsFileUploads()) {
            $requestBody = $request->getMultipartBody();
            $request->setHeaders([
                'Content-Type' => 'multipart/form-data; boundary=' . $requestBody->getBoundary(),
            ]);
        } else {
            $requestBody = $request->getUrlEncodedBody();
            $request->setHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]);
        }
        return [
            $url,
            $request->getMethod(),
            $request->getHeaders(),
            $requestBody->getBody(),
        ];
    }

    /**
     * Makes the request to Graph and returns the result.
     *
     * @param ZaloRequest $request
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function sendRequest(ZaloRequest $request) {
        if ($request->getApiType() === Zalo::API_TYPE_GRAPH) {
            $request->validateAccessToken();
        } else if ($request->getApiType() === Zalo::API_TYPE_OA) {
            $params = $this->getParamsData($request);
            $request->setParams($params);
        } else if ($request->getApiType() === Zalo::API_TYPE_OA_ONBEHALF) {
            $request->validateAccessToken();
            $params = $this->getParamsOAOnbehalf($request);
            $request->setParams($params);
        }

        list($url, $method, $headers, $body) = $this->prepareRequestMessage($request);
        
        // Since file uploads can take a while, we need to give more time for uploads
        $timeOut = static::DEFAULT_REQUEST_TIMEOUT;

        // Should throw `ZaloSDKException` exception on HTTP client error.
        // Don't catch to allow it to bubble up.
        $rawResponse = $this->httpClientHandler->send($url, $method, $body, $headers, $timeOut);
        static::$requestCount++;

        $returnResponse = new ZaloResponse(
                $request, $rawResponse->getBody(), $rawResponse->getHttpResponseCode(), $rawResponse->getHeaders()
        );

        if ($returnResponse->isError()) {
            throw $returnResponse->getThrownException();
        }

        return $returnResponse;
    }

    /**
     * Makes the upload request to Graph and returns the result.
     *
     * @param ZaloRequest $request
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function sendRequestUploadVideo(ZaloRequest $request) {

        list($url, $method, $headers, $body) = $this->prepareUploadVideoRequestMessage($request);
        // Since file uploads can take a while, we need to give more time for uploads
        $timeOut = static::DEFAULT_REQUEST_TIMEOUT;

        // Should throw `ZaloSDKException` exception on HTTP client error.
        // Don't catch to allow it to bubble up.
        $rawResponse = $this->httpClientHandler->send($url, $method, $body, $headers, $timeOut);
        static::$requestCount++;

        $returnResponse = new ZaloResponse(
                $request, $rawResponse->getBody(), $rawResponse->getHttpResponseCode(), $rawResponse->getHeaders()
        );

        if ($returnResponse->isError()) {
            throw $returnResponse->getThrownException();
        }

        return $returnResponse;
    }

    /**
     * Prepares the request for sending to the client handler.
     *
     * @param ZaloRequest $request
     *
     * @return array
     */
    public function prepareUploadVideoRequestMessage(ZaloRequest $request) {
        // If we're sending files they should be sent as multipart/form-data
        $requestBody = $request->getMultipartBody();
        $request->setHeaders([
            'Content-Type' => 'multipart/form-data; boundary=' . $requestBody->getBoundary(),
        ]);
        return [
            $request->getEndpoint(),
            $request->getMethod(),
            $request->getHeaders(),
            $requestBody->getBody(),
        ];
    }

    private function getParamsData(ZaloRequest $request) {
        $paramsResult = [];
        $params = $request->getParams();
        $timestamp = time();
        $oaid = $request->getOAInfo()->getId();
        $secret = $request->getOAInfo()->getSecret();
        $data = "";
        if (isset($params['uid'])) {
            $uid = $params['uid'];
            $mac = hash("sha256", utf8_encode($oaid . $uid . $timestamp . $secret));
            $paramsResult = ['uid' => $uid,
                'mac' => $mac];
        } else if (isset($params['msgid'])) {
            $msgid = $params['msgid'];
            $mac = hash("sha256", utf8_encode($oaid . $msgid . $timestamp . $secret));
            $paramsResult = ['msgid' => $msgid,
                'mac' => $mac];
        } else if (isset($params['orderid'])) {
            $orderid = $params['orderid'];
            $mac = hash("sha256", utf8_encode($oaid . $orderid . $timestamp . $secret));
            $paramsResult = ['orderid' => $orderid,
                'mac' => $mac];
        } else if (isset($params['productid'])) {
            $productId = $params['productid'];
            $mac = hash("sha256", utf8_encode($oaid . $productId . $timestamp . $secret));
            $paramsResult = ['productid' => $productId,
                'mac' => $mac];
        } else if (isset($params['media'])) {
            $media = json_encode($params['media']);
            $mac = hash("sha256", utf8_encode($oaid . $media . $timestamp . $secret));
            $paramsResult = ['media' => $media,
                'mac' => $mac];
        } else if (isset($params['mediaid'])) {
            $mediaId = $params['mediaid'];
            $mac = hash("sha256", utf8_encode($oaid . $mediaId . $timestamp . $secret));
            $paramsResult = ['mediaid' => $mediaId,
                'mac' => $mac];
        } else if (isset($params['token'])) {
            $token = $params['token'];
            $mac = hash("sha256", utf8_encode($oaid . $token . $timestamp . $secret));
            $paramsResult = ['token' => $token,
                'mac' => $mac];
        } else if (isset($params['data'])) {
            $data = json_encode($params['data']);
            $mac = hash("sha256", utf8_encode($oaid . $data . $timestamp . $secret));
            $paramsResult = ['data' => $data,
                'mac' => $mac];
        } else {
            $mac = hash("sha256", utf8_encode($oaid . $timestamp . $secret));
            $paramsResult = ['mac' => $mac];
        }
        $baseParams = ['oaid' => $oaid,
            'timestamp' => $timestamp];
        $paramsResult = array_merge($paramsResult, $baseParams);
        return $paramsResult;
    }

    private function getParamsOAOnbehalf(ZaloRequest $request) {
        $paramsResult = [];
        $params = $request->getParams();
        $timestamp = time();
        $appid = $request->getApp()->getId();
        $secret = $request->getApp()->getSecret();
        $data = "";
        if (isset($params['data'])) {
            $params['data']['accessTok'] = $request->getAccessToken();
            $data = json_encode($params['data']);
            $mac = hash("sha256", utf8_encode($appid . $data . $timestamp . $secret));
            $paramsResult = ['data' => $data,
                'mac' => $mac];
        } else {
            $accessToken = array(
                'accessTok' => $request->getAccessToken()
            );
            $params = ['data' => $accessToken];
            $data = json_encode($params['data']);
            $mac = hash("sha256", utf8_encode($appid . $data . $timestamp . $secret));
            $paramsResult = ['data' => $data,
                'mac' => $mac];
        }
        $baseParams = ['appid' => $appid,
            'timestamp' => $timestamp];
        $paramsResult = array_merge($paramsResult, $baseParams);
        return $paramsResult;
    }

}
