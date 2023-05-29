# Zalo SDK for PHP (v4.0.2)


Landing page: <a href="https://developers.zalo.me/">https://developers.zalo.me/</a><br>
<strong>Blog:</strong> there are lots of great tutorials and guides published in our <a href="https://developers.zalo.me/docs/">Official Zalo Platform Blog</a> and we are adding new content regularly.<br>
<strong>Community:</strong> If you are having trouble using some feature, the best way to get help is the <a href="https://developers.zalo.me/community/">Zalo Community</a><br>
<strong>Support:</strong> We are also available to answer short questions on Zalo at <a href="https://zalo.me/zalo4developers">Official Account Zalo For Developers</a><br>

## Installation

The Zalo PHP SDK can be installed with [Composer](https://getcomposer.org/). Run this command:

```sh
composer require zaloplatform/zalo-php-sdk
```

## How To Use

**Import Autoload** 
```php
require_once __DIR__ . '/vendor/autoload.php';
```

**Khởi tạo**
```php
use Zalo\Zalo;

$config = array(
    'app_id' => '1234567890987654321',
    'app_secret' => 'AbC123456XyZ'
);
$zalo = new Zalo($config);
```

## Social API

Tài liệu chi tiết <a href="https://developers.zalo.me/docs/social-api/tham-khao/user-access-token-v4">tại đây</a>.

***Lấy link đăng nhập***
```php
$helper = $zalo -> getRedirectLoginHelper();
$callbackUrl = "https://www.callbackack.com";
$codeChallenge = "your code challenge";
$state = "your state";
$loginUrl = $helper->getLoginUrl($callbackUrl, $codeChallenge, $state); // This is login url
```

**Lấy access token**
>Khi người dùng click vào link đăng nhập,
>Hệ thống sẽ thực hiện xử lý đăng nhập cho người dùng và chuyển hướng về link callback đã đăng ký với app,
>OAuth code sẽ được trả về và hiển thị trên đường dẫn của link callback ,
>Hãy đặt đoạn mã dưới tại link callback bạn đã đăng ký với app, đoạn mã sẽ thực hiện lấy oauth code từ link callback và gửi yêu cầu lên hệ thống để lấy access token.

```php
$codeVerifier = "your code verifier";
$zaloToken = $helper->getZaloToken($codeVerifier); // get zalo token
$accessToken = $zaloToken->getAccessToken();
```

**Lấy thông tin người dùng**
```php
$accessToken = 'put_your_access_token_here';
$params = ['fields' => 'id,name,picture'];
$response = $zalo->get(ZaloEndPoint::API_GRAPH_ME, $accessToken, $params);
$result = $response->getDecodedBody(); // result
```

## Official Account Open API

Tài liệu chi tiết <a href="https://developers.zalo.me/docs/api/official-account-api/xac-thuc-va-uy-quyen/cach-1-xac-thuc-voi-giao-thuc-oauth/yeu-cau-cap-moi-oa-access-token-post-4307">tại đây</a>.

**Tạo link Offical Account ủy quyền cho ứng dụng**
```php
$oaCallbackUrl = "https://www.callbackPage.com"
$codeChallenge = "your code challenge";
$state = "your state";
$linkOAGrantPermission2App = $helper->getLoginUrlByOA($oaCallbackUrl, $codeChallenge, $state); // This is url for admin OA grant permission to app
```

**Lấy access token**
>Khi quản trị viên của OA click vào link và ủy quyền cho ứng dụng,
>Hệ thống sẽ thực hiện xử lý và chuyển hướng về link callback đã đăng ký với app,
>OAuth code sẽ được trả về và hiển thị trên đường dẫn của link callback ,
>Hãy đặt đoạn mã dưới tại link callback bạn đã đăng ký với app, đoạn mã sẽ thực hiện lấy oauth code từ link callback và gửi yêu cầu lên hệ thống để lấy access token.

```php
$codeVerifier = "your code verifier";
$zaloToken = $helper->getZaloTokenByOA($codeVerifier); // get zalo token
$accessToken = $zaloToken->getAccessToken();
```

**Gửi tin Tư vấn dạng văn bản**
```php
$msgBuilder = new MessageBuilder(MessageBuilder::MSG_TYPE_TXT);
$msgBuilder->withUserId('user_id');
$msgBuilder->withText('Message Text');

$msgText = $msgBuilder->build();

// send request
$response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_CONSULTATION_MESSAGE_V3, $this->accessToken, $msgText);
$result = $response->getDecodedBody();
```

**Gửi tin Tư vấn đính kèm hình ảnh**
```php
$msgBuilder = new MessageBuilder(MessageBuilder::MSG_TYPE_MEDIA);
$msgBuilder->withUserId('user_id');
$msgBuilder->withText('Message Image');
$msgBuilder->withMediaUrl('https://stc-developers.zdn.vn/images/bg_1.jpg');

$msgImage = $msgBuilder->build();

// send request
$response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_CONSULTATION_MESSAGE_V3, $this->accessToken, $msgImage);
$result = $response->getDecodedBody();
```

**Gửi tin Tư vấn theo mẫu yêu cầu thông tin người dùng**
```php
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
```

**Gửi tin Tư vấn đính kèm file**
```php
$msgBuilder = new MessageBuilder(MessageBuilder::MSG_TYPE_FILE);
$msgBuilder->withUserId('user_id');
$msgBuilder->withFileToken('PkkPJZzjmrbliDeEQ6h6MYVxrmrkSenF8A3N0MGpcWailOnLPpZDM7lbsG9YRSe8VxEG0MSuqmv_l945R3NM2d6vb0ThDhvQCgUYKo1vW0nwlRO6EdUnNoQWeo5sUUTqA8JM7cXHuNPqeiK81569QLtRcHSWEDWUFBQO7aa-taK6vvn473xS7m2xa1ySCzmUL-wR54mpYaXGxfj0CIgyTJWa8vW6');
$msgFile = $msgBuilder->build();

// send request
$response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_CONSULTATION_MESSAGE_V3, $this->accessToken, $msgFile);
$result = $response->getDecodedBody();
```

**Gửi tin Tư vấn trích dẫn**
```php
$msgBuilder = new MessageBuilder(MessageBuilder::MSG_TYPE_TXT);
$msgBuilder->withUserId('user_id');

$msgBuilder->withText("quote message");
$msgBuilder->withQuoteMessage('93f8c4b28f589705ce4a');

$msgText = $msgBuilder->build();

// send request
$response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_CONSULTATION_MESSAGE_V3, $this->accessToken, $msgText);
$result = $response->getDecodedBody();
```

**Gửi tin Tư vấn kèm Sticker**
```php
$msgBuilder = new MessageBuilder(MessageBuilder::MSG_TYPE_MEDIA);
$msgBuilder->withUserId('user_id');
$msgBuilder->withText('text');
$msgBuilder->withMediaType('sticker');
$msgBuilder->withAttachment('bfe458bf64fa8da4d4eb');

$msgSticker = $msgBuilder->build();

// send request
$response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_CONSULTATION_MESSAGE_V3, $this->accessToken, $msgSticker);
$result = $response->getDecodedBody();
```

**Gửi tin Giao dịch**
```php
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
```

**Gửi tin Truyền thông cá nhân**
```php
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
```

**Gửi tin nhắn text**
```php
// build data
$msgBuilder = new MessageBuilder('text');
$msgBuilder->withUserId('494021888309207992');
$msgBuilder->withText('Message Text');

// add buttons (only support 5 buttons - optional)
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
$response = $zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgText);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn hình**
```php
// build data
$msgBuilder = new MessageBuilder('media');
$msgBuilder->withUserId('494021888309207992');
$msgBuilder->withText('Message Image');
$msgBuilder->withAttachment('cb2ab1696b688236db79');

// add buttons (only support 5 buttons - optional)
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
$response = $zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgImage);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn list**
```php
$msgBuilder = new MessageBuilder('list');
$msgBuilder->withUserId('494021888309207992');
$msgBuilder->withText('Message Text');

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
$response = $zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgList);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn dạng Gif**
```php
$msgBuilder = new MessageBuilder('media');
$msgBuilder->withUserId('494021888309207992');
$msgBuilder->withText('Message Image');
$msgBuilder->withAttachment('PWhbF13YGGi9VTkG/vHcTyoskajfj5Ve/EGsTK80XYo=');
$msgBuilder->withMediaType('gif');
$msgBuilder->withMediaSize(120, 120);
$msgImage = $msgBuilder->build();

