<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace Zalo;

use Zalo\Authentication\AccessToken;
use Zalo\Authentication\OAuth2Client;
use Zalo\Authentication\ZaloRedirectLoginHelper;
use Zalo\Authentication\AccessTokenMetadata;
use Zalo\Url\UrlDetectionInterface;
use Zalo\Url\ZaloUrlDetectionHandler;
use Zalo\Url\ZaloUrlManipulator;
use Zalo\HttpClients\HttpClientsFactory;
use Zalo\HttpClients\ZCurl;
use Zalo\HttpClients\ZCurlHttpClient;
use Zalo\HttpClients\ZHttpClientInterface;
use Zalo\HttpClients\ZaloStream;
use Zalo\HttpClients\ZaloStreamHttpClient;
use Zalo\Http\GraphRawResponse;
use Zalo\Http\RequestBodyInterface;
use Zalo\Http\RequestBodyUrlEncoded;
use Zalo\Exceptions\ZaloSDKException;
use Zalo\Exceptions\ZaloAuthenticationException;
use Zalo\Exceptions\ZaloAuthorizationException;
use Zalo\Exceptions\ZaloClientException;
use Zalo\Exceptions\ZaloOtherException;
use Zalo\Exceptions\ZaloResponseException;
use Zalo\Exceptions\ZaloResumableUploadException;
use Zalo\Exceptions\ZaloServerException;
use Zalo\Exceptions\ZaloThrottleException;
use Zalo\ZaloApp;
use Zalo\Zalo;
use Zalo\ZaloClient;
use Zalo\ZaloRequest;
use Zalo\ZaloResponse;


class ZaloSdkTest {

    protected $zalo;
    protected $helper;
    protected $accessTok;

    public function __construct() {

        $app_id = "960183778787479128";
        $app_secret = "coLo667bj6S0LBd57TOU";

        if (isset($_COOKIE['app_id'])) {
            $app_id = $_COOKIE['app_id'];
        }
        if (isset($_COOKIE['secret_key'])) {
            $app_secret = $_COOKIE['secret_key'];
        }

        $this->zalo = new Zalo([
            'app_id' => $app_id,
            'app_secret' => $app_secret,
                //'default_access_token' => '{access-token}', // optional
        ]);
        $this->helper = $this->zalo->getRedirectLoginHelper();
    }

    function ZaloSDKTest() {
        $callBackUrl = "https://smilesplz91.000webhostapp.com/";
        $loginUrl = $this->helper->getLoginUrl($callBackUrl);
        echo '<a href="' . $loginUrl . '" <p> Login with Zalo </p> </a>';
    }

    function ZaloSDKTestAccessToken() {         // method nay gan voi callback link
        try {
            $cookie_name = "access_token";
            if (!isset($_COOKIE[$cookie_name])) {
                $callBackUrl = "https://smilesplz91.000webhostapp.com/";
                
                $oauthCode = isset($_GET['code']) ? $_GET['code'] : "THIS NOT CALLBACK PAGE !!!";
                echo '<br><b>OAUTH CODE HERE :D : <p id="oauth">' . $oauthCode . '</p></b><br>';

                $accessToken = $this->helper->getAccessToken($callBackUrl);
                echo '<br><b>ACESS TOKEN HERE :D : <p id="accesstoken">' . $accessToken . '</p></b><br>';
                echo '<br><b>EXPIRES IN : ';
                if ($accessToken != null) {
                    print_r($accessToken->getExpiresAt());
                }
                echo '</b><br>';
                $this->accessTok = $accessToken;
            } else {
                echo "Cookie '" . $cookie_name . "' is set!<br>";
                echo "Value is: " . $_COOKIE[$cookie_name] . "<br>";
            }
        } catch (ZaloResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }

        echo '<br><a href="/?getme=true">Get me</a><br>';
        echo '<a href="/?getfriends=true">Get friends</a><br>';
        echo '<a href="/?postfeed=true">Post feed</a><br>';
        echo '<a href="/?sendapprequest=true">Send app request</a><br>';
        echo '<a href="/?sendmessage=true">Send message</a><br>';
    }

    function getMe() {
        $cookie_name = "access_token";
        if (!isset($_COOKIE[$cookie_name])) {
            echo "Cookie named '" . $cookie_name . "' is not set!";
        } else {
            $accessToken = $_COOKIE[$cookie_name];
            $response = $this->zalo->get('/me', $accessToken);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        }
    }

    function getFiends() {
        $cookie_name = "access_token";
        if (!isset($_COOKIE[$cookie_name])) {
            echo "Cookie named '" . $cookie_name . "' is not set!";
        } else {
            $accessToken = $_COOKIE[$cookie_name];
            $params = ['offset' => 0, 'limit' => 10, 'fields' => "id, name"];
            $response = $this->zalo->get('/me/friends', $accessToken, $params);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        }
    }

    function postFeed() {
        $cookie_name = "access_token";
        if (!isset($_COOKIE[$cookie_name])) {
            echo "Cookie named '" . $cookie_name . "' is not set!";
        } else {
            $accessToken = $_COOKIE[$cookie_name];
            $params = ['message' => 'Zalo Developers', 'link' => 'https://developers.zalo.me'];
            $response = $this->zalo->post('/me/feed', $params, $accessToken);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        }
    }

    function sendAppRequest() {
        $cookie_name = "access_token";
        if (!isset($_COOKIE[$cookie_name])) {
            echo "Cookie named '" . $cookie_name . "' is not set!";
        } else {
            $accessToken = $_COOKIE[$cookie_name];
            $params = ['message' => 'Test function moi su dung ung dung', 'to' => '6870335006918372741'];
            $response = $this->zalo->post('/apprequests', $params, $accessToken);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        }
    }

    function sendMessage() {
        $cookie_name = "access_token";
        if (!isset($_COOKIE[$cookie_name])) {
            echo "Cookie named '" . $cookie_name . "' is not set!";
        } else {
            $accessToken = $_COOKIE[$cookie_name];
            $params = ['message' => 'Test function gui tin qua OA', 'to' => '6870335006918372741', 'link' => 'https://developers.zalo.me'];
            $response = $this->zalo->post('/me/message', $params, $accessToken);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        }
    }
}
