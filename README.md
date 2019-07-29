# Zalo SDK for PHP (v2.0.0)


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
    'app_secret' => 'AbC123456XyZ',
    'callback_url' => 'https://www.callback.com'
);
$zalo = new Zalo($config);
```

## Social API

***Lấy link đăng nhập***
```php
$helper = $zalo -> getRedirectLoginHelper();
$callbackUrl = "https://www.callbackack.com";
$loginUrl = $helper->getLoginUrl($callBackUrl); // This is login url
```

**Lấy access token**
>Khi người dùng click vào link đăng nhập,
>Hệ thống sẽ thực hiện xử lý đăng nhập cho người dùng và chuyển hướng về link callback đã đăng ký với app,
>OAuth code sẽ được trả về và hiển thị trên đường dẫn của link callback ,
>Hãy đặt đoạn mã dưới tại link callback bạn đã đăng ký với app, đoạn mã sẽ thực hiện lấy oauth code từ link callback và gửi yêu cầu lên hệ thống để lấy access token.

```php
$callBackUrl = "www.put_your_call_backack_url_here.com";
$oauthCode = isset($_GET['code']) ? $_GET['code'] : "THIS NOT CALLBACK PAGE !!!"; // get oauthoauth code from url params
$accessToken = $helper->getAccessToken($callBackUrl); // get access token
if ($accessToken != null) {
    $expires = $accessToken->getExpiresAt(); // get expires time
}
```

**Lấy thông tin người dùng**
```php
$accessToken = 'put_your_access_token_here';
$params = ['fields' => 'id,name,birthday,gender,picture'];
$response = $zalo->get(ZaloEndpoint::API_GRAPH_ME, $accessToken, $params);
$result = $response->getDecodedBody(); // result
```

**Lấy danh sách bạn bè**
```php
$accessToken = 'put_your_access_token_here';
$params = ['offset' => 0, 'limit' => 10, 'fields' => "id, name"];
$response = $zalo->get(ZaloEndpoint::API_GRAPH_FRIENDS, $accessToken, $params);
$result = $response->getDecodedBody(); // result
```

**Lấy danh sách bạn bè chưa sử dụng ứng dụng và có thể nhắn tin mời sử dụng ứng dụng**
```php
$accessToken = 'put_your_access_token_here';
$params = ['offset' => 0, 'limit' => 10, 'fields' => "id, name"];
$response = $zalo->get(ZaloEndpoint::API_GRAPH_INVITABLE_FRIENDS, $accessToken, $params);
$result = $response->getDecodedBody(); // result
```

**Đăng bài viết**
```php
$accessToken = 'put_your_access_token_here';
$params = ['message' => 'put_your_text_here', 'link' => 'put_your_link_here'];
$response = $zalo->post(ZaloEndpoint::API_GRAPH_POST_FEED, $accessToken, $params);
$result = $response->getDecodedBody(); // result
```

**Mời sử dụng ứng dụng**
```php
$accessToken = 'put_your_access_token_here';
$params = ['message' => 'put_your_message_here', 'to' => 'put_user_id_receive_here'];
$response = $zalo->post(ZaloEndpoint::API_GRAPH_APP_REQUESTS, $accessToken, $params);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn tới bạn bè**
```php
$accessToken = 'put_your_access_token_here';
$params = ['message' => 'put_your_message_here', 'to' => 'put_user_id_receive_here', 'link' => 'put_your_link_here'];
$response = $zalo->post(ZaloEndpoint::API_GRAPH_MESSAGE, $accessToken, $params);
$result = $response->getDecodedBody(); // result
```

## Official Account Open API

**Tạo link Offical Account ủy quyền cho ứng dụng**
```php
$callbackPageUrl = "https://www.callbackPage.com"
$linkOAGrantPermission2App = $helper->getLoginUrlByPage($callbackPageUrl); // This is url for admin OA grant permission to app
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
$response = $zalo->post(ZaloEndpoint::API_OA_SEND_MESSAGE, $accessToken, $msgText);
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
$response = $zalo->post(ZaloEndpoint::API_OA_SEND_MESSAGE, $accessToken, $msgImage);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn list**
```php
$msgBuilder = new MessageBuilder('list');
$msgBuilder->withUserId('494021888309207992');

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
$response = $zalo->post(ZaloEndpoint::API_OA_SEND_MESSAGE, $accessToken, $msgList);
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

$response = $zalo->post(ZaloEndpoint::API_OA_SEND_MESSAGE, $accessToken, $msgImage);
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

**Gửi tin nhắn mời quan tâm**
```php
// build data
$msgBuilder = new MessageBuilder('template');
$msgBuilder->withPhoneNumber('0919018791');
$msgBuilder->withTemplate('87efbc018044691a3055', []);
$msgInvite = $msgBuilder->build();
// send request
$response = $zalo->post(ZaloEndPoint::API_OA_SEND_MESSAGE, $accessToken, $msgInvite);
$result = $response->getDecodedBody();
```

**Upload hình**
```php
$data = array('file' => new ZaloFile($filePath));
$response = $zalo->post(ZaloEndpoint::API_OA_UPLOAD_PHOTO, $accessToken, $data);
$result = $response->getDecodedBody(); // result
```

**Upload hình Gif**
```php
$data = array('file' => new ZaloFile($filePath));
$response = $zalo->post(ZaloEndpoint::API_OA_UPLOAD_GIF, $accessToken, $data);
$result = $response->getDecodedBody(); // result
```

**Upload file PDF**
```php
$data = array('file' => new ZaloFile($filePath));
$response = $zalo->post(ZaloEndpoint::API_OA_UPLOAD_FILE, $accessToken, $data);
$result = $response->getDecodedBody(); // result
```

**Lấy danh sách nhãn**
```php
$response = $zalo->get(ZaloEndpoint::API_OA_GET_LIST_TAG, $accessToken, []);
$result = $response->getDecodedBody();
```

**Xóa nhãn**
```php
// build data
$data = array('tag_name' => 'vip');
// send request
$response = $zalo->post(ZaloEndpoint::API_OA_REMOVE_TAG, $accessToken, $data);
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
$response = $zalo->post(ZaloEndpoint::API_OA_REMOVE_USER_FROM_TAG, $accessToken, $data);
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
$response = $zalo->post(ZaloEndpoint::API_OA_TAG_USER, $accessToken, $data);
$result = $response->getDecodedBody();
```

**Lấy thông tin người quan tâm**
```php
$data = ['data' => json_encode(array(
            'user_id' => '494021888309207992'
        ))];
$response = $zalo->get(ZaloEndpoint::API_OA_GET_USER_PROFILE, $accessToken, $data);
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
                'user_id' => 494021888309207992,
                'offset' => 0,
                'count' => 10
            ))];
$response = $zalo->get(ZaloEndPoint::API_OA_GET_CONVERSATION, $accessToken, $data);
$result = $response->getDecodedBody(); // result
```

## Versioning

Current version is 2.0.0. We will update more features in next version.

## Authors

* **Zalo's Developer** 

## License

This project is licensed under the MIT License.