$response = $zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgImage);
$result = $response->getDecodedBody(); // result
```

**Gửi File**
```php
$msgBuilder = new MessageBuilder('file');
$msgBuilder->withUserId('494021888309207992');
$msgBuilder->withFileToken('call_upload_file_api_to_get_file_token');
$msgFile = $msgBuilder->build();
$response = $zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgFile);
$result = $response->getDecodedBody(); // result
```

**Upload hình**
```php
$data = array('file' => new ZaloFile($filePath));
$response = $zalo->post(ZaloEndPoint::API_OA_UPLOAD_PHOTO, $accessToken, $data);
$result = $response->getDecodedBody(); // result
```

**Upload hình Gif**
```php
$data = array('file' => new ZaloFile($filePath));
$response = $zalo->post(ZaloEndPoint::API_OA_UPLOAD_GIF, $accessToken, $data);
$result = $response->getDecodedBody(); // result
```

**Upload file PDF**
```php
$data = array('file' => new ZaloFile($filePath));
$response = $zalo->post(ZaloEndPoint::API_OA_UPLOAD_FILE, $accessToken, $data);
$result = $response->getDecodedBody(); // result
```

**Lấy danh sách nhãn**
```php
$response = $zalo->get(ZaloEndPoint::API_OA_GET_LIST_TAG, $accessToken, []);
$result = $response->getDecodedBody();
```

**Xóa nhãn**
```php
// build data
$data = array('tag_name' => 'vip');
// send request
$response = $zalo->post(ZaloEndPoint::API_OA_REMOVE_TAG, $accessToken, $data);
$result = $response->getDecodedBody();
```

**Gỡ người quan tâm khỏi nhãn**
```php
// build data
$data = array(
        'user_id' => '494021888309207992',
        'tag_name' => 'vip'
);
// send request
$response = $zalo->post(ZaloEndPoint::API_OA_REMOVE_USER_FROM_TAG, $accessToken, $data);
$result = $response->getDecodedBody();
```

**Gán nhãn người quan tâm**
```php
// build data
$data = array(
        'user_id' => '494021888309207992',
        'tag_name' => 'vip'
);
// send request
$response = $zalo->post(ZaloEndPoint::API_OA_TAG_USER, $accessToken, $data);
$result = $response->getDecodedBody();
```

**Lấy thông tin người quan tâm**
```php
$data = ['data' => json_encode(array(
            'user_id' => '494021888309207992'
        ))];
