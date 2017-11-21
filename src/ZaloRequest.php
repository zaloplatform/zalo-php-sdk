<?php
/**
 * Zalo © 2017
 *
 */

namespace Zalo;

use Zalo\Authentication\AccessToken;
use Zalo\Url\ZaloUrlManipulator;
use Zalo\Http\RequestBodyUrlEncoded;
use Zalo\Http\RequestBodyMultipart;
use Zalo\Exceptions\ZaloSDKException;
use Zalo\FileUpload\ZaloFile;

/**
 * Class Request
 *
 * @package Zalo
 */
class ZaloRequest
{
    /**
     * @var ZaloApp The Zalo app entity.
     */
    protected $app;
    
    /**
     * @var ZaloOA The Zalo Offical Account entity.
     */
    protected $oaInfo;

    /**
     * @var string|null The access token to use for this request.
     */
    protected $accessToken;

    /**
     * @var string The HTTP method for this request.
     */
    protected $method;

    /**
     * @var string The Graph endpoint for this request.
     */
    protected $endpoint;

    /**
     * @var array The headers to send with this request.
     */
    protected $headers = [];

    /**
     * @var array The parameters to send with this request.
     */
    protected $params = [];

    /**
     * @var array The files to send with this request.
     */
    protected $files = [];

    /**
     * @var string ETag to send with this request.
     */
    protected $eTag;
    
    /**
     * @var string API Type : OAUTH = 0 | GRAPH = 1 | OA = 2
     */
    protected $apiType;


    /**
     * Creates a new Request entity.
     *
     * @param ZaloApp|null        $app
     * @param AccessToken|string|null $accessToken
     * @param string|null             $method
     * @param string|null             $endpoint
     * @param array|null              $params
     * @param string|null             $eTag
     * @param string|null             $graphVersion
     */
    public function __construct(ZaloApp $app = null, ZaloOA $oaInfo = null, $accessToken = null, $method = null, $endpoint = null, array $params = [], $eTag = null)
    {
        $zaloApiType = ZaloAPIManager::getInstance()->getMapEndPoint()[$endpoint];
        $this->setApp($app);
        $this->setOAInfo($oaInfo);
        $this->setAccessToken($accessToken);
        $this->setMethod($method);
        $this->setEndpoint($endpoint);
        $this->setParams($params);
        $this->setETag($eTag);
        $this->setApiType($zaloApiType);
    }

    /**
     * Set the access token for this request.
     *
     * @param AccessToken|string|null
     *
     * @return ZaloRequest
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        if ($accessToken instanceof AccessToken) {
            $this->accessToken = $accessToken->getValue();
        }

        return $this;
    }

    /**
     * Sets the access token with one harvested from a URL or POST params.
     *
     * @param string $accessToken The access token.
     *
     * @return ZaloRequest
     *
     * @throws ZaloSDKException
     */
    public function setAccessTokenFromParams($accessToken)
    {
        $existingAccessToken = $this->getAccessToken();
        if (!$existingAccessToken) {
            $this->setAccessToken($accessToken);
        } elseif ($accessToken !== $existingAccessToken) {
            throw new ZaloSDKException('Access token mismatch. The access token provided in the ZaloRequest and the one provided in the URL or POST params do not match.');
        }

        return $this;
    }

    /**
     * Return the access token for this request.
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Return the access token for this request as an AccessToken entity.
     *
     * @return AccessToken|null
     */
    public function getAccessTokenEntity()
    {
        return $this->accessToken ? new AccessToken($this->accessToken) : null;
    }

    /**
     * Set the ZaloApp entity used for this request.
     *
     * @param ZaloApp|null $app
     */
    public function setApp(ZaloApp $app = null)
    {
        $this->app = $app;
    }

    /**
     * Return the ZaloApp entity used for this request.
     *
     * @return ZaloApp
     */
    public function getApp()
    {
        return $this->app;
    }
    
    /**
     * Set the ZaloOA entity used for this request.
     *
     * @param ZaloOA|null $app
     */
    public function setOAInfo(ZaloOA $oa = null)
    {
        $this->oaInfo = $oa;
    }

    /**
     * Return the ZaloApp entity used for this request.
     *
     * @return ZaloOA
     */
    public function getOAInfo()
    {
        return $this->oaInfo;
    }
    
    /**
     * Set the API Type for this request.
     *
     * @param apiType|null $apiType
     */
    public function setApiType($apiType = null)
    {
        $this->apiType = $apiType;
    }

    /**
     * Return the API Type for this request.
     *
     * @return apiType
     */
    public function getApiType()
    {
        return $this->apiType;
    }

    /**
     * Generate an app secret proof to sign this request.
     *
     * @return string|null
     */
    public function getAppSecretProof()
    {
        if (!$accessTokenEntity = $this->getAccessTokenEntity()) {
            return null;
        }

        return $accessTokenEntity->getAppSecretProof($this->app->getSecret());
    }

