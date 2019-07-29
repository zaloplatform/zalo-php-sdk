<?php

namespace Zalo;

use Zalo\Authentication\AccessToken;
use Zalo\Authentication\OAuth2Client;
use Zalo\Authentication\ZaloRedirectLoginHelper;
use Zalo\Authentication\AccessTokenMetadata;
use Zalo\Url\UrlDetectionInterface;
use Zalo\Url\ZaloUrlDetectionHandler;
use Zalo\Url\ZaloUrlManipulator;
use Zalo\HttpClients\HttpClientsFactory;
use Zalo\HttpClients\ZaloCurl;
use Zalo\HttpClients\ZaloCurlHttpClient;
use Zalo\HttpClients\ZaloHttpClientInterface;
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
use Zalo\FileUpload\ZaloFile;
use Zalo\ZaloApp;
use Zalo\Zalo;
use Zalo\ZaloClient;
use Zalo\ZaloRequest;
use Zalo\ZaloResponse;
use Zalo\Builder\MessageBuilder;
use Zalo\ZaloEndPoint;

class ZaloSdkTest {

    protected static $instance;
    protected $zalo;
    protected $helper;

    /**
     * Get a singleton instance of the class
     *
     * @return self
     * @codeCoverageIgnore
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct() {

        $app_id = "3651372925690900611";
        $app_secret = "rbZ5wQ2tVUh7Y3y6Kxqe";
        $oa_id = "3886132808798808645";
        $oa_secret = "O44TuGPXY64JjCXDmDjQ";

        if (isset($_COOKIE['app_id'])) {
            $app_id = $_COOKIE['app_id'];
        }
        if (isset($_COOKIE['secret_key'])) {
            $app_secret = $_COOKIE['secret_key'];
        }

        $this->zalo = new Zalo(ZaloConfig::getInstance()->getConfig());
        $this->helper = $this->zalo->getRedirectLoginHelper();
    }

    function renderLoginUrl() {
        $callBackUrl = "https://smilesplz91.000webhostapp.com/";
        if (isset($_COOKIE['call_back_url'])) {
            $callBackUrl = $_COOKIE['call_back_url'];
        }
        $loginUrl = $this->helper->getLoginUrl($callBackUrl);
        echo '<a href="' . $loginUrl . '" <p> Login with Zalo </p> </a><br>';
        $loginUrlByPage = $this->helper->getLoginUrlByPage($callBackUrl);
        echo '<a href="' . $loginUrlByPage . '" <p> Login by page with Zalo </p> </a><br>';
        $key = 'code';
        if (isset($_GET[$key])) {
            echo '<br> OAuthCode : ';
            echo '<p id="oauth">' . $_GET[$key] . '</p>';
            echo '<br>';
        }
    }

    function getAccessTokenByApp() {
        try {
            $cookie_name = "access_token";
            if (!isset($_COOKIE[$cookie_name])) {
                $callBackUrl = "https://smilesplz91.000webhostapp.com/";
                if (isset($_COOKIE['call_back_url'])) {
                    $callBackUrl = $_COOKIE['call_back_url'];
                }
                $accessToken = $this->helper->getAccessToken($callBackUrl);
                echo '<br><b>ACESS TOKEN HERE :D : <p id="accesstoken">' . $accessToken . '</p></b><br>';
                echo '<br><b>EXPIRES IN : ';
                if ($accessToken != null) {
                    print_r($accessToken->getExpiresAt());
                }
                echo '</b><br>';
                $accessToken = $accessToken;
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
    }

    function renderListAction() {
        echo '<br>Social API <br>';
        echo '<a href="/?getme=true">Get me</a><br>';
        echo '<a href="/?getfriends=true">Get friends</a><br>';
        echo '<a href="/?getinvitable=true">Get invitable friends</a><br>';
        echo '<a href="/?postfeed=true">Post feed</a><br>';
        echo '<a href="/?sendapprequest=true">Send app request</a><br>';
        echo '<a href="/?sendmessage=true">Send message</a><br>';
        echo '=====================================<br>Offical Account API <br>';
        
        echo '<a href="/?getlisttag=true">Get list tag</a><br>';
        echo '<a href="/?rmtag=true">Remove tag</a><br>';
        echo '<a href="/?rmusrfrtag=true">Remove user from tag</a><br>';
        echo '<a href="/?taguser=true">Tag user</a><br>';

        echo '<a href="/?sendfollowmsg=true">Send message follow OA</a><br>';
        echo '<a href="/?sendoatext=true">Send text message OA</a><br>';
        echo '<a href="/?sendoaimage=true">Send image message OA</a><br>';
        echo '<a href="/?sendoalist=true">Send list message OA</a><br>';
        echo '<a href="/?sendoagif=true">Send gif message OA</a><br>';
        echo '<a href="/?sendfile=true">Send file message OA</a><br>';

        echo '<a href="/?uploadimageoa=true">Upload image OA</a><br>';
        echo '<a href="/?uploadgifoa=true">Upload gif OA</a><br>';
        echo '<a href="/?uploadfile=true">Upload file OA</a><br>';

        echo '<a href="/?getprofileuseroa=true">Get profile user follow OA</a><br>';
        echo '<a href="/?getprofileoa=true">Get profile OA </a><br>';
        echo '<a href="/?getfollowers=true">Get followers</a><br>';
        echo '<a href="/?listrecentchat=true">Get list recent chat</a><br>';
        echo '<a href="/?conversation=true">Get conversation</a><br>';
    }

    function getMe() {
        $cookie_name = "access_token";
        if (!isset($_COOKIE[$cookie_name])) {
            echo "Cookie named '" . $cookie_name . "' is not set!";
        } else {
            $accessToken = $_COOKIE[$cookie_name];
            $response = $this->zalo->get(ZaloEndPoint::API_GRAPH_ME, $accessToken, ['fields' => 'id,name,birthday,gender,picture']);
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
            $response = $this->zalo->get(ZaloEndPoint::API_GRAPH_FRIENDS, $accessToken, $params);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        }
    }

    function getInvitableFiends() {
        $cookie_name = "access_token";
        if (!isset($_COOKIE[$cookie_name])) {
            echo "Cookie named '" . $cookie_name . "' is not set!";
        } else {
            $accessToken = $_COOKIE[$cookie_name];
            $params = ['offset' => 0, 'limit' => 10, 'fields' => "id, name"];
            $response = $this->zalo->get(ZaloEndPoint::API_GRAPH_INVITABLE_FRIENDS, $accessToken, $params);
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
            $response = $this->zalo->post(ZaloEndPoint::API_GRAPH_POST_FEED, $accessToken, $params);
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
            $response = $this->zalo->post(ZaloEndPoint::API_GRAPH_APP_REQUESTS, $accessToken, $params);
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
            $response = $this->zalo->post(ZaloEndPoint::API_GRAPH_MESSAGE, $accessToken, $params);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        }
    }

    function sendFollowRequestMessage($accessToken) {
        $msgBuilder = new MessageBuilder('template');
        $msgBuilder->withPhoneNumber('0919018791');
        $templateData = array(
            'customer_name' => 'Linh Nguyen',
            'customer_address' => '997 cach mang thang 8',
            'customer_code' => 'ABC000111',
            'date' => '24/07/2019',
            'power_used' => '1982 kWh',
            'charge' => '5.000.000 vnd',
            'payment_date' => '01/08/2019'
        );
        $msgBuilder->withTemplate('87efbc018044691a3055', $templateData);
        $msgInvite = $msgBuilder->build();
        $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgInvite);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }

    function getListTag($accessToken) {
        // goi api
        $response = $this->zalo->get(ZaloEndPoint::API_OA_GET_LIST_TAG, $accessToken, []);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }

    function deleteTag($accessToken) {
        $data = array('tag_name' => 'vip_user');

        // goi api
        $response = $this->zalo->post(ZaloEndPoint::API_OA_REMOVE_TAG, $accessToken, $data);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }

    function removeUserFromTag($accessToken) {
        $data = array(
            'user_id' => '494021888309207992',
            'tag_name' => 'vip_user'
        );

        // goi api
        $response = $this->zalo->post(ZaloEndPoint::API_OA_REMOVE_USER_FROM_TAG, $accessToken, $data);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }

    function tagUser($accessToken) {
        $data = array(
                'user_id' => '494021888309207992',
                'tag_name' => 'vip_user'
        );

        // goi api
        $response = $this->zalo->post(ZaloEndPoint::API_OA_TAG_USER, $accessToken, $data);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }

    function sendOATextMessage($accessToken) {
            $msgBuilder = new MessageBuilder('text');
            $msgBuilder->withUserId('494021888309207992');
            $msgBuilder->withText('Message Text');
            $msgText = $msgBuilder->build();

            // goi api
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgText);
            echo '<br><br>';
            // lay du lieu tra ve
            print_r($response->getDecodedBody());
            echo '<br><br>';

            $msgBuilder->withText('Message Text with Buttons');
            $actionOpenUrl = $msgBuilder->buildActionOpenURL('https://wwww.google.com');
            $msgBuilder->withButton('Open Link', $actionOpenUrl);

            $actionQueryShow = $msgBuilder->buildActionQueryShow('query_show');
            $msgBuilder->withButton('Query Show', $actionQueryShow);

            $actionQueryHide = $msgBuilder->buildActionQueryHide('query_hide');
            $msgBuilder->withButton('Query Hide', $actionQueryHide);

            $actionOpenPhone = $msgBuilder->buildActionOpenPhone('0919018791');
            $msgBuilder->withButton('Open Phone', $actionOpenPhone);

            $actionOpenSMS = $msgBuilder->buildActionOpenSMS('0919018791', 'sms text');
            $msgBuilder->withButton('Open SMS', $actionOpenSMS);

            $msgTextWithButton = $msgBuilder->build();
            // goi api
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgTextWithButton);
            echo '<br><br>';
            // lay du lieu tra ve
            print_r($response->getDecodedBody());
            echo '<br><br>';
    }

    function sendOAImageMessage($accessToken) {
        try {
            $msgBuilder = new MessageBuilder('media');
            $msgBuilder->withUserId('494021888309207992');
            $msgBuilder->withText('Message Image');
            $msgBuilder->withAttachment('cb2ab1696b688236db79');
            $msgImage = $msgBuilder->build();

            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgImage);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';

            $msgBuilder->withText('Message Image with Buttons');
            $actionOpenUrl = $msgBuilder->buildActionOpenURL('https://wwww.google.com');
            $msgBuilder->withButton('Open Link', $actionOpenUrl);

            $actionQueryShow = $msgBuilder->buildActionQueryShow('query_show');
            $msgBuilder->withButton('Query Show', $actionQueryShow);

            $actionQueryHide = $msgBuilder->buildActionQueryHide('query_hide');
            $msgBuilder->withButton('Query Hide', $actionQueryHide);

            $actionOpenPhone = $msgBuilder->buildActionOpenPhone('0919018791');
            $msgBuilder->withButton('Open Phone', $actionOpenPhone);

            $actionOpenSMS = $msgBuilder->buildActionOpenSMS('0919018791', 'sms text');
            $msgBuilder->withButton('Open SMS', $actionOpenSMS);

            $msgImage = $msgBuilder->build();

            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgImage);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    function sendOALinksMessage($accessToken) {
        $msgBuilder = new MessageBuilder('list');
        $msgBuilder->withUserId('494021888309207992');

        $actionOpenUrl = $msgBuilder->buildActionOpenURL('https://www.google.com');
        $msgBuilder->withElement('Open Link Google', 'https://img.icons8.com/bubbles/2x/google-logo.png', 'The best search engine!', $actionOpenUrl);

        $actionQueryShow = $msgBuilder->buildActionQueryShow('query_show');
        $msgBuilder->withElement('Query Show', 'https://www.computerhope.com/jargon/q/query.jpg', '', $actionQueryShow);

        $actionQueryHide = $msgBuilder->buildActionQueryHide('query_hide');
        $msgBuilder->withElement('Query Hide', 'https://www.computerhope.com/jargon/q/query.jpg', '', $actionQueryHide);

        $actionOpenPhone = $msgBuilder->buildActionOpenPhone('0919018791');
        $msgBuilder->withElement('Open Phone', 'https://cdn.iconscout.com/icon/premium/png-256-thumb/phone-275-123408.png', '', $actionOpenPhone);

        $actionOpenSMS = $msgBuilder->buildActionOpenSMS('0919018791', 'sms text');
        $msgBuilder->withElement('Open SMS', 'https://cdn0.iconfinder.com/data/icons/new-design/512/42-Chat-512.png', '', $actionOpenSMS);
        
        
        $msgList = $msgBuilder->build();

        $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgList);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';

        $actionOpenUrl = $msgBuilder->buildActionOpenURL('https://wwww.google.com');
        $msgBuilder->withButton('Open Link', $actionOpenUrl);

        $actionQueryShow = $msgBuilder->buildActionQueryShow('query_show');
        $msgBuilder->withButton('Query Show', $actionQueryShow);

        $actionQueryHide = $msgBuilder->buildActionQueryHide('query_hide');
        $msgBuilder->withButton('Query Hide', $actionQueryHide);

        $actionOpenPhone = $msgBuilder->buildActionOpenPhone('0919018791');
        $msgBuilder->withButton('Open Phone', $actionOpenPhone);

        $actionOpenSMS = $msgBuilder->buildActionOpenSMS('0919018791', 'sms text');
        $msgBuilder->withButton('Open SMS', $actionOpenSMS);
        $msgList = $msgBuilder->build();

        $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgList);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }

    function sendOAGifMessage($accessToken) {
            $msgBuilder = new MessageBuilder('media');
            $msgBuilder->withUserId('494021888309207992');
            $msgBuilder->withText('Message Image');
            $msgBuilder->withAttachment('PWhbF13YGGi9VTkG/vHcTyoskajfj5Ve/EGsTK80XYo=');
            $msgBuilder->withMediaType('gif');
            $msgBuilder->withMediaSize(120, 120);
            $msgImage = $msgBuilder->build();

            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgImage);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';

            $msgBuilder->withText('Message Image with Buttons');
            $actionOpenUrl = $msgBuilder->buildActionOpenURL('https://wwww.google.com');
            $msgBuilder->withButton('Open Link', $actionOpenUrl);

            $actionQueryShow = $msgBuilder->buildActionQueryShow('query_show');
            $msgBuilder->withButton('Query Show', $actionQueryShow);

            $actionQueryHide = $msgBuilder->buildActionQueryHide('query_hide');
            $msgBuilder->withButton('Query Hide', $actionQueryHide);

            $actionOpenPhone = $msgBuilder->buildActionOpenPhone('0919018791');
            $msgBuilder->withButton('Open Phone', $actionOpenPhone);

            $actionOpenSMS = $msgBuilder->buildActionOpenSMS('0919018791', 'sms text');
            $msgBuilder->withButton('Open SMS', $actionOpenSMS);

            $msgImage = $msgBuilder->build();

            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgImage);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
    }

    function sendFileMessage($accessToken) {
        $msgBuilder = new MessageBuilder('file');
        $msgBuilder->withUserId('494021888309207992');
        $msgBuilder->withFileToken('QgcYIRd2pbPZyk49xiNBLIkvx0cvnjLTTxdW0_ZQaWSvxkG3_8_5KJgxiGIhY9TLRlRcLloLaLDiyk5Q_PEQKsxquLFx-fifQkUQBhZTdKT0_vmWjFURNaUBcGEuu-u0B8lo0xk_q5P-t_O2wB-30thSer7Zkj1PUe-bMU-xXL4rDJcWKKCmeeoL2G');
        $msgFile = $msgBuilder->build();

        $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgFile);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';

        $msgBuilder->withText('Message Image with Buttons');
        $actionOpenUrl = $msgBuilder->buildActionOpenURL('https://wwww.google.com');
        $msgBuilder->withButton('Open Link', $actionOpenUrl);

        $actionQueryShow = $msgBuilder->buildActionQueryShow('query_show');
        $msgBuilder->withButton('Query Show', $actionQueryShow);

        $actionQueryHide = $msgBuilder->buildActionQueryHide('query_hide');
        $msgBuilder->withButton('Query Hide', $actionQueryHide);

        $actionOpenPhone = $msgBuilder->buildActionOpenPhone('0919018791');
        $msgBuilder->withButton('Open Phone', $actionOpenPhone);

        $actionOpenSMS = $msgBuilder->buildActionOpenSMS('0919018791', 'sms text');
        $msgBuilder->withButton('Open SMS', $actionOpenSMS);

        $msgImage = $msgBuilder->build();

        $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgImage);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }

    function uploadImageOA($filePath, $accessToken) {
        try {
            $data = array('file' => new ZaloFile($filePath));
            $response = $this->zalo->post(ZaloEndPoint::API_OA_UPLOAD_PHOTO, $accessToken, $data);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    function uploadGifOA($filePath, $accessToken) {
        try {
            $data = array('file' => new ZaloFile($filePath));
            $response = $this->zalo->post(ZaloEndPoint::API_OA_UPLOAD_GIF, $accessToken, $data);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    function uploadFile($filePath, $accessToken) {
        try {
            $data = array('file' => new ZaloFile($filePath));
            $response = $this->zalo->post(ZaloEndPoint::API_OA_UPLOAD_FILE, $accessToken, $data);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    function getProfileUserFollowOA($accessToken) {
        try {
            $data = ['data' => json_encode(array(
                'user_id' => '494021888309207992'
            ))];
            $response = $this->zalo->get(ZaloEndPoint::API_OA_GET_USER_PROFILE, $accessToken, $data);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    function getProfileOA($accessToken) {
        try {
            $response = $this->zalo->get(ZaloEndPoint::API_OA_GET_PROFILE, $accessToken, []);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    function getFollowers($accessToken) {
        try {
            $data = ['data' => json_encode(array(
                'offset' => '0',
                'count' => '10'
            ))];
            $response = $this->zalo->get(ZaloEndPoint::API_OA_GET_LIST_FOLLOWER, $accessToken, $data);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    function getListRecentChat($accessToken) {
        try {
            $data = ['data' => json_encode(array(
                'offset' => '0',
                'count' => '10'
            ))];
            $response = $this->zalo->get(ZaloEndPoint::API_OA_GET_LIST_RECENT_CHAT, $accessToken, $data);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    function getConversation($accessToken) {
        try {
            $data = ['data' => json_encode(array(
                'user_id' => 494021888309207992,
                'offset' => 0,
                'count' => 10
            ))];
            $response = $this->zalo->get(ZaloEndPoint::API_OA_GET_CONVERSATION, $accessToken, $data);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }
}