$response = $zalo->get(ZaloEndPoint::API_OA_GET_USER_PROFILE, $accessToken, $data);
$result = $response->getDecodedBody(); // result
```

**Lấy thông tin OA**
```php
$response = $zalo->get(ZaloEndPoint::API_OA_GET_PROFILE, $accessToken, []);
$result = $response->getDecodedBody(); // result
```

**Lấy danh sách người quan tâm**
```php
$data = ['data' => json_encode(array(
                'offset' => 0,
                'count' => 10
            ))];
$response = $this->zalo->get(ZaloEndPoint::API_OA_GET_LIST_FOLLOWER, $accessToken, $data);
$result = $response->getDecodedBody(); // result
```

**Lấy danh sách tin nhắn gần nhất**
```php
$data = ['data' => json_encode(array(
                'offset' => 0,
                'count' => 10
            ))];
$response = $zalo->get(ZaloEndPoint::API_OA_GET_LIST_RECENT_CHAT, $accessToken, $data);
$result = $response->getDecodedBody(); // result
```

**Lấy danh sách tin nhắn với người quan tâm**
```php
$data = ['data' => json_encode(array(
                'user_id' => '494021888309207992',
                'offset' => 0,
                'count' => 10
            ))];
$response = $zalo->get(ZaloEndPoint::API_OA_GET_CONVERSATION, $accessToken, $data);
$result = $response->getDecodedBody(); // result
```

## Versioning

Current version is 4.0.2. We will update more features in next version.

## Authors

* **Zalo's Developer** 

## License

This project is licensed under the [MIT licensed](./LICENSE).

