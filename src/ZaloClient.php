<?php
/**
 * Zalo © 2019
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
     * @const int The timeout in seconds for a normal request.
     */
    const DEFAULT_REQUEST_TIMEOUT = 60;

    /**
     * @var bool Toggle to use  beta url.
     */
    protected $enableBetaMode = false;

    /**
     * @var ZaloHttpClientInterface HTTP client handler.
     */
    protected $httpClientHandler;

    /**
     * @var int The number of calls that have been made to .
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
     * Prepares the request for sending to the client handler.
     *
     * @param ZaloRequest $request
     *
     * @return array
     */
    public function prepareRequestMessage(ZaloRequest $request) {
        $url = $request->getUrl();
        // If we're sending files they should be sent as multipart/form-data
        if ($request->containsFileUploads()) {
            $requestBody = $request->getMultipartBody();
            $request->setHeaders([
                'Content-Type' => 'multipart/form-data; boundary=' . $requestBody->getBoundary(),
            ]);
        } else if ($request->getMethod() === 'GET' || $request->isGraph() === true) {
            $requestBody = $request->getUrlEncodedBody();
            $request->setHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]);
        } else {
            $requestBody = $request->getRawBody();
            $request->setHeaders([
                'Content-Type' => 'application/json',
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
     * Makes the request to  and returns the result.
     *
     * @param ZaloRequest $request
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function sendRequest(ZaloRequest $request) {
        $request->validateAccessToken();

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
     * Makes the upload request to  and returns the result.
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
            $request->getUrl(),
            $request->getMethod(),
            $request->getHeaders(),
            $requestBody->getBody(),
        ];
    }
}