    /**
     * Validate that an access token exists for this request.
     *
     * @throws ZaloSDKException
     */
    public function validateAccessToken()
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            throw new ZaloSDKException('You must provide an access token.');
        }
    }

    /**
     * Set the HTTP method for this request.
     *
     * @param string
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
    }

    /**
     * Return the HTTP method for this request.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Validate that the HTTP method is set.
     *
     * @throws ZaloSDKException
     */
    public function validateMethod()
    {
        if (!$this->method) {
            throw new ZaloSDKException('HTTP method not specified.');
        }

        if (!in_array($this->method, ['GET', 'POST', 'DELETE'])) {
            throw new ZaloSDKException('Invalid HTTP method specified.');
        }
    }

    /**
     * Set the endpoint for this request.
     *
     * @param string
     *
     * @return ZaloRequest
     *
     * @throws ZaloSDKException
     */
    public function setEndpoint($endpoint)
    {
        // Harvest the access token from the endpoint to keep things in sync
        $params = ZaloUrlManipulator::getParamsAsArray($endpoint);
        if (isset($params['access_token'])) {
            $this->setAccessTokenFromParams($params['access_token']);
        }

        // Clean the token & app secret proof from the endpoint.
        $filterParams = ['access_token', 'appsecret_proof'];
        $this->endpoint = ZaloUrlManipulator::removeParamsFromUrl($endpoint, $filterParams);
        
        return $this;
    }

    /**
     * Return the endpoint for this request.
     *
     * @return string
     */
    public function getEndpoint()
    {
        // For batch requests, this will be empty
        return $this->endpoint;
    }

    /**
     * Generate and return the headers for this request.
     *
     * @return array
     */
    public function getHeaders()
    {
        $headers = static::getDefaultHeaders();

        if ($this->eTag) {
            $headers['If-None-Match'] = $this->eTag;
        }

        return array_merge($this->headers, $headers);
    }

    /**
     * Set the headers for this request.
     *
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Sets the eTag value.
     *
     * @param string $eTag
     */
    public function setETag($eTag)
    {
        $this->eTag = $eTag;
    }

    /**
     * Set the params for this request.
     *
     * @param array $params
     *
     * @return ZaloRequest
     *
     * @throws ZaloSDKException
     */
    public function setParams(array $params = [])
    {
        if (isset($params['access_token'])) {
            $this->setAccessTokenFromParams($params['access_token']);
        }

        // Don't let these buggers slip in.
        unset($params['access_token']);

        // @TODO Refactor code above with this
        //$params = $this->sanitizeAuthenticationParams($params);
        $params = $this->sanitizeFileParams($params);
        $this->dangerouslySetParams($params);

        return $this;
    }

    /**
     * Set the params for this request without filtering them first.
     *
     * @param array $params
     *
     * @return ZaloRequest
     */
    public function dangerouslySetParams(array $params = [])
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    /**
     * Iterate over the params and pull out the file uploads.
     *
     * @param array $params
     *
     * @return array
     */
    public function sanitizeFileParams(array $params)
    {
        foreach ($params as $key => $value) {
            if ($value instanceof ZaloFile) {
                $this->addFile($key, $value);
                unset($params[$key]);
            }
        }

        return $params;
    }
    
    /**
     * Add a file to be uploaded.
     *
     * @param string       $key
     * @param ZaloFile $file
     */
    public function addFile($key, ZaloFile $file)
    {
        $this->files[$key] = $file;
    }
    
    /**
     * Removes all the files from the upload queue.
     */
    public function resetFiles()
    {
        $this->files = [];
    }

    /**
     * Get the list of files to be uploaded.
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Let's us know if there is a file upload with this request.
     *
     * @return boolean
     */
    public function containsFileUploads()
    {
        return !empty($this->files);
    }
    
    /**
     * Returns the body of the request as multipart/form-data.
     *
     * @return RequestBodyMultipart
     */
    public function getMultipartBody()
    {
        $params = $this->getPostParams();

        return new RequestBodyMultipart($params, $this->files);
    }

    /**
     * Returns the body of the request as URL-encoded.
     *
     * @return RequestBodyUrlEncoded
     */
    public function getUrlEncodedBody()
    {
        $params = $this->getPostParams();
        return new RequestBodyUrlEncoded($params);
    }

    /**
     * Generate and return the params for this request.
     *
     * @return array
     */
    public function getParams()
    {
        $params = $this->params;

        $accessToken = $this->getAccessToken();
        if ($accessToken && $this->getApiType() !== Zalo::API_TYPE_OA_ONBEHALF) {
            $params['access_token'] = $accessToken;
        }

        return $params;
    }

    /**
     * Only return params on POST requests.
     *
     * @return array
     */
    public function getPostParams()
    {
        if ($this->getMethod() === 'POST') {
            return $this->getParams();
        }

        return [];
    }

    /**
     * Generate and return the URL for this request.
     *
     * @return string
     */
    public function getUrl()
    {
        $this->validateMethod();
        $version = "";
        if ($this->getApiType() == Zalo::API_TYPE_AUTHEN) {
            $version = Zalo::DEFAULT_OAUTH_VERSION;
        } else if ($this->getApiType() == Zalo::API_TYPE_GRAPH) {
            $version = Zalo::DEFAULT_GRAPH_VERSION;
        } else {
            $version = Zalo::DEFAULT_OA_VERSION;
        }
        $version = ZaloUrlManipulator::forceSlashPrefix($version);
        $endpoint = ZaloUrlManipulator::forceSlashPrefix($this->getEndpoint());

        $url = $version . $endpoint;

        if ($this->getMethod() !== 'POST') {
            $params = $this->getParams();
            $url = ZaloUrlManipulator::appendParamsToUrl($url, $params);
        }
        return $url;
    }

    /**
     * Return the default headers that every request should use.
     *
     * @return array
     */
    public static function getDefaultHeaders()
    {
        return [
            'SDK-Source' => 'ZALO-PHP-SDK-v' . Zalo::VERSION,
            'Accept-Encoding' => '*',
        ];
    }
}
