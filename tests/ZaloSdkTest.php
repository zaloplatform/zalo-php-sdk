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
use Zalo\ZaloConfig;

class ZaloSdkTest {

    protected static $instance;
    protected $zalo;
    protected $helper;
    protected $accessTok;

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

        $app_id = "960183778787479128";
        $app_secret = "coLo667bj6S0LBd57TOU";
        $oa_id = "2491302944280861639";
        $oa_secret = "Tb418kOM4WJLQzwYGqqw";

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
        echo '<a href="/?sendmessageoa=true">Send message OA</a><br>';
        echo '<a href="/?sendimgmessageoa=true">Send image message OA</a><br>';
        echo '<a href="/?sendlinkmessageoa=true">Send link message OA</a><br>';
        echo '<a href="/?sendinteractionmessageoa=true">Send interaction message OA</a><br>';
        echo '<a href="/?getprofileoa=true">Get profile user follow OA</a><br>';
        echo '<a href="/?uploadimageoa=true">Upload image OA</a><br>';
        echo '<a href="/?getmsgstatus=true">Get message status</a><br>';
        echo '<a href="/?sendcsmsg=true">Send customer care message</a><br>';
        echo '<a href="/?sendcsmsgviaphone=true">Send customer care message</a><br>';
        echo '=====================================<br>Offical Account Store API <br>';
        echo '<a href="/?getorderoa=true">Get list order OA</a><br>';
        echo '<a href="/?createproduct=true">Create product OA</a><br>';
        echo '<a href="/?updateproduct=true">Update product OA</a><br>';
        echo '<a href="/?deleteproduct=true">Delete product OA</a><br>';
        echo '<a href="/?getproductinfo=true">Get product info OA</a><br>';
        echo '<a href="/?getlistproduct=true">Get list product OA</a><br>';
        echo '<a href="/?uploadproductimg=true">Upload product image OA</a><br>';
        echo '<a href="/?createcategory=true">Create category OA</a><br>';
        echo '<a href="/?updatecategory=true">Update category OA</a><br>';
        echo '<a href="/?getlistcate=true">Get list category OA</a><br>';
        echo '<a href="/?uploadcateimg=true">Upload category image OA</a><br>';
        echo '<a href="/?updateorder=true">Update order OA</a><br>';
        echo '<a href="/?getorderinfo=true">Get order info OA</a><br>';
    }

    function getMe() {
        $cookie_name = "access_token";
        if (!isset($_COOKIE[$cookie_name])) {
            echo "Cookie named '" . $cookie_name . "' is not set!";
        } else {
            $accessToken = $_COOKIE[$cookie_name];
            $response = $this->zalo->get('/me', $accessToken, [], Zalo::API_TYPE_GRAPH);
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
            $response = $this->zalo->get('/me/friends', $accessToken, $params, Zalo::API_TYPE_GRAPH);
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
            $response = $this->zalo->get('/me/invitable_friends', $accessToken, $params, Zalo::API_TYPE_GRAPH);
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
            $response = $this->zalo->post('/me/feed', $accessToken, $params, Zalo::API_TYPE_GRAPH);
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
            $response = $this->zalo->post('/apprequests', $accessToken, $params, Zalo::API_TYPE_GRAPH);
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
            $response = $this->zalo->post('/me/message', $accessToken, $params, Zalo::API_TYPE_GRAPH);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        }
    }

    function sendOATextMessage() {
        $data = array(
            'uid' => 1785179753369910605,
            'message' => 'Hello!@$#@+%^*+&^**()*&./\/++++'
        );
        $params = ['data' => $data];
        $response = $this->zalo->post('/sendmessage/text', null, $params, Zalo::API_TYPE_OA);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }

    function sendOAImageMessage() {
        try {
            $data = array(
                'uid' => 1785179753369910605,
                'imageid' => '0564c1b560c78999d0d6',
                'message' => 'Hello!@$#@+%^*+&^**()*&./\/++++'
            );
            $params = ['data' => $data];
            $response = $this->zalo->post('/sendmessage/image', null, $params, Zalo::API_TYPE_OA);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    function sendOALinksMessage() {

        $firstLink = array('link' => 'https://developers.zalo.me',
            'linktitle' => 'Zalo for developers',
            'linkdes' => 'Zalo for developer comunity',
            'linkthumb' => 'https://cms.developers.zalo.me/wp-content/uploads/2017/06/Oauth2.jpg');
        $secondLink = array('link' => 'https://developers.zalo.me/docs/',
            'linktitle' => 'Documents for zalo developers',
            'linkdes' => 'Zalo for developer comunity',
            'linkthumb' => 'https://cms.developers.zalo.me/wp-content/uploads/2017/06/Oauth2.jpg');

        $data = array(
            'uid' => 1785179753369910605,
            'links' => [$firstLink, $secondLink],
        );
        $params = ['data' => $data];
        $response = $this->zalo->post('/sendmessage/links', null, $params, Zalo::API_TYPE_OA);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }

    function sendOAInteractionMessage() {
        $firstAction = array('action' => 'oa.query.show',
            'title' => 'Say yes now !',
            'description' => 'Test interaction message',
            'data' => '#yes',
            'href' => 'https://developers.zalo.me',
            'thumb' => 'https://cms.developers.zalo.me/wp-content/uploads/2017/06/Oauth2.jpg');

        $popupForSecondAction = array('title' => 'Popup',
            'desc' => 'Go to link right now ?',
            'ok' => 'Accept',
            'cancel' => 'Decline'
        );
        $secondAction = array('action' => 'oa.query.show',
            'title' => 'Say no now !',
            'description' => 'Test interaction message',
            'data' => '#no',
            'href' => 'https://developers.zalo.me',
            'thumb' => 'https://cms.developers.zalo.me/wp-content/uploads/2017/06/Oauth2.jpg',
            'popup' => $popupForSecondAction);

        $data = array(
            'uid' => 1785179753369910605,
            'actionlist' => [$firstAction, $secondAction],
        );
        $params = ['data' => $data];
        $response = $this->zalo->post('/sendmessage/actionlist', null, $params, Zalo::API_TYPE_OA);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }

    function getProfileUserFollowOA() {
        try {
            $params = ['uid' => 1785179753369910605];
            $response = $this->zalo->get('/getprofile', null, $params, Zalo::API_TYPE_OA);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    function uploadImageOA($filePath) {
        try {
            $params = ['file' => new ZaloFile($filePath)];
            $response = $this->zalo->post('/upload/image', null, $params, Zalo::API_TYPE_OA);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }
    
    function getMessageStatus() {
        try {
            $params = ['msgid' => "7837164f5652040c5d43"];
            $response = $this->zalo->get('/getmessagestatus', null, $params, Zalo::API_TYPE_OA);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }
    
    function sendCustomerCareMessage() {
        try {
            $templateData = array(
                'username' => 'linhndh',
                'invitename' => 'linh'
            );
            $data = array(
                'uid' => 1785179753369910605,
                'templateid' => 'fafc11142d51c40f9d40',
                'templatedata' => $templateData
            );
            $params = ['data' => $data];
            $response = $this->zalo->post('/sendmessage/cs', null, $params, Zalo::API_TYPE_OA);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }
    
    function sendCustomerCareMessageViaPhoneNumber() {
        try {
            $templateData = array(
                'username' => 'linhndh',
                'invitename' => 'linh'
            );
            $data = array(
                'phone' => 84919018791,
                'templateid' => 'fafc11142d51c40f9d40',
                'templatedata' => $templateData
            );
            $params = ['data' => $data];
            $response = $this->zalo->post('/sendmessage/phone/cs', null, $params, Zalo::API_TYPE_OA);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }
    
    function getOrderOA() {
        $data = array(
            'offset' => 0,
            'count' => 10,
            'filter' => 0
        );
        $params = ['data' => $data];
        $response = $this->zalo->get('/store/order/getorderofoa', null, $params, Zalo::API_TYPE_OA);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }
    
    function createProduct() {
        $cate = array('cateid' => '38a81ced22a8cbf692b9');
        $cates = [$cate];
        $photo = array('id' => 'b835603364748d2ad465');
        $photos = [$photo];
        $data = array(
//            'cateids' => $cates,
            'name' => 'Test create product',
            'desc' => 'create product',
            'code' => '1010',
            'price' => 15000,
            'photos' => $photos,
            'display' => 'show', // show | hide
            'payment' => 2 // 2 - enable | 3 - disable
        );
        $params = ['data' => $data];
        $response = $this->zalo->post('/store/product/create', null, $params, Zalo::API_TYPE_OA);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }
    
    function updateProduct() {
        $cate = array('cateid' => '38a81ced22a8cbf692b9');
        $cates = [$cate];
        $photo = array('id' => 'b835603364748d2ad465');
        $photos = [$photo];
        $productUpdate = array(
            'cateids' => $cates,
            'name' => 'Test update product',
            'desc' => '',
            'code' => '1010',
            'price' => 15000,
            'photos' => $photos,
            'display' => 'show', // show | hide
            'payment' => 2 // 2 - enable | 3 - disable
        );
        
        $data = array(
            'productid' => '2fc93b701235fb6ba224',
            'product' => $productUpdate
        );
        $params = ['data' => $data];
        $response = $this->zalo->post('/store/product/update', null, $params, Zalo::API_TYPE_OA);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }
    
    function deleteProduct() {
        $params = ['productid' => ''];
        $response = $this->zalo->post('/store/product/remove', null, $params, Zalo::API_TYPE_OA);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }
    
    function getProductInfo() {
        $data = array(
            'productid' => '2fc93b701235fb6ba224'
        );
        $params = ['data' => $data];
        $response = $this->zalo->get('/store/product/getproduct', null, $params, Zalo::API_TYPE_OA);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }
    
    function getListProduct() {
        $data = array(
            'offset' => '0',
            'count' => '10'
        );
        $params = ['data' => $data];
        $response = $this->zalo->get('/store/product/getproductofoa', null, $params, Zalo::API_TYPE_OA);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }
    
    function uploadProductImage($filePath) {
        try {
            $params = ['file' => new ZaloFile($filePath)];
            $response = $this->zalo->post('/store/upload/productphoto', null, $params, Zalo::API_TYPE_OA);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }
    
    function createCategory() {
        $data = array(
            'name' => 'Test category',
            'desc' => 'This is sample category',
            'photo' => '1aca13c31784fedaa795',
            'status' => 0 // 0 - show | 1 - hide
        );
        $params = ['data' => $data];
        $response = $this->zalo->post('/store/category/create', null, $params, Zalo::API_TYPE_OA);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }
    
    function updateCategory() {
        $categoryUpdate = array(
            'name' => 'Test update category',
            'desc' => 'This is sample category',
            'photo' => '',
            'status' => 1 // 0 - show | 1 - hide
        );
        $data = array(
            'categoryid' => '38a81ced22a8cbf692b9',
            'category' => $categoryUpdate
        );
        $params = ['data' => $data];
        $response = $this->zalo->post('/store/category/update', null, $params, Zalo::API_TYPE_OA);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }
    
    function getListCategory() {
        $data = array(
            'offset' => '0',
            'count' => '10'
        );
        $params = ['data' => $data];
        $response = $this->zalo->get('/store/category/getcategoryofoa', null, $params, Zalo::API_TYPE_OA);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }
    
    function uploadCategoryImage($filePath) {
        try {
            $params = ['file' => new ZaloFile($filePath)];
            $response = $this->zalo->post('/store/upload/categoryphoto', null, $params, Zalo::API_TYPE_OA);
            echo '<br><br>';
            print_r($response->getDecodedBody());
            echo '<br><br>';
        } catch (ZaloSDKException $e) {
            // When validation fails or other local issues
            echo 'Zalo SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }
    
    function updateOrder() {
        $data = array(
            'orderid' => '9541954bac0e45501c1f',
            'status' => 2,
            'reason' => 'test update order',
            'cancelReason' => 'nothing'
        );
        $params = ['data' => $data];
        $response = $this->zalo->post('/store/order/update', null, $params, Zalo::API_TYPE_OA);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }
    
    function getOrderInfo() {
        $params = ['orderid' => '9541954bac0e45501c1f'];
        $response = $this->zalo->get('/store/order/getorder', null, $params, Zalo::API_TYPE_OA);
        echo '<br><br>';
        print_r($response->getDecodedBody());
        echo '<br><br>';
    }
}
