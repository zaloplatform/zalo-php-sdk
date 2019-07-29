<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo\Authentication;

use Zalo\Authentication\AccessToken;
use Zalo\Authentication\AccessTokenMetadata;
use Zalo\Zalo;
use Zalo\ZaloApp;
use Zalo\ZaloRequest;
use Zalo\ZaloResponse;
use Zalo\ZaloClient;
use Zalo\Exceptions\ZaloResponseException;
use Zalo\Exceptions\ZaloSDKException;

/**
 * Class OAuth2Client
 *
 * @package Zalo
 */
class OAuth2Client
{
    /**
     * @const string The base authorization URL.
     */
    const BASE_AUTHORIZATION_URL = 'https://oauth.zaloapp.com';

    /**
     * @const string Default OAuth API version for requests.
     */
    const DEFAULT_OAUTH_VERSION = 'v3';

    /**
     * The ZaloApp entity.
     *
     * @var ZaloApp
     */
    protected $app;

    /**
     * The Zalo client.
     *
     * @var ZaloClient
     */
    protected $client;

    /**
     * The last request sent to Graph.
     *
     * @var ZaloRequest|null
     */
    protected $lastRequest;

    /**
     * @param ZaloApp    $app
     * @param ZaloClient $client
     * @param string|null    $graphVersion The version of the Graph API to use.
     */
    public function __construct(ZaloApp $app, ZaloClient $client)
    {
        $this->app = $app;
        $this->client = $client;
    }

    /**
     * Returns the last ZaloRequest that was sent.
     * Useful for debugging and testing.
     *
     * @return ZaloRequest|null
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * Generates an authorization URL to begin the process of authenticating a user.
     *
     * @param string $redirectUrl The callback URL to redirect to.
     * @param string $state       The CSPRNG-generated CSRF value.
     * @param array  $scope       An array of permissions to request.
     * @param array  $params      An array of parameters to generate URL.
     * @param string $separator   The separator to use in http_build_query().
     *
     * @return string
     */
    public function getAuthorizationUrl($redirectUrl, array $params = [], $separator = '&')
    {
        $params += [
            'app_id' => $this->app->getId(),
            'redirect_uri' => $redirectUrl,
        ];

        return static::BASE_AUTHORIZATION_URL . '/' . static::DEFAULT_OAUTH_VERSION . '/auth?' . http_build_query($params, null, $separator);
    }
    
    public function getAuthorizationUrlByPage($redirectUrl, array $params = [], $separator = '&')
    {
        $params += [
            'app_id' => $this->app->getId(),
            'redirect_uri' => $redirectUrl,
        ];
        return static::BASE_AUTHORIZATION_URL . '/' . static::DEFAULT_OAUTH_VERSION . '/oa/permission?'. http_build_query($params, null, $separator);
    }

    /**
     * Get a valid access token from a code.
     *
     * @param string $code
     * @param string $redirectUri
     *
     * @return AccessToken
     *
     * @throws ZaloSDKException
     */
    public function getAccessTokenFromCode($code, $redirectUri = '')
    {
        $params = [
            'code' => $code,
            'redirect_uri' => $redirectUri,
        ];

        return $this->requestAnAccessToken($params);
    }

    /**
     * Send a request to the OAuth endpoint.
     *
     * @param array $params
     *
     * @return AccessToken
     *
     * @throws ZaloSDKException
     */
    protected function requestAnAccessToken(array $params)
    {
        $response = $this->sendRequestWithClientParams('/access_token', $params);
        $data = $response->getDecodedBody();

        if (!isset($data['access_token'])) {
            throw new ZaloSDKException('Access token was not returned from Graph.', 401);
        }

        // Graph returns two different key names for expiration time
        // on the same endpoint. Doh! :/
        $expiresAt = 0;
        if (isset($data['expires'])) {
            // For exchanging a short lived token with a long lived token.
            // The expiration time in seconds will be returned as "expires".
            $expiresAt = time() + $data['expires'];
        } elseif (isset($data['expires_in'])) {
            // For exchanging a code for a short lived access token.
            // The expiration time in seconds will be returned as "expires_in".
            // See: https://developers.zalo.me/docs/
            $expiresAt = time() + $data['expires_in'];
        }

        return new AccessToken($data['access_token'], $expiresAt);
    }

    /**
     * Send a request to Graph with an app access token.
     *
     * @param string                  $endpoint
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     *
     * @return ZaloResponse
     *
     * @throws ZaloResponseException
     */
    protected function sendRequestWithClientParams($endpoint, array $params, $accessToken = null)
    {
        $params += $this->getClientParams();

        $accessToken = $accessToken ?: $this->app->getAccessToken();
        $url = static::BASE_AUTHORIZATION_URL . '/' . static::DEFAULT_OAUTH_VERSION . $endpoint;
        $this->lastRequest = new ZaloRequest(
            $accessToken,
            'GET',
            $url,
            $params,
            null
        );

        return $this->client->sendRequest($this->lastRequest);
    }

    /**
     * Returns the client_* params for OAuth requests.
     *
     * @return array
     */
    protected function getClientParams()
    {
        return [
            'app_id' => $this->app->getId(),
            'app_secret' => $this->app->getSecret(),
        ];
    }
}
