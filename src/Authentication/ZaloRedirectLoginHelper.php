<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo\Authentication;

use Zalo\Authentication\AccessToken;
use Zalo\Authentication\OAuth2Client;
use Zalo\Exceptions\ZaloSDKException;
use Zalo\Url\ZaloUrlDetectionHandler;
use Zalo\Url\ZaloUrlManipulator;
use Zalo\Url\UrlDetectionInterface;


/**
 * Class ZaloRedirectLoginHelper
 *
 * @package Zalo
 */
class ZaloRedirectLoginHelper
{
    /**
     * @const int The length of CSRF string to validate the login link.
     */
    const CSRF_LENGTH = 32;

    /**
     * @var OAuth2Client The OAuth 2.0 client service.
     */
    protected $oAuth2Client;

    /**
     * @var UrlDetectionInterface The URL detection handler.
     */
    protected $urlDetectionHandler;

    /**
     * @param OAuth2Client                              $oAuth2Client          The OAuth 2.0 client service.
     * @param PersistentDataInterface|null              $persistentDataHandler The persistent data handler.
     * @param UrlDetectionInterface|null                $urlHandler            The URL detection handler.
     * @param PseudoRandomStringGeneratorInterface|null $prsg                  The cryptographically secure pseudo-random string generator.
     */
    public function __construct(OAuth2Client $oAuth2Client, UrlDetectionInterface $urlHandler = null)
    {
        $this->oAuth2Client = $oAuth2Client;
        $this->urlDetectionHandler = $urlHandler ?: new ZaloUrlDetectionHandler();
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
     * Stores CSRF state and returns a URL to which the user should be sent to in order to continue the login process with Zalo.
     *
     * @param string $redirectUrl The URL Zalo should redirect users to after login.
     * @param array  $scope       List of permissions to request during login.
     * @param array  $params      An array of parameters to generate URL.
     * @param string $separator   The separator to use in http_build_query().
     *
     * @return string
     */
    private function makeUrl($redirectUrl, array $params = [], $separator = '&')
    {
        return $this->oAuth2Client->getAuthorizationUrl($redirectUrl, $params, $separator);
    }
    
    private function makeUrlByPage($redirectUrl, array $params = [], $separator = '&')
    {
        return $this->oAuth2Client->getAuthorizationUrlByPage($redirectUrl, $params, $separator);
    }

    /**
     * Returns the URL to send the user in order to login to Zalo.
     *
     * @param string $redirectUrl The URL Zalo should redirect users to after login.
     * @param array  $scope       List of permissions to request during login.
     * @param string $separator   The separator to use in http_build_query().
     *
     * @return string
     */
    public function getLoginUrl($redirectUrl, $separator = '&')
    {
        return $this->makeUrl($redirectUrl, [], $separator);
    }
    
    public function getLoginUrlByPage($redirectUrl, $separator = '&')
    {
        return $this->makeUrlByPage($redirectUrl, [], $separator);
    }

    /**
     * Returns the URL to send the user in order to login to Zalo with permission(s) to be re-asked.
     *
     * @param string $redirectUrl The URL Zalo should redirect users to after login.
     * @param array  $scope       List of permissions to request during login.
     * @param string $separator   The separator to use in http_build_query().
     *
     * @return string
     */
    public function getReRequestUrl($redirectUrl, array $scope = [], $separator = '&')
    {
        $params = ['auth_type' => 'rerequest'];

        return $this->makeUrl($redirectUrl, $scope, $params, $separator);
    }

    /**
     * Returns the URL to send the user in order to login to Zalo with user to be re-authenticated.
     *
     * @param string $redirectUrl The URL Zalo should redirect users to after login.
     * @param array  $scope       List of permissions to request during login.
     * @param string $separator   The separator to use in http_build_query().
     *
     * @return string
     */
    public function getReAuthenticationUrl($redirectUrl, array $scope = [], $separator = '&')
    {
        $params = ['auth_type' => 'reauthenticate'];

        return $this->makeUrl($redirectUrl, $scope, $params, $separator);
    }

    /**
     * Takes a valid code from a login redirect, and returns an AccessToken entity.
     *
     * @param string|null $redirectUrl The redirect URL.
     *
     * @return AccessToken|null
     *
     * @throws ZaloSDKException
     */
    public function getAccessToken($redirectUrl = null)
    {
        if (!$code = $this->getCode()) {
            return null;
        }
        
        $redirectUrl = $redirectUrl ?: $this->urlDetectionHandler->getCurrentUrl();
        // At minimum we need to remove the state param
        $redirectUrl = ZaloUrlManipulator::removeParamsFromUrl($redirectUrl, ['state']);

        return $this->oAuth2Client->getAccessTokenFromCode($code, $redirectUrl);
    }

    /**
     * Return the code.
     *
     * @return string|null
     */
    protected function getCode()
    {
        return $this->getInput('code');
    }

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode()
    {
        return $this->getInput('error_code');
    }

    /**
     * Returns the error.
     *
     * @return string|null
     */
    public function getError()
    {
        return $this->getInput('error');
    }

    /**
     * Returns a value from a GET param.
     *
     * @param string $key
     *
     * @return string|null
     */
    private function getInput($key)
    {
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }
}
