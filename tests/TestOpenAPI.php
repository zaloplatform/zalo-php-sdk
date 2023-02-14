<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Zalo\Builder\MessageBuilder;
use Zalo\Zalo;
use Zalo\ZaloEndPoint;

class TestOpenAPI extends TestCase
{
    /**
     * @var string
     */
    protected $accessToken = 'put your access token';

    /**
     * @var Zalo
     */
    protected $zalo;

    public function __construct()
    {
        try {
            $config = array(
                'app_id' => 'put_your_app_id',
                'app_secret' => 'put_your_app_secret'
            );

            $this->zalo = new Zalo($config);
        } catch (\Exception $e) {
            print_r('Initial test fail ' . $e->getMessage());
            exit;
        }
    }

    public function testSendTextMessageAPI() {
        try {
            $msgBuilder = new MessageBuilder('text');
            $msgBuilder->withUserId('user_id');
            $msgBuilder->withText('Message Text');

            $actionOpenUrl = $msgBuilder->buildActionOpenURL('https://wwww.google.com'); // build action open link
            $msgBuilder->withButton('Open Link', $actionOpenUrl);

            $actionQueryShow = $msgBuilder->buildActionQueryShow('query_show'); // build action query show
            $msgBuilder->withButton('Query Show', $actionQueryShow);

            $actionQueryHide = $msgBuilder->buildActionQueryHide('query_hide'); // build action query hide
            $msgBuilder->withButton('Query Hide', $actionQueryHide);

            $actionOpenPhone = $msgBuilder->buildActionOpenPhone('0919018791'); // build action open phone
            $msgBuilder->withButton('Open Phone', $actionOpenPhone);

            $actionOpenSMS = $msgBuilder->buildActionOpenSMS('0919018791', 'sms text'); // build action open sms
            $msgBuilder->withButton('Open SMS', $actionOpenSMS);

            $msgText = $msgBuilder->build();

            // send request
            $response = $this -> zalo->post(ZaloEndpoint::API_OA_SEND_MESSAGE, $this->accessToken, $msgText);
            $result = $response->getDecodedBody();
            print_r('testSendTextMessageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testSendImageMessageAPI() {
        try {
            $msgBuilder = new MessageBuilder('media');
            $msgBuilder->withUserId('user_id');
            $msgBuilder->withText('Message Image');
            $msgBuilder->withAttachment('O5DQPI_gKq9iMbDZBET7L6jDHNKk_4XCUKTIOpxvNaTdKr9mAFa24Ia1IJvpfXDPPKzOE3sg4qfbKaCmPBLLNt1P0sHplnH2CWSROtE-4a4_11rXUheJGJe616PzkWX6RLO5Dotd2n4x30ioUxm42MH4MpvzTOWu5MxvKr8');

            $actionOpenUrl = $msgBuilder->buildActionOpenURL('https://wwww.google.com'); // build action open link
            $msgBuilder->withButton('Open Link', $actionOpenUrl);

            $actionQueryShow = $msgBuilder->buildActionQueryShow('query_show'); // build action query show
            $msgBuilder->withButton('Query Show', $actionQueryShow);

            $actionQueryHide = $msgBuilder->buildActionQueryHide('query_hide'); // build action query hide
            $msgBuilder->withButton('Query Hide', $actionQueryHide);

            $actionOpenPhone = $msgBuilder->buildActionOpenPhone('0919018791'); // build action open phone
            $msgBuilder->withButton('Open Phone', $actionOpenPhone);

            $actionOpenSMS = $msgBuilder->buildActionOpenSMS('0919018791', 'sms text'); // build action open sms
            $msgBuilder->withButton('Open SMS', $actionOpenSMS);

            $msgImage = $msgBuilder->build();

            // send request
            $response = $this -> zalo->post(ZaloEndpoint::API_OA_SEND_MESSAGE, $this->accessToken, $msgImage);
            $result = $response->getDecodedBody();
            print_r('testSendImageMessageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testSendMessageListAPI() {
        try {
            $msgBuilder = new MessageBuilder('list');
            $msgBuilder->withUserId('user_id');

            $actionOpenUrl = $msgBuilder->buildActionOpenURL('https://www.google.com');
            $msgBuilder->withElement('Open Link Google', 'https://img.icons8.com/bubbles/2x/google-logo.png', 'Search engine', $actionOpenUrl);

            $actionQueryShow = $msgBuilder->buildActionQueryShow('query_show');
            $msgBuilder->withElement('Query Show', 'https://www.computerhope.com/jargon/q/query.jpg', '', $actionQueryShow);

            $actionQueryHide = $msgBuilder->buildActionQueryHide('query_hide');
            $msgBuilder->withElement('Query Hide', 'https://www.computerhope.com/jargon/q/query.jpg', '', $actionQueryHide);

            $actionOpenPhone = $msgBuilder->buildActionOpenPhone('0919018791');
            $msgBuilder->withElement('Open Phone', 'https://cdn.iconscout.com/icon/premium/png-256-thumb/phone-275-123408.png', '', $actionOpenPhone);

            $actionOpenSMS = $msgBuilder->buildActionOpenSMS('0919018791', 'sms text');
            $msgBuilder->withElement('Open SMS', 'https://cdn0.iconfinder.com/data/icons/new-design/512/42-Chat-512.png', '', $actionOpenSMS);

            $msgList = $msgBuilder->build();
            $response = $this->zalo->post(ZaloEndpoint::API_OA_SEND_MESSAGE, $this->accessToken, $msgList);
            $result = $response->getDecodedBody(); // result
            print_r('testSendMessageListAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testSendGifMessageAPI() {
        try {
            $msgBuilder = new MessageBuilder('media');
            $msgBuilder->withUserId('user_id');
            $msgBuilder->withText('Message Image');
            $msgBuilder->withAttachment('pEq6ALHczYF1w0OpGMFcDT3h42LGOV0ZnkW2CqPbuZN6j5Hz57wiRfcb735QRweWbQKFCL1rgoE8g0SnN7_gESRm73rNU-mspFCNE5PouJ3D_GnjMtV_Dv-lM6WDS_XcbgWFRGHth7EAx5jj4dVn9SI-G2D9AE1jYF1H9GSau7w8xKejNZkcQvtc2db9CUTnqh4LVaWyvdFJl1DxI3RfTT-t17eSE_H_sh5GSau_yIYAj05r7ZkdPzRo0dbzehd2F1LszJS');
            $msgBuilder->withMediaType('gif');
            $msgBuilder->withMediaSize(120, 120);
            $msgImage = $msgBuilder->build();

            $response = $this->zalo->post(ZaloEndpoint::API_OA_SEND_MESSAGE, $this->accessToken, $msgImage);
            $result = $response->getDecodedBody(); // result
            print_r('testSendGifMessageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testSendFileMessageAPI() {
        try {
            $msgBuilder = new MessageBuilder('file');
            $msgBuilder->withUserId('user_id');
            $msgBuilder->withFileToken('PkkPJZzjmrbliDeEQ6h6MYVxrmrkSenF8A3N0MGpcWailOnLPpZDM7lbsG9YRSe8VxEG0MSuqmv_l945R3NM2d6vb0ThDhvQCgUYKo1vW0nwlRO6EdUnNoQWeo5sUUTqA8JM7cXHuNPqeiK81569QLtRcHSWEDWUFBQO7aa-taK6vvn473xS7m2xa1ySCzmUL-wR54mpYaXGxfj0CIgyTJWa8vW6');
            $msgFile = $msgBuilder->build();
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $this->accessToken, $msgFile);
            $result = $response->getDecodedBody(); // result
            print_r('testSendFileMessageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testUploadImageAPI() {
        try {
            $filePath = 'put your file path';
            $data = array('file' => new ZaloFile($filePath));
            $response = $this->zalo->post(ZaloEndpoint::API_OA_UPLOAD_PHOTO, $this->accessToken, $data);
            $result = $response->getDecodedBody(); // result
            print_r('testUploadImageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testUploadGifAPI() {
        try {
            $filePath = 'put your file path';
            $data = array('file' => new ZaloFile($filePath));
            $response = $this->zalo->post(ZaloEndpoint::API_OA_UPLOAD_GIF, $this->accessToken, $data);
            $result = $response->getDecodedBody(); // result
            print_r('testUploadGifAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testUploadPDFAPI() {
        try {
            $filePath = 'put your file path';
            $data = array('file' => new ZaloFile($filePath));
            $response = $this->zalo->post(ZaloEndpoint::API_OA_UPLOAD_FILE, $this->accessToken, $data);
            $result = $response->getDecodedBody(); // result
            print_r('testUploadPDFAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testGetListTagOfOAAPI() {
        try {
            $response = $this->zalo->get(ZaloEndpoint::API_OA_GET_LIST_TAG, $this->accessToken, []);
            $result = $response->getDecodedBody();
            print_r('testGetListTagOfOAAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testRemoveTagOfOAAPI() {
        try {
            $data = array('tag_name' => 'vip');
            $response = $this->zalo->post(ZaloEndpoint::API_OA_REMOVE_TAG, $this->accessToken, $data);
            $result = $response->getDecodedBody();
            print_r('testRemoveTagOfOAAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testRemoveUserFromTagAPI() {
        try {
            $data = array(
                'user_id' => 'user_id',
                'tag_name' => 'tag_name'
            );
            $response = $this->zalo->post(ZaloEndpoint::API_OA_REMOVE_USER_FROM_TAG, $this->accessToken, $data);
            $result = $response->getDecodedBody();
            print_r('testRemoveUserFromTagAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testAddUserToTagAPI() {
        try {
            $data = array(
                'user_id' => 'user_id',
                'tag_name' => 'tag_name'
            );
            $response = $this->zalo->post(ZaloEndpoint::API_OA_TAG_USER, $this->accessToken, $data);
            $result = $response->getDecodedBody();
            print_r('testAddUserToTagAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOAGetListFollowerAPI() {
        try {
            $data = ['data' => json_encode(array(
                'offset' => 0,
                'count' => 10
            ))];
            $response = $this->zalo->get(ZaloEndPoint::API_OA_GET_LIST_FOLLOWER, $this->accessToken, $data);
            $result = $response->getDecodedBody(); // result
            print_r('testOAGetListFollowerAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOAGetUserProfileAPI() {
        try {
            $data = ['data' => json_encode(array(
                'user_id' => 'user_id'
            ))];
            $response = $this->zalo->get(ZaloEndpoint::API_OA_GET_USER_PROFILE, $this->accessToken, $data);
            $result = $response->getDecodedBody(); // result
            print_r('testOAGetUserProfileAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOAGetProfileAPI() {
        try {
            $response = $this->zalo->get(ZaloEndPoint::API_OA_GET_PROFILE, $this->accessToken, []);
            $result = $response->getDecodedBody(); // result
            print_r('testOAGetProfileAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOAGetListRecentChatAPI() {
        try {
            $data = ['data' => json_encode(array(
                'offset' => 0,
                'count' => 10
            ))];
            $response = $this->zalo->get(ZaloEndPoint::API_OA_GET_LIST_RECENT_CHAT, $this->accessToken, $data);
            $result = $response->getDecodedBody(); // result
            print_r('testOAGetListRecentChatAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOAGetConversationAPI() {
        try {
            $data = ['data' => json_encode(array(
                'user_id' => 'user_id',
                'offset' => 0,
                'count' => 10
            ))];
            $response = $this->zalo->get(ZaloEndPoint::API_OA_GET_CONVERSATION, $this->accessToken, $data);
            $result = $response->getDecodedBody(); // result
            print_r('testOAGetConversationAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }
}