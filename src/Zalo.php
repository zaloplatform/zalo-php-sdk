<?php
/**
 * Zalo © 2017
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
use Zalo\ZaloApp;
use Zalo\ZaloOA;
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
    const VERSION = '1.0.3';
    /**
     * @const string Default Graph API version for requests.
     */
    const DEFAULT_GRAPH_VERSION = 'v2.0';
    /**
     * @const string Default OAuth API version for requests.
     */
    const DEFAULT_OAUTH_VERSION = 'v3';
    /**
     * @const string Default OfficalAccount API version for requests.
     */
    const DEFAULT_OA_VERSION = 'v1';
    /**
     * @const string The name of the environment variable that contains the app ID.
     */
    const APP_ID_ENV_NAME = 'ZALO_APP_ID';
    /**
     * @const string The name of the environment variable that contains the app secret.
     */
    const APP_SECRET_ENV_NAME = 'ZALO_APP_SECRET';
    /**
     * @const string The name of the environment variable that contains the Offical Account ID.
     */
    const OA_ID_ENV_NAME = 'ZALO_OA_ID';
    /**
     * @const string The name of the environment variable that contains the Offical Account secret key.
     */
    const OA_SECRET_ENV_NAME = 'ZALO_OA_SECRET';
    /**
     * @var ZaloOA The ZaloOA entity.
     */
    protected $oaInfo;
    
    /**
     * @const int OAuth api type.
     */
    const API_TYPE_AUTHEN = 0;
    
    /**
     * @const int Graph api type.
     */
    const API_TYPE_GRAPH = 1;
    
    /**
     * @const int OfficalAccount api type.
     */
    const API_TYPE_OA = 2;
    
    /**
     * @const int OfficalAccount api onbehalf type.
     */
    const API_TYPE_OA_ONBEHALF = 3;
    
    /**
     * @var ZaloApp The ZaloApp entity.
     */
    protected $app;
    /**
     * @var ZaloClient The Zalo client service.
     */
    protected $client;
    /**
     * @var OAuth2Client The OAuth 2.0 client service.
     */
    protected $oAuth2Client;
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
     * Instantiates a new Zalo super-class object.
     *
     * @param array $config
     *
     * @throws ZaloSDKException
     */
    public function __construct(array $config = [])
    {
        $config = array_merge([
            'app_id' => getenv(static::APP_ID_ENV_NAME),
            'app_secret' => getenv(static::APP_SECRET_ENV_NAME),
            'oa_id' => getenv(static::OA_ID_ENV_NAME),
            'oa_secret' => getenv(static::OA_SECRET_ENV_NAME),
            'enable_beta_mode' => false,
            'http_client_handler' => 'curl',
            'url_detection_handler' => null,
        ], $config);
        if (!$config['app_id']) {
            throw new ZaloSDKException('Required "app_id" key not supplied in config and could not find fallback environment variable "' . static::APP_ID_ENV_NAME . '"');
        }
        if (!$config['app_secret']) {
            throw new ZaloSDKException('Required "app_secret" key not supplied in config and could not find fallback environment variable "' . static::APP_SECRET_ENV_NAME . '"');
        }
        $this->app = new ZaloApp($config['app_id'], $config['app_secret']);
        $this->oaInfo = new ZaloOA($config['oa_id'], $config['oa_secret']);
        $this->client = new ZaloClient(
            HttpClientsFactory::createHttpClient($config['http_client_handler']),
            $config['enable_beta_mode']
        );
        $this->setUrlDetectionHandler($config['url_detection_handler'] ?: new ZaloUrlDetectionHandler());
        if (isset($config['default_access_token'])) {
            $this->setDefaultAccessToken($config['default_access_token']);
        }
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
            $this->oAuth2Client = new OAuth2Client($app, $client, static::DEFAULT_OAUTH_VERSION);
        }
        return $this->oAuth2Client;
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
     * Returns the default Graph version.
     *
     * @return string
     */
    public function getDefaultGraphVersion()
    {
        return $this->defaultGraphVersion;
    }
    
    /**
     * Returns the default OAuth version.
     *
     * @return string
     */
    public function getDefaultOAuthVersion()
    {
        return $this->defaultOAuthVersion;
    }
    
    /**
     * Sends a GET request to Graph and returns the result.
     *
     * @param string                  $endpoint
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     * @param string|null             $graphVersion
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function get($endpoint,array $params = [], $accessToken = null, $eTag = null)
    {   
        return $this->sendRequest(
            'GET',
            $endpoint,
            $params,
            $accessToken,
            $eTag
        );
    }
    /**
     * Sends a POST request to Graph and returns the result.
     *
     * @param string                  $endpoint
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     * @param string|null             $graphVersion
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function post($endpoint, $params = [], $accessToken = null, $eTag = null)
    {
        return $this->sendRequest(
            'POST',
            $endpoint,
            $params,
            $accessToken,
            $eTag
        );
    }
    /**
     * Sends a POST request to Graph and returns the result.
     *
     * @param string                  $endpoint
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     * @param string|null             $graphVersion
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function uploadVideo($endpoint, array $params = [], $accessToken = null, $eTag = null)
    {
        return $this->sendRequestUploadVideo(
            'POST',
            $endpoint,
            $params,
            $accessToken,
            $eTag
        );
    }
    /**
     * Sends a DELETE request to Graph and returns the result.
     *
     * @param string                  $endpoint
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     * @param string|null             $graphVersion
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function delete($endpoint, array $params = [], $accessToken = null, $eTag = null)
    {
        return $this->sendRequest(
            'DELETE',
            $endpoint,
            $params,
            $accessToken,
            $eTag
        );
    }
    /**
     * Sends a request to Graph and returns the result.
     *
     * @param string                  $method
     * @param string                  $endpoint
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     * @param string|null             $graphVersion
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function sendRequest($method, $endpoint, array $params = [], $accessToken = null, $eTag = null)
    {
        $request = $this->request($method, $endpoint, $params, $accessToken, $eTag);
        return $this->lastResponse = $this->client->sendRequest($request);
    }
    /**
     * Sends a request upload video to OA and returns the result.
     *
     * @param string                  $method
     * @param string                  $endpoint
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     * @param string|null             $graphVersion
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function sendRequestUploadVideo($method, $endpoint, array $params = [], $accessToken = null, $eTag = null)
    {
        $request = $this->request($method, $endpoint, $params, $accessToken, $eTag);
        return $this->lastResponse = $this->client->sendRequestUploadVideo($request);
    }
    /**
     * Instantiates a new ZaloRequest entity.
     *
     * @param string                  $method
     * @param string                  $endpoint
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     * @param string|null             $graphVersion
     *
     * @return ZaloRequest
     *
     * @throws ZaloSDKException
     */
    public function request($method, $endpoint, array $params = [], $accessToken = null, $eTag = null)
    {
        $request =  new ZaloRequest(
            $this->app,
            $this->oaInfo,
            $accessToken,
            $method,
            $endpoint,
            $params,
            $eTag
        );
        return $request;
    }
    
    public function getRedirectLoginHelper()
    {
        return new ZaloRedirectLoginHelper(
            $this->getOAuth2Client(),
            $this->urlDetectionHandler
        );
    }
}
