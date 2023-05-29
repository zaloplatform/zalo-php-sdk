<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Zalo\Builder\MessageBuilder;
use Zalo\Common\TransactionTemplateType;
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
                'app_id' => '1644840091349742683',
                'app_secret' => 'Jr6TXIq7TT3R6D3xpwu0'
            );

            $this->zalo = new Zalo($config);
        } catch (\Exception $e) {
            print_r('Initial test fail ' . $e->getMessage());
            exit;
        }
    }

    public function testSendTextMessageAPI()
    {
        try {
            $msgBuilder = new MessageBuilder('text');
            $msgBuilder->withUserId('user_id');
            $msgBuilder->withText('Message Text');

            $msgText = $msgBuilder->build();

            // send request
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $this->accessToken, $msgText);
            $result = $response->getDecodedBody();
            print_r('testSendTextMessageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testSendImageMessageAPI()
    {
        try {
            $msgBuilder = new MessageBuilder('media');
            $msgBuilder->withUserId('user_id');
            $msgBuilder->withText('Message Image');
//            $msgBuilder->withAttachment('O5DQPI_gKq9iMbDZBET7L6jDHNKk_4XCUKTIOpxvNaTdKr9mAFa24Ia1IJvpfXDPPKzOE3sg4qfbKaCmPBLLNt1P0sHplnH2CWSROtE-4a4_11rXUheJGJe616PzkWX6RLO5Dotd2n4x30ioUxm42MH4MpvzTOWu5MxvKr8');
            $msgBuilder->withMediaUrl('https://stc-developers.zdn.vn/images/bg_1.jpg');

            $msgImage = $msgBuilder->build();

            // send request
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $this->accessToken, $msgImage);
            $result = $response->getDecodedBody();
            print_r('testSendImageMessageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testSendMessageListAPI()
    {
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
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $this->accessToken, $msgList);
            $result = $response->getDecodedBody(); // result
            print_r('testSendMessageListAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testSendGifMessageAPI()
    {
        try {
            $msgBuilder = new MessageBuilder('media');
            $msgBuilder->withUserId('user_id');
            $msgBuilder->withText('Message Image');
            $msgBuilder->withAttachment('pEq6ALHczYF1w0OpGMFcDT3h42LGOV0ZnkW2CqPbuZN6j5Hz57wiRfcb735QRweWbQKFCL1rgoE8g0SnN7_gESRm73rNU-mspFCNE5PouJ3D_GnjMtV_Dv-lM6WDS_XcbgWFRGHth7EAx5jj4dVn9SI-G2D9AE1jYF1H9GSau7w8xKejNZkcQvtc2db9CUTnqh4LVaWyvdFJl1DxI3RfTT-t17eSE_H_sh5GSau_yIYAj05r7ZkdPzRo0dbzehd2F1LszJS');
            $msgBuilder->withMediaType('gif');
            $msgBuilder->withMediaSize(120, 120);
            $msgImage = $msgBuilder->build();

            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $this->accessToken, $msgImage);
            $result = $response->getDecodedBody(); // result
            print_r('testSendGifMessageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testSendFileMessageAPI()
    {
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

    public function testUploadImageAPI()
    {
        try {
            $filePath = 'put your file path';
            $data = array('file' => new ZaloFile($filePath));
            $response = $this->zalo->post(ZaloEndPoint::API_OA_UPLOAD_PHOTO, $this->accessToken, $data);
            $result = $response->getDecodedBody(); // result
            print_r('testUploadImageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testUploadGifAPI()
    {
        try {
            $filePath = 'put your file path';
            $data = array('file' => new ZaloFile($filePath));
            $response = $this->zalo->post(ZaloEndPoint::API_OA_UPLOAD_GIF, $this->accessToken, $data);
            $result = $response->getDecodedBody(); // result
            print_r('testUploadGifAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testUploadPDFAPI()
    {
        try {
            $filePath = 'put your file path';
            $data = array('file' => new ZaloFile($filePath));
            $response = $this->zalo->post(ZaloEndPoint::API_OA_UPLOAD_FILE, $this->accessToken, $data);
            $result = $response->getDecodedBody(); // result
            print_r('testUploadPDFAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testGetListTagOfOAAPI()
    {
        try {
            $response = $this->zalo->get(ZaloEndPoint::API_OA_GET_LIST_TAG, $this->accessToken, []);
            $result = $response->getDecodedBody();
            print_r('testGetListTagOfOAAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testRemoveTagOfOAAPI()
    {
        try {
            $data = array('tag_name' => 'vip');
            $response = $this->zalo->post(ZaloEndPoint::API_OA_REMOVE_TAG, $this->accessToken, $data);
            $result = $response->getDecodedBody();
            print_r('testRemoveTagOfOAAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testRemoveUserFromTagAPI()
    {
        try {
            $data = array(
                'user_id' => 'user_id',
                'tag_name' => 'tag_name'
            );
            $response = $this->zalo->post(ZaloEndPoint::API_OA_REMOVE_USER_FROM_TAG, $this->accessToken, $data);
            $result = $response->getDecodedBody();
            print_r('testRemoveUserFromTagAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testAddUserToTagAPI()
    {
        try {
            $data = array(
                'user_id' => 'user_id',
                'tag_name' => 'tag_name'
            );
            $response = $this->zalo->post(ZaloEndPoint::API_OA_TAG_USER, $this->accessToken, $data);
            $result = $response->getDecodedBody();
            print_r('testAddUserToTagAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOAGetListFollowerAPI()
    {
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

    public function testOAGetUserProfileAPI()
    {
        try {
            $data = ['data' => json_encode(array(
                'user_id' => 'user_id'
            ))];
            $response = $this->zalo->get(ZaloEndPoint::API_OA_GET_USER_PROFILE, $this->accessToken, $data);
            $result = $response->getDecodedBody(); // result
            print_r('testOAGetUserProfileAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOAGetProfileAPI()
    {
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

    public function testOAGetListRecentChatAPI()
    {
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

    public function testOAGetConversationAPI()
    {
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

    public function testOASendConsultationTextMessageAPI()
    {
        try {
            $msgBuilder = new MessageBuilder(MessageBuilder::MSG_TYPE_TXT);
            $msgBuilder->withUserId('user_id');
            $msgBuilder->withText('Message Text');

            $msgText = $msgBuilder->build();

            // send request
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_CONSULTATION_MESSAGE_V3, $this->accessToken, $msgText);
            $result = $response->getDecodedBody();
            print_r('testOASendConsultationTextMessageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOASendImageConsultationMessageAPI()
    {
        try {
            $msgBuilder = new MessageBuilder(MessageBuilder::MSG_TYPE_MEDIA);
            $msgBuilder->withUserId('user_id');
            $msgBuilder->withText('Message Image');
//            $msgBuilder->withAttachment('O5DQPI_gKq9iMbDZBET7L6jDHNKk_4XCUKTIOpxvNaTdKr9mAFa24Ia1IJvpfXDPPKzOE3sg4qfbKaCmPBLLNt1P0sHplnH2CWSROtE-4a4_11rXUheJGJe616PzkWX6RLO5Dotd2n4x30ioUxm42MH4MpvzTOWu5MxvKr8');
            $msgBuilder->withMediaUrl('https://stc-developers.zdn.vn/images/bg_1.jpg');

            $msgImage = $msgBuilder->build();

            // send request
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_CONSULTATION_MESSAGE_V3, $this->accessToken, $msgImage);
            $result = $response->getDecodedBody();
            print_r('testOASendImageConsultationMessageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOARequestUserInfoConsultationMessageAPI()
    {
        try {
            $msgBuilder = new MessageBuilder(MessageBuilder::MSG_TYPE_REQUEST_USER_INFO);
            $msgBuilder->withUserId('user_id');

            $element = array(
                "title" => "OA Chatbot (Testing)",
                "subtitle" => "Đang yêu cầu thông tin từ bạn",
                "image_url" => "https://stc-oa-chat-adm.zdn.vn/images/request-info-banner.png"
            );
            $msgBuilder->addElement($element);

            $msgText = $msgBuilder->build();

            // send request
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_CONSULTATION_MESSAGE_V3, $this->accessToken, $msgText);
            $result = $response->getDecodedBody();
            print_r('testOARequestUserInfoConsultationMessageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOASendFileConsultationMessageAPI()
    {
        try {
            $msgBuilder = new MessageBuilder(MessageBuilder::MSG_TYPE_FILE);
            $msgBuilder->withUserId('user_id');
            $msgBuilder->withFileToken('PkkPJZzjmrbliDeEQ6h6MYVxrmrkSenF8A3N0MGpcWailOnLPpZDM7lbsG9YRSe8VxEG0MSuqmv_l945R3NM2d6vb0ThDhvQCgUYKo1vW0nwlRO6EdUnNoQWeo5sUUTqA8JM7cXHuNPqeiK81569QLtRcHSWEDWUFBQO7aa-taK6vvn473xS7m2xa1ySCzmUL-wR54mpYaXGxfj0CIgyTJWa8vW6');
            $msgFile = $msgBuilder->build();

            // send request
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_CONSULTATION_MESSAGE_V3, $this->accessToken, $msgFile);
            $result = $response->getDecodedBody();
            print_r('testOASendFileConsultationMessageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOASendQuoteConsultationMessageAPI()
    {
        try {
            $msgBuilder = new MessageBuilder(MessageBuilder::MSG_TYPE_TXT);
            $msgBuilder->withUserId('user_id');

            $msgBuilder->withText("quote message");
            $msgBuilder->withQuoteMessage('93f8c4b28f589705ce4a');

            $msgText = $msgBuilder->build();

            // send request
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_CONSULTATION_MESSAGE_V3, $this->accessToken, $msgText);
            $result = $response->getDecodedBody();
            print_r('testOASendQuoteConsultationMessageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOASendStickerConsultationMessage()
    {
        try {
            $msgBuilder = new MessageBuilder(MessageBuilder::MSG_TYPE_MEDIA);
            $msgBuilder->withUserId('user_id');
            $msgBuilder->withText('text');
            $msgBuilder->withMediaType('sticker');
            $msgBuilder->withAttachment('bfe458bf64fa8da4d4eb');

            $msgSticker = $msgBuilder->build();

            // send request
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_CONSULTATION_MESSAGE_V3, $this->accessToken, $msgSticker);
            $result = $response->getDecodedBody();
            print_r('testOASendStickerConsultationMessage: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOASendTransactionMessageAPI()
    {
        try {
            $msgBuilder = new MessageBuilder(MessageBuilder::MSG_TYPE_TRANSACTION);
            $msgBuilder->withUserId('user_id');

            $msgBuilder->withTemplateType(TransactionTemplateType::TRANSACTION_ORDER);
            $msgBuilder->withLanguage("VI");

            $bannerElement = array(
                'attachment_id' => 'a-JJEvLdkcEPxTOwb6gYTfhwm26VSBHjaE3MDfrWedgLyC0smJRiA8w-csdGVg1cdxZLPT1je7k4i8nwbdYrSCJact3NOVGltEUQTjDayIhTvf1zqsR-Ai3aboRERgjvm-cI8iqv-NoIxi0cdNBoE6SYVJooM6xKTBft',
                'type' => 'banner'
            );
            $msgBuilder->addElement($bannerElement);

            $headerElement = array(
                'content' => 'Trạng thái đơn hàng',
                'align' => 'left',
                'type' => 'header'
            );
            $msgBuilder->addElement($headerElement);

            $text1Element = array(
                'align' => 'left',
                'content' => '• Cảm ơn bạn đã mua hàng tại cửa hàng.<br>• Thông tin đơn hàng của bạn như sau:',
                'type' => 'text'

            );
            $msgBuilder->addElement($text1Element);

            $tableContent1 = array(
                'key' => 'Mã khách hàng',
                'value' => 'F-01332973223'
            );
            $tableContent2 = array(
                'key' => 'Trạng thái',
                'value' => 'Đang giao',
                'style' => 'yellow',
            );
            $tableContent3 = array(
                'key' => 'Giá tiền',
                'value' => '250,000đ'
            );
            $tableElement = array(
                'content' => array($tableContent1, $tableContent2, $tableContent3),
                'type' => 'table'
            );
            $msgBuilder->addElement($tableElement);

            $text2Element = array(
                'content' => 'Lưu ý điện thoại. Xin cảm ơn!',
                'align' => 'center',
                'type' => 'text'
            );
            $msgBuilder->addElement($text2Element);

            $actionOpenUrl = $msgBuilder->buildActionOpenURL('https://oa.zalo.me/home');
            $msgBuilder->addButton('Kiểm tra lộ trình - default icon', '', $actionOpenUrl);

            $actionQueryShow = $msgBuilder->buildActionQueryShow('Xem lại giỏ hàng');
            $msgBuilder->addButton('Xem lại giỏ hàng', 'wZ753VDsR4xWEC89zNTsNkGZr1xsPs19vZF22VHtTbxZ8zG9g24u3FXjZrQvQNH2wMl1MhbwT5_oOvX5_szXLB8tZq--TY0Dhp61JRfsAWglCej8ltmg3xC_rqsWAdjRkctG5lXzAGVlQe9BhZ9mJcSYVIDsc7MoPMnQ', $actionQueryShow);

            $actionOpenPhone = $msgBuilder->buildActionOpenPhone('84123456789');
            $msgBuilder->addButton('Liên hệ tổng đài', 'gNf2KPUOTG-ZSqLJaPTl6QTcKqIIXtaEfNP5Kv2NRncWPbDJpC4XIxie20pTYMq5gYv60DsQRHYn9XyVcuzu4_5o21NQbZbCxd087DcJFq7bTmeUq9qwGVie2ahEpZuLg2KDJfJ0Q12c85jAczqtKcSYVGJJ1cZMYtKR', $actionOpenPhone);

            $msgTransaction = $msgBuilder->build();

            // send request
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_TRANSACTION_MESSAGE_V3, $this->accessToken, $msgTransaction);
            $result = $response->getDecodedBody();
            print_r('testOASendTransactionMessageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOASendPromotionMessageAPI()
    {
        try {
            $msgBuilder = new MessageBuilder(MessageBuilder::MSG_TYPE_PROMOTION);
            $msgBuilder->withUserId('user_id');

            $bannerElement = array(
                'attachment_id' => 'aERC3A0iYGgQxim8fYIK6fxzsXkaFfq7ZFRB3RCyZH6RyziRis3RNydebK3iSPCJX_cJ3k1nW1EQufjN_pUL1f6Ypq3rTef5nxp6H_HnXKFDiyD5y762HS-baqRpQe5FdA376lTfq1sRyPr8ypd74ecbaLyA-tGmuJ-97W',
                'type' => 'banner'
            );
            $msgBuilder->addElement($bannerElement);

            $headerElement = array(
                'content' => '💥💥Ưu đãi thành viên Platinum💥💥',
                'type' => 'header'
            );
            $msgBuilder->addElement($headerElement);

            $text1Element = array(
                'content' => 'Ưu đãi dành riêng cho khách hàng Nguyen Van A hạng thẻ Platinum<br>Voucher trị giá 150$',
                'type' => 'text',
                'align' => 'left'

            );
            $msgBuilder->addElement($text1Element);

            $tableContent1 = array(
                'key' => 'Voucher',
                'value' => 'VC09279222'
            );
            $tableContent2 = array(
                'key' => 'Hạn sử dụng',
                'value' => '30/12/2023'
            );
            $tableElement = array(
                'content' => array($tableContent1, $tableContent2),
                'type' => 'table'
            );
            $msgBuilder->addElement($tableElement);

            $text2Element = array(
                'content' => 'Áp dụng tất cả cửa hàng trên toàn quốc',
                'type' => 'text',
                'align' => 'center'

            );
            $msgBuilder->addElement($text2Element);

            $actionOpenUrl = $msgBuilder->buildActionOpenURL('https://oa.zalo.me/home');
            $msgBuilder->addButton('Tham khảo chương trình', '', $actionOpenUrl);

            $actionQueryShow = $msgBuilder->buildActionQueryHide('#tuvan');
            $msgBuilder->addButton('Liên hệ chăm sóc viên', 'aeqg9SYn3nIUYYeWohGI1fYRF3V9f0GHceig8Ckq4WQVcpmWb-9SL8JLPt-6gX0QbTCfSuQv40UEst1imAm53CwFPsQ1jq9MsOnlQe6rIrZOYcrlWBTAKy_UQsV9vnfGozCuOvFfIbN5rcXddFKM4sSYVM0D50I9eWy3', $actionQueryShow);

            $msgPromotion = $msgBuilder->build();

            // send request
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_PROMOTION_MESSAGE_V3, $this->accessToken, $msgPromotion);
            $result = $response->getDecodedBody();
            print_r('testOASendPromotionMessageAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }
}