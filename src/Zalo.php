<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo;

use Zalo\Authentication\AccessToken;
use Zalo\Authentication\OAuth2Client;
use Zalo\Authentication\ZaloRedirectLoginHelper;
use Zalo\Url\UrlDetectionInterface;
use Zalo\Url\ZaloUrlDetectionHandler;
use Zalo\HttpClients\HttpClientsFactory;
use Zalo\Exceptions\ZaloSDKException;
use Zalo\ZaloClient;
use Zalo\ZaloRequest;

/**
 * Class Zalo
 *
 * @package Zalo
 */
class Zalo
{
    /**
     * @const string Version number of the Zalo PHP SDK.
     */
    const VERSION = '2.0.0';
    /**
     * @var ZaloClient The Zalo client service.
     */
    protected $client;
    /**
     * @var ZaloApp The ZaloApp entity.
     */
    protected $app;
    /**
     * @var UrlDetectionInterface|null The URL detection handler.
     */
    protected $urlDetectionHandler;
    /**
     * @var AccessToken|null The default access token to use with requests.
     */
    protected $defaultAccessToken;
    /**
     * @var ZaloResponse|ZaloBatchResponse|null Stores the last request made to Graph.
     */
    protected $lastResponse;
    /**
     * @var OAuth2Client The OAuth 2.0 client service.
     */
    protected $oAuth2Client;
    
    /**
     * Instantiates a new Zalo super-class object.
     *
     * @param array $config
     *
     * @throws ZaloSDKException
     */
    public function __construct(array $config = [])
    {
        $config = array_merge([
            'enable_beta_mode' => false,
            'http_client_handler' => 'curl',
            'url_detection_handler' => null,
        ], $config);
        $this->client = new ZaloClient(
            HttpClientsFactory::createHttpClient($config['http_client_handler']),
            $config['enable_beta_mode']
        );
        $this->app = new ZaloApp($config['app_id'], $config['app_secret'], $config['callback_url']);
        $this->setUrlDetectionHandler($config['url_detection_handler'] ?: new ZaloUrlDetectionHandler());
        if (isset($config['default_access_token'])) {
            $this->setDefaultAccessToken($config['default_access_token']);
        }
    }
    /**
     * Returns the last response returned from Graph.
     *
     * @return ZaloResponse|ZaloBatchResponse|null
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }
    /**
     * Returns the URL detection handler.
     *
     * @return UrlDetectionInterface
     */
    public function getUrlDetectionHandler()
    {
        return $this->urlDetectionHandler;
    }
    /**
     * Changes the URL detection handler.
     *
     * @param UrlDetectionInterface $urlDetectionHandler
     */
    private function setUrlDetectionHandler(UrlDetectionInterface $urlDetectionHandler)
    {
        $this->urlDetectionHandler = $urlDetectionHandler;
    }
    /**
     * Returns the default AccessToken entity.
     *
     * @return AccessToken|null
     */
    public function getDefaultAccessToken()
    {
        return $this->defaultAccessToken;
    }
    /**
     * Sets the default access token to use with requests.
     *
     * @param AccessToken|string $accessToken The access token to save.
     *
     * @throws \InvalidArgumentException
     */
    public function setDefaultAccessToken($accessToken)
    {
        if (is_string($accessToken)) {
            $this->defaultAccessToken = new AccessToken($accessToken);
            return;
        }
        if ($accessToken instanceof AccessToken) {
            $this->defaultAccessToken = $accessToken;
            return;
        }
        throw new \InvalidArgumentException('The default access token must be of type "string" or Zalo\AccessToken');
    }
    
    /**
     * Sends a GET request to Graph and returns the result.
     *
     * @param string                  $url
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function get($url, $accessToken = null, array $params = [], $eTag = null)
    {   
        return $this->sendRequest(
            'GET',
            $url,
            $params,
            $accessToken,
            $eTag
        );
    }
    /**
     * Sends a POST request to Graph and returns the result.
     *
     * @param string                  $url
     * @param AccessToken|string|null $accessToken
     * @param array                   $params
     * @param string|null             $eTag
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function post($url, $accessToken = null, $params = [] , $eTag = null)
    {
        return $this->sendRequest(
            'POST',
            $url,
            $params,
            $accessToken,
            $eTag
        );
    }
    /**
     * Sends a POST request to Graph and returns the result.
     *
     * @param string                  $url
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function uploadVideo($url, array $params = [], $accessToken = null, $eTag = null)
    {
        return $this->sendRequestUploadVideo(
            'POST',
            $url,
            $params,
            $accessToken,
            $eTag
        );
    }
    /**
     * Sends a DELETE request to Graph and returns the result.
     *
     * @param string                  $url
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function delete($url, array $params = [], $accessToken = null, $eTag = null)
    {
        return $this->sendRequest(
            'DELETE',
            $url,
            $params,
            $accessToken,
            $eTag
        );
    }
    /**
     * Sends a request to Graph and returns the result.
     *
     * @param string                  $method
     * @param string                  $url
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function sendRequest($method, $url, array $params = [], $accessToken = null, $eTag = null)
    {
        $request = $this->request($method, $url, $params, $accessToken, $eTag);
        return $this->lastResponse = $this->client->sendRequest($request);
    }
    /**
     * Sends a request upload video to OA and returns the result.
     *
     * @param string                  $method
     * @param string                  $url
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function sendRequestUploadVideo($method, $url, array $params = [], $accessToken = null, $eTag = null)
    {
        $request = $this->request($method, $url, $params, $accessToken, $eTag);
        return $this->lastResponse = $this->client->sendRequestUploadVideo($request);
    }
    /**
     * Instantiates a new ZaloRequest entity.
     *
     * @param string                  $method
     * @param string                  $url
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     *
     * @return ZaloRequest
     *
     * @throws ZaloSDKException
     */
    public function request($method, $url, array $params = [], $accessToken = null, $eTag = null)
    {
        $request =  new ZaloRequest(
            $accessToken,
            $method,
            $url,
            $params,
            $eTag
        );
        return $request;
    }
    /**
     * Returns the ZaloApp entity.
     *
     * @return ZaloApp
     */
    public function getApp()
    {
        return $this->app;
    }
    /**
     * Returns the ZaloClient service.
     *
     * @return ZaloClient
     */
    public function getClient()
    {
        return $this->client;
    }
    /**
     * Returns the OAuth 2.0 client service.
     *
     * @return OAuth2Client
     */
    public function getOAuth2Client()
    {
        if (!$this->oAuth2Client instanceof OAuth2Client) {
            $app = $this->getApp();
            $client = $this->getClient();
            $this->oAuth2Client = new OAuth2Client($app, $client);
        }
        return $this->oAuth2Client;
    }
    /**
     * Returns Login helper.
     *
     * @return ZaloRedirectLoginHelper
     */
    public function getRedirectLoginHelper()
    {
        return new ZaloRedirectLoginHelper(
            $this->getOAuth2Client(),
            $this->urlDetectionHandler
        );
    }
}