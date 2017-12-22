# Zalo SDK for PHP (v1.0.3)

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

**Configuration**

***File to config -> ZaloConfig.php***
```php
/** config your app id here */
const ZALO_APP_ID_CFG = "put_your_app_id_here";
    
/** config your app secret key here */
const ZALO_APP_SECRET_KEY_CFG = "put_your_secret_key_here";

/** config your offical account id here */
const ZALO_OA_ID_CFG = "put_your_oa_id_here";

/** config your offical account secret key here */
const ZALO_OA_SECRET_KEY_CFG = "put_your_oa_secret_key_here";
```
**Create an instance of the Zalo class**
```php
use Zalo\Zalo;
use Zalo\ZaloConfig;

$zalo = new Zalo(ZaloConfig::getInstance()->getConfig());
```

## Social API

***Lấy link đăng nhập***
```php
$helper = $zalo -> getRedirectLoginHelper();
$callBackUrl = "www.put_your_call_backack_url_here.com";
$loginUrl = $helper->getLoginUrl($callBackUrl); // This is login url
```

**Lấy access token**
>Khi người dùng click vào link đăng nhập,
>Hệ thống sẽ thực hiện xử lý đăng nhập cho người dùng và chuyển hướng về link callback đã đăng ký với app,
>Oauth code sẽ được trả về và hiển thị trên đường dẫn của link callback ,
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
$params = [];
$response = $zalo->get(ZaloEndpoint::API_GRAPH_ME, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Lấy danh sách bạn bè**
```php
$accessToken = 'put_your_access_token_here';
$params = ['offset' => 0, 'limit' => 10, 'fields' => "id, name"];
$response = $zalo->get(ZaloEndpoint::API_GRAPH_FRIENDS, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Lấy danh sách bạn bè chưa sử dụng ứng dụng và có thể nhắn tin mời sử dụng ứng dụng**
```php
$accessToken = 'put_your_access_token_here';
$params = ['offset' => 0, 'limit' => 10, 'fields' => "id, name"];
$response = $zalo->get(ZaloEndpoint::API_GRAPH_INVITABLE_FRIENDS, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Đăng bài viết**
```php
$accessToken = 'put_your_access_token_here';
$params = ['message' => 'put_your_text_here', 'link' => 'put_your_link_here'];
$response = $zalo->post(ZaloEndpoint::API_GRAPH_POST_FEED, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Mời sử dụng ứng dụng**
```php
$accessToken = 'put_your_access_token_here';
$params = ['message' => 'put_your_message_here', 'to' => 'put_user_id_receive_here'];
$response = $zalo->post(ZaloEndpoint::API_GRAPH_APP_REQUESTS, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn tới bạn bè**
```php
$accessToken = 'put_your_access_token_here';
$params = ['message' => 'put_your_message_here', 'to' => 'put_user_id_receive_here', 'link' => 'put_your_link_here'];
$response = $zalo->post(ZaloEndpoint::API_GRAPH_MESSAGE, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

## Official Account Open API
**Gửi tin nhắn mời quan tâm**
```php
$templateData = array(
    'template_key' => "template_value"
);
$data = array(
    'phone' => 84912345678,
    'templateid' => "put_template_id_here",
    'templatedata' => $templateData,
    'callbackdata' => "put_your_call_back_link_here"
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_SEND_FOLLOW_MSG, $params);
$result = $response->getDecodedBody();
```

**Lấy danh sách nhãn**
```php
$params = [];
$response = $zalo->get(ZaloEndpoint::API_OA_GET_LIST_TAG, $params);
$result = $response->getDecodedBody();
```

**Xóa nhãn**
```php
$data = array(
    'tagName' => "put_tag_name_here"
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_REMOVE_TAG, $params);
$result = $response->getDecodedBody();
```

**Gỡ người quan tâm khỏi nhãn**
```php
$data = array(
    'uid' => 0,
    'tagName' => "put_tag_name_here"
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_REMOVE_USER_FROM_TAG, $params);
$result = $response->getDecodedBody();
```

**Gán nhãn người quan tâm**
```php
$data = array(
    'uid' => 0,
    'tagName' => "put_tag_name_here"
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_TAG_USER, $params);
$result = $response->getDecodedBody();
```

**Gửi tin nhắn text**
```php
$data = array(
    'uid' => 1785179753369910605, // user id
    'message' => 'put_your_text_message_here'
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_SEND_TEXT_MSG, $params);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn hình**
```php
 $data = array(
    'uid' => 1785179753369910605, // user id
    'imageid' => 'put_your_uploaded_image_id',
    'message' => 'put_your_text_message_here'
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_SEND_PHOTO_MSG, $params);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn liên kết**
```php
$firstLink = array('link' => 'put_url_here',
    'linktitle' => 'put_title_here',
    'linkdes' => 'put_description_here',
    'linkthumb' => 'put_thumbnail_url_here');
    
$secondLink = array('link' => 'put_url_here',
    'linktitle' => 'put_title_here',
    'linkdes' => 'put_description_here',
    'linkthumb' => 'put_thumbnail_url_here');
    
$data = array(
    'uid' => 1785179753369910605, // uid
    'links' => [$firstLink, $secondLink],
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_SEND_LINK_MSG, $params);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn tương tác**
```php
$firstAction = array('action' => 'oa.query.show',
    'title' => 'put_title_here',
    'description' => 'put_description_here',
    'data' => 'reply_message_when_user_click',
    'href' => 'put_url_here',
    'thumb' => 'put_thumbnail_url_here');

$popupForSecondAction = array('title' => 'put_title_here',
    'desc' => 'put_description_here',
    'ok' => 'title_of_ok_button',
    'cancel' => 'title_of_cancel_button'
);
$secondAction = array('action' => 'oa.query.show',
    'title' => 'put_title_here',
    'description' => 'put_description_here',
    'data' => 'reply_message_when_user_click',
    'href' => 'put_url_here',
    'thumb' => 'put_thumbnail_url_here',
    'popup' => $popupForSecondAction);

$data = array(
    'uid' => 1785179753369910605, // user id
    'actionlist' => [$firstAction, $secondAction],
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_SEND_ACTION_MSG, $params);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn dạng Gif**
```php
$data = array(
    'uid' => 1785179753369910605, // put_user_id_here
    'imageid' => "put_image_id_here",
    'width' => 200,
    'height' => 200
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_SEND_GIF_MSG, $params);
$result = $response->getDecodedBody(); // result
```

**Lấy thông tin người quan tâm**
```php
$params = ['uid' => 1785179753369910605]; // put user id here
$response = $zalo->get(ZaloEndpoint::API_OA_GET_PROFILE, $params);
$result = $response->getDecodedBody(); // result
```

**Upload hình**
```php
$filePath = 'path_to_image';
$params = ['file' => new ZaloFile($filePath)];
$response = $zalo->post(ZaloEndpoint::API_OA_UPLOAD_PHOTO, $params);
$result = $response->getDecodedBody(); // result
```

**Upload hình Gif**
```php
$filePath = 'path_to_image';
$params = ['file' => new ZaloFile($filePath)];
$response = $zalo->post(ZaloEndpoint::API_OA_UPLOAD_GIF, $params);
$result = $response->getDecodedBody(); // result
```

**Lấy trạng thái tin nhắn**
```php
$params = ['msgid' => 'put_message_id_here'];
$response = $zalo->get(ZaloEndpoint::API_OA_GET_MSG_STATUS, $params);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn chăm sóc khách hàng**
```php
$templateData = array(
    'username' => 'put_your_template_data_here', // request Offical Account Admin to get template data
    'invitename' => 'put_your_template_data_here'
);
$data = array(
    'uid' => 1785179753369910605, // user id
    'templateid' => 'put_your_template_id_here', // request Offical Account Admin to get template id
    'templatedata' => $templateData
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_SEND_CUSTOMER_CARE_MSG, $params);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn chăm sóc khách hàng qua số điện thoại**
```php
$templateData = array(
    'username' => 'put_your_template_data_here', // request Offical Account Admin to get template data
    'invitename' => 'put_your_template_data_here'
);
$data = array(
    'phone' => 84919018791, // phone number or user id
    'templateid' => 'put_your_template_id_here', // request Offical Account Admin to get template id
    'templatedata' => $templateData
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_SEND_CUSTOMER_CARE_MSG_BY_PHONE, $params);
$result = $response->getDecodedBody(); // result
```

**Trả lời tin nhắn dạng text**
```php
$data = array(
    'msgid' => "put_message_id_here",
    'message' => "put_message_here"
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_REPLY_TEXT_MSG, $params);
$result = $response->getDecodedBody(); // result
```

**Trả lời tin nhắn dạng hình**
```php
$data = array(
    'msgid' => "put_message_id_here",
    'imageid' => "put_image_id_here",
    'message' => "put_message_here"
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_REPLY_PHOTO_MSG, $params);
$result = $response->getDecodedBody(); // result
```

**Trả lời tin nhắn dạng liên kết**
```php
$firstLink = array('link' => 'put_link_here',
    'linktitle' => 'put_title_here',
    'linkdes' => 'put_description_here',
    'linkthumb' => 'put_thumnail_link_here');
$secondLink = array('link' => 'https://developers.zalo.me/docs/',
    'linktitle' => 'Documents for zalo developers',
    'linkdes' => 'Zalo for developer comunity',
    'linkthumb' => 'https://cms.developers.zalo.me/wp-content/uploads/2017/06/Oauth2.jpg');
$data = array(
    'msgid' => "put_message_id_here",
    'links' => [$firstLink, $secondLink],
);
$params = ['data' => $data];
$response = $this->zalo->post(ZaloEndpoint::API_OA_REPLY_LINK_MSG, $params);
$result = $response->getDecodedBody(); // result
```

**Tạo mã QR**
```php
$data = array(
    'qrdata' => "put_data_here",
    'size' => 1000, // put_size_here
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_CREATE_QR_CODE, $params);
$result = $response->getDecodedBody(); // result
```

## Official Account Open API Onbehalf
>Khi người dùng click vào link đăng nhập,
>Hệ thống sẽ thực hiện xử lý đăng nhập, và yêu cầu cấp quyền truy xuất thông tin của Offical Account,
>Sau khi người dùng đồng ý cấp quyền, hệ thống sẽ chuyển hướng về callback link đã đăng ký với app,
>Access token được hệ thống trả về và hiển thị trên callback link.

***Lấy link đăng nhập***
```php
$helper = $zalo -> getRedirectLoginHelper();
$callBackUrl = "www.put_your_call_backack_url_here.com";
$loginUrl = $helper->getLoginUrlByPage($callBackUrl); // This is login url
```

**Lấy thông tin người quan tâm**
```php
$accessToken = "put_access_token_here";
$data = array(
    'uid' => 0 // put user id here
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_ONBEHALF_GET_PROFILE, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Lấy thông tin OA**
```php
$accessToken = "put_access_token_here";
$params = [];
$response = $zalo->get(ZaloEndpoint::API_OA_ONBEHALF_GET_OA, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Lấy đoạn hội thoại giữa người quan tâm và OA**
```php
$accessToken = "put_access_token_here";
$data = array(
    'uid' => 0, // put user id here
    'offset' => 0,
    'count' => 10
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_ONBEHALF_CONVERSATION, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Lấy danh sách người quan tâm vừa chat với OA**
```php
$accessToken = "put_access_token_here";
$data = array(
    'offset' => 0,
    'count' => 10
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_ONBEHALF_RECENT_CHAT, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn dạng text**
```php
$accessToken = "put_access_token_here";
$data = array(
    'uid' => 0, // put user id here
    'message' => "put_message_here"
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_ONBEHALF_SEND_TEXT_MSG, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn dạng hình**
```php
$accessToken = "put_access_token_here";
$data = array(
    'uid' => 0, // put user id here
    'message' => "put_message_here",
    'imageid' => "put_image_id_here"
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_ONBEHALF_SEND_PHOTO_MSG, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn dạng liên kết**
```php
$accessToken = "put_access_token_here";
$firstLink = array('link' => 'put_url_here',
    'linktitle' => 'put_title_here',
    'linkdes' => 'put_description_here',
    'linkthumb' => 'put_thumbnail_url_here');
    
$secondLink = array('link' => 'put_url_here',
    'linktitle' => 'put_title_here',
    'linkdes' => 'put_description_here',
    'linkthumb' => 'put_thumbnail_url_here');
    
$data = array(
    'uid' => 0, // uid
    'links' => [$firstLink, $secondLink],
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_ONBEHALF_SEND_LINK_MSG, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn tương tác**
```php
$accessToken = "put_access_token_here";
$firstAction = array('action' => 'oa.query.show',
    'title' => 'put_title_here',
    'description' => 'put_description_here',
    'data' => 'reply_message_when_user_click',
    'href' => 'put_url_here',
    'thumb' => 'put_thumbnail_url_here');

$popupForSecondAction = array('title' => 'put_title_here',
    'desc' => 'put_description_here',
    'ok' => 'title_of_ok_button',
    'cancel' => 'title_of_cancel_button'
);
$secondAction = array('action' => 'oa.query.show',
    'title' => 'put_title_here',
    'description' => 'put_description_here',
    'data' => 'reply_message_when_user_click',
    'href' => 'put_url_here',
    'thumb' => 'put_thumbnail_url_here',
    'popup' => $popupForSecondAction
);
$data = array(
    'uid' => 0, // user id
    'actionlist' => [$firstAction, $secondAction],
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_ONBEHALF_SEND_ACTION_MSG, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Gửi tin nhắn dạng Gif**
```php
$accessToken = "put_access_token_here";
$data = array(
    'uid' => 0, // put user id here
    'imageid' => "put_image_id_here",
    'width' => 0, // put image width
    'height' => 0 // put image height
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_ONBEHALF_SEND_GIF_MSG, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Upload hình**
```php
$accessToken = "put_access_token_here";
$filePath = 'path_to_image';
$params = ['file' => new ZaloFile($filePath)];
$response = $zalo->post(ZaloEndpoint::API_OA_ONBEHALF_UPLOAD_PHOTO, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Upload hình Gif**
```php
$accessToken = "put_access_token_here";
$filePath = 'path_to_image';
$params = ['file' => new ZaloFile($filePath)];
$response = $zalo->post(ZaloEndpoint::API_OA_ONBEHALF_UPLOAD_GIF, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Trả lời tin nhắn dạng text**
```php
$accessToken = "put_access_token_here";
$data = array(
    'msgid' => "put_message_id_here",
    'message' => "put_message_id_here"
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_ONBEHALF_REPLY_TEXT_MSG, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Trả lời tin nhắn dạng hình**
```php
$accessToken = "put_access_token_here";
$data = array(
    'msgid' => "put_message_id_here",
    'imageid' => "put_image_id_here",
    'message' => "put_message_id_here"
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_ONBEHALF_REPLY_PHOTO_MSG, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Trả lời tin nhắn dạng liên kết**
```php
$accessToken = "put_access_token_here";
$firstLink = array('link' => 'put_link_here',
    'linktitle' => 'put_title_here',
    'linkdes' => 'put_description_here',
    'linkthumb' => 'put_thumbnail_link_here'
);
$secondLink = array('link' => 'https://developers.zalo.me/docs/',
    'linktitle' => 'Documents for zalo developers',
    'linkdes' => 'Zalo for developer comunity',
    'linkthumb' => 'https://cms.developers.zalo.me/wp-content/uploads/2017/06/Oauth2.jpg'
);
$data = array(
    'msgid' => "put_message_id_here",
    'links' => [$firstLink, $secondLink],
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_ONBEHALF_REPLY_LINK_MSG, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

## Store API
**Chỉnh sửa variation**
```php
$variation = array(
    'variationid' => "put_variation_id_here",
    'default' => 1, // 1 (enable), 2 (disable)
    'price' => 0.5,
    'name' => "put_variation_name_here",
    'status' => 2  // 2: Enable, 3: Disable
);
$data = array(
    'variation' => $variation
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_UPDATE_VARIATION, $params);
$result = $response->getDecodedBody();
```

**Thêm variation vào sản phẩm**
```php
$variationOne = array(
    'default' => 1, // 1 (enable), 2 (disable)
    'price' => 4,
    'name' => "put_variation_name_here",
    'attributes' => ["put_attribute_id_x1_here", "put_attribute_id_x2_here", "put_attribute_id_x3_here", "put_attribute_id_x4_here"]
);
$variationTwo = array(
    'default' => 2,
    'price' => 5,
    'name' => "put_variation_name_here",
    'attributes' => ["put_attribute_id_y1_here", "put_attribute_id_y2_here", "put_attribute_id_y3_here", "put_attribute_id_y4_here"]
);
$data = array(
    'productid' => "put_product_id_here",
    'variations' => [$variationOne, $variationTwo]
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_ADD_VARIATION, $params);
$result = $response->getDecodedBody();
```

**Lấy thông tin thuộc tính sản phẩm**
```php
$data = array(
    'attributeids' => ["put_attribute_id_1_here", "put_attribute_id_2_here"]
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_STORE_GET_ATTRIBUTE_INFO, $params);
$result = $response->getDecodedBody();
```

**Lấy danh sách thuộc tính sản phẩm**
```php
$data = array(
    'offset' => 0,
    'count' => 10
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_STORE_GET_SLICE_ATTRIBUTE, $params);
$result = $response->getDecodedBody();
```

**Chỉnh sửa thuộc tính sản phẩm**
```php
$data = array(
    'attributeid' => "put_attribute_id_here",
    'name' => "put_attribute_name_here"
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_UPDATE_ATTRIBUTE, $params);
$result = $response->getDecodedBody();
```

**Tạo thuộc tính sản phẩm**
```php
$data = array(
    'name' => "put_attribute_name_here",
    'type' => "put_attribute_type_id_here" // get from end point -> ZaloEndpoint::API_OA_STORE_GET_SLICE_ATTRIBUTE_TYPE
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_CREATE_ATTRIBUTE, $params);
$result = $response->getDecodedBody();
```

**Lấy danh sách kiểu thuộc tính**
```php
$data = array(
    'offset' => 0,
    'count' => 10
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_STORE_GET_SLICE_ATTRIBUTE_TYPE, $params);
$result = $response->getDecodedBody();
```

**Tạo sản phẩm**
```php
$cate = array('cateid' => 'put_your_cate_id_here');
$cates = [$cate];
$photo = array('id' => 'put_your_image_id_here');
$photos = [$photo];
$data = array(
    'cateids' => $cates,
    'name' => 'put_your_product_name_here',
    'desc' => 'put_your_description_here',
    'code' => 'put_your_code_number_here',
    'price' => 15000,
    'photos' => $photos,
    'display' => 'show', // show | hide
    'payment' => 2 // 2 - enable | 3 - disable
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_CREATE_PRODUCT, $params);
$result = $response->getDecodedBody(); // result
```

**Chỉnh sửa sản phẩm**
```php
$cate = array('cateid' => 'put_your_cate_id_here');
$cates = [$cate];
$photo = array('id' => 'put_your_image_id_here');
$photos = [$photo];
$productUpdate = array(
    'cateids' => $cates,
    'name' => 'put_your_product_name_here',
    'desc' => 'put_your_description_here',
    'code' => 'put_your_code_number_here',
    'price' => 15000,
    'photos' => $photos,
    'display' => 'show', // show | hide
    'payment' => 2 // 2 - enable | 3 - disable
);
$data = array(
    'productid' => 'put_your_product_id_here',
    'product' => $productUpdate
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_UPDATE_PRODUCT, $params);
$result = $response->getDecodedBody(); // result
```

**Xóa sản phẩm**
```php
$params = ['productid' => 'put_product_id_here'];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_REMOVE_PRODUCT, $params);
$result = $response->getDecodedBody(); // result
```

**Lấy thông tin sản phẩm**
```php
$data = array(
    'productid' => 'put_your_product_id_here'
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_STORE_GET_PRODUCT, $params);
$result = $response->getDecodedBody(); // result
```

**Danh sách sản phẩm**
```php
$data = array(
    'offset' => '0',
    'count' => '10'
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_STORE_GET_SLICE_PRODUCT, $params);
$result = $response->getDecodedBody(); // result
```

**Upload hình sản phẩm**
```php
$filePath = 'path_to_your_image';
$params = ['file' => new ZaloFile($filePath)];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_UPLOAD_PRODUCT_PHOTO, $params);
$result = $response->getDecodedBody(); // result
```

**Tạo danh mục**
```php
$data = array(
    'name' => 'put_your_category_name_here',
    'desc' => 'put_your_description_here',
    'photo' => 'put_your_photo_id_here',
    'status' => 0 // 0 - show | 1 - hide
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_CREATE_CATEGORY, $params);
$result = $response->getDecodedBody(); // result
```

**Chỉnh sửa danh mục**
```php
$categoryUpdate = array(
    'name' => 'put_your_category_name_here',
    'desc' => 'put_your_description_here',
    'photo' => 'put_your_photo_id_here',
    'status' => 1 // 0 - show | 1 - hide
);
$data = array(
    'categoryid' => 'put_your_category_id_here',
    'category' => $categoryUpdate
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_UPDATE_CATEGORY, $params);
$result = $response->getDecodedBody(); // result
```

**Danh sách danh mục**
```php
$data = array(
    'offset' => '0',
    'count' => '10'
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_STORE_GET_SLICE_CATEGORY, $params);
$result = $response->getDecodedBody(); // result
```

**Upload hình danh mục**
```php
$filePath = 'path_to_your_image';
$params = ['file' => new ZaloFile($filePath)];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_UPLOAD_CATEGORY_PHOTO, $params);
$result = $response->getDecodedBody(); // result
```

**Chỉnh sửa đơn hàng**
```php
$data = array(
    'orderid' => 'put_your_order_id_here',
    'status' => 2,
    'reason' => 'put_your_reason_here',
    'cancelReason' => 'put_your_reason_here'
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_UPDATE_ORDER, $params);
$result = $response->getDecodedBody(); // result
```

**Danh sách đơn hàng**
```php
$data = array(
    'offset' => 0,
    'count' => 10,
    'filter' => 0
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_STORE_GET_SLICE_ORDER, $params);
$result = $response->getDecodedBody(); // result
```

**Lấy thông tin đơn hàng**
```php
$params = ['orderid' => 'put_your_order_id_here'];
$response = $zalo->get(ZaloEndpoint::API_OA_STORE_GET_ORDER, $params);
$result = $response->getDecodedBody(); // result
```

## Store API Onbehalf
>Khi người dùng click vào link đăng nhập,
>Hệ thống sẽ thực hiện xử lý đăng nhập, và yêu cầu cấp quyền truy xuất thông tin của Offical Account,
>Sau khi người dùng đồng ý cấp quyền, hệ thống sẽ chuyển hướng về callback link đã đăng ký với app,
>Access token được hệ thống trả về và hiển thị trên callback link.

***Lấy link đăng nhập***
```php
$helper = $zalo -> getRedirectLoginHelper();
$callBackUrl = "www.put_your_call_backack_url_here.com";
$loginUrl = $helper->getLoginUrlByPage($callBackUrl); // This is login url
```

**Tạo sản phẩm**
```php
$accessToken = "put_access_token_here";
$cate = array('cateid' => 'put_your_cate_id_here');
$cates = [$cate];
$photo = array('id' => 'put_your_image_id_here');
$photos = [$photo];
$data = array(
    'cateids' => $cates,
    'name' => 'put_your_product_name_here',
    'desc' => 'put_your_description_here',
    'code' => 'put_your_code_number_here',
    'price' => 15000,
    'photos' => $photos,
    'display' => 'show', // show | hide
    'payment' => 2 // 2 - enable | 3 - disable
);
$product = array('product' => $data);
$params = ['data' => $product];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_ONBEHALF_CREATE_PRODUCT, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Chỉnh sửa sản phẩm**
```php
$accessToken = "put_access_token_here";
$cate = array('cateid' => 'put_your_cate_id_here');
$cates = [$cate];
$photo = array('id' => 'put_your_image_id_here');
$photos = [$photo];
$productUpdate = array(
    'cateids' => $cates,
    'name' => 'put_your_product_name_here',
    'desc' => 'put_your_description_here',
    'code' => 'put_your_code_number_here',
    'price' => 15000,
    'photos' => $photos,
    'display' => 'show', // show | hide
    'payment' => 2 // 2 - enable | 3 - disable
);
$data = array(
    'productid' => 'put_your_product_id_here',
    'product' => $productUpdate
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_ONBEHALF_UPDATE_PRODUCT, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Xóa sản phẩm**
```php
$accessToken = "put_access_token_here";
$data = array(
    'productid' => 'put_your_product_id_here'
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_ONBEHALF_REMOVE_PRODUCT, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Lấy thông tin sản phẩm**
```php
$accessToken = "put_access_token_here";
$data = array(
    'productid' => 'put_your_product_id_here'
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_STORE_ONBEHALF_GET_PRODUCT, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Danh sách sản phẩm**
```php
$accessToken = "put_access_token_here";
$data = array(
    'offset' => '0',
    'count' => '10'
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_STORE_ONBEHALF_GET_SLICE_PRODUCT, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Upload hình sản phẩm**
```php
$accessToken = "put_access_token_here";
$filePath = 'path_to_your_image';
$params = ['file' => new ZaloFile($filePath)];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_ONBEHALF_UPLOAD_PRODUCT_PHOTO, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Tạo danh mục**
```php
$accessToken = "put_access_token_here";
$data = array(
    'name' => 'put_your_category_name_here',
    'desc' => 'put_your_description_here',
    'photo' => 'put_your_photo_id_here',
    'status' => 0 // 0 - show | 1 - hide
);
$category = array('category' => $data);
$params = ['data' => $category];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_ONBEHALF_CREATE_CATEGORY, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Chỉnh sửa danh mục**
```php
$accessToken = "put_access_token_here";
$categoryUpdate = array(
    'name' => 'put_your_category_name_here',
    'desc' => 'put_your_description_here',
    'photo' => 'put_your_photo_id_here',
    'status' => 1 // 0 - show | 1 - hide
);
$data = array(
    'categoryid' => 'put_your_category_id_here',
    'category' => $categoryUpdate
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_ONBEHALF_UPDATE_CATEGORY, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Danh sách danh mục**
```php
$accessToken = "put_access_token_here";
$data = array(
    'offset' => '0',
    'count' => '10'
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_STORE_ONBEHALF_GET_SLICE_CATEGORY, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Upload hình danh mục**
```php
$accessToken = "put_access_token_here";
$filePath = 'path_to_your_image';
$params = ['file' => new ZaloFile($filePath)];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_ONBEHALF_UPLOAD_CATEGORY_PHOTO, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Chỉnh sửa đơn hàng**
```php
$accessToken = "put_access_token_here";
$data = array(
    'orderid' => 'put_your_order_id_here',
    'status' => 2,
    'reason' => 'put_your_reason_here',
    'cancelReason' => 'put_your_reason_here'
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_STORE_ONBEHALF_UPDATE_ORDER, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Danh sách đơn hàng**
```php
$accessToken = "put_access_token_here";
$data = array(
    'offset' => 0,
    'count' => 10,
    'filter' => 0
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_STORE_ONBEHALF_GET_SLICE_ORDER, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

**Lấy thông tin đơn hàng**
```php
$accessToken = "put_access_token_here";
$data = ['orderid' => 'put_your_order_id_here'];
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_STORE_ONBEHALF_GET_ORDER, $params, $accessToken);
$result = $response->getDecodedBody(); // result
```

## Article API
**Tạo bài viết**
```php
$cover = array(
    'coverType' => 1, //  0 (photo) | 1 (video)
    'coverView' => 1, // 1 (horizontal), 2 (vertical), 3 (square)
    'videoId' => 'put_your_video_id_here',
    'status' => 'show'
);
$actionLink = array(
    'type' => 2, // 0 (link to web), 1 (link to image), 2 (link to video), 3 (link to audio)
    'label' => 'put_label_here',
    'url' => 'https://www.youtube.com/watch?v=jp3xBWgii8A&list=RDjp3xBWgii8A'
);
$paragraphText = array(
    'type' => 0,
    'content' => 'put_content_here'
);
$paragraphImage = array(
    'type' => 1,
    'url' => 'https://upload.wikimedia.org/wikipedia/commons/7/71/2010-kodiak-bear-1.jpg',
    'caption' => 'put_caption_here',
    'width' => 500,
    'height' => 300
);
$paragraphVideo = array(
    'type' => 3,
    'url' => 'https://www.youtube.com/watch?v=jp3xBWgii8A&list=RDjp3xBWgii8A',
    'category' => 'youtube',
    'caption' => 'put_caption_here'
);
$relatedArticle = array(
    'id' => 'put_media_id_here' // related article
);
$media = array(
    'title' => 'put_title_here',
    'author' => 'put_author_here',
    'cover' => $cover,
    'desc' => 'put_description_here',
    'actionLink' => $actionLink,
    'body' => [$paragraphText, $paragraphImage, $paragraphVideo],
    'relatedMedias' => [$relatedArticle],
    'status' => 'show'
);
$params = ['media' => $media];
$response = $zalo->post(ZaloEndpoint::API_OA_ARTICLE_CREATE_MEDIA, $params);
$result = $response->getDecodedBody(); // result
```

**Lấy Id của bài viết**
```php
$params = ['token' => 'put_token_here'];
$response = $zalo->post(ZaloEndpoint::API_OA_ARTICLE_GET_MEDIA_ID, $params);
$result = $response->getDecodedBody(); // result
```

**Chỉnh sửa bài viết**
```php
$cover = array(
    'coverType' => 1, //  0 (photo) | 1 (video)
    'coverView' => 1, // 1 (horizontal), 2 (vertical), 3 (square)
    'videoId' => 'put_your_video_id_here',
    'status' => 'show'
);
$actionLink = array(
    'type' => 2, // 0 (link to web), 1 (link to image), 2 (link to video), 3 (link to audio)
    'label' => 'put_label_here',
    'url' => 'https://www.youtube.com/watch?v=jp3xBWgii8A&list=RDjp3xBWgii8A'
);
$paragraphText = array(
    'type' => 0,
    'content' => 'put_content_here'
);
$paragraphImage = array(
    'type' => 1,
    'url' => 'https://upload.wikimedia.org/wikipedia/commons/7/71/2010-kodiak-bear-1.jpg',
    'caption' => 'put_caption_here',
    'width' => 500,
    'height' => 300
);
$paragraphVideo = array(
    'type' => 3,
    'url' => 'https://www.youtube.com/watch?v=jp3xBWgii8A&list=RDjp3xBWgii8A',
    'category' => 'youtube',
    'caption' => 'put_caption_here'
);
$relatedArticle = array(
    'id' => 'put_media_id_here' // related article
);
$media = array(
    'title' => 'put_title_here',
    'author' => 'put_author_here',
    'cover' => $cover,
    'desc' => 'put_description_here',
    'actionLink' => $actionLink,
    'body' => [$paragraphText, $paragraphImage, $paragraphVideo],
    'relatedMedias' => [$relatedArticle],
    'status' => 'show'
);
$data = array(
    'mediaid' => 'put_media_id_here',
    'media' => $media
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_ARTICLE_UPDATE_MEDIA, $params);
$result = $response->getDecodedBody(); // result
```

**Xóa bài viết**
```php
$params = ['mediaid' => 'put_media_id_here'];
$response = $zalo->post(ZaloEndpoint::API_OA_ARTICLE_REMOVE_MEDIA, $params);
$result = $response->getDecodedBody(); // result
```

**Lấy danh sách bài viết**
```php
$data = array(
    'offset' => 0,
    'count' => 10
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_ARTICLE_GET_SLICE_MEDIA, $params);
$result = $response->getDecodedBody(); // result
```

**Broadcast bài viết**
```php
$target = array(
    'gender' => '1',
    'ages' => '1,2,3'
);
$firstArticle = array(
    'id' => 'put_article_id_here'
);
$secondArticle = array(
    'id' => 'put_article_id_here'
);
$data = array(
    'mediaIds' => [$firstArticle, $secondArticle],
    'target' => $target
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_ARTICLE_BROADCAST_MEDIA, $params);
$result = $response->getDecodedBody(); // result
```

**Upload video cho bài viết**
```php
$filePath = "path_to_video";
// Step 1 - Lấy link upload
$video = new ZaloFile($filePath);
$data = array(
    'videoName' => $video->getFileName(),
    'videoSize' => $video->getSize(),
);
$params = ['data' => $data];
$responseStepOne = $zalo->post(ZaloEndpoint::API_OA_ARTICLE_UPLOAD_VIDEO, $params);
$resultStepOne = $responseStepOne->getDecodedBody(); // result

// Step 2 - Upload file và lấy token
// get params from step 1
$uploadLink = $resultStepOne['data']['uploadLink'];
$timestamp = $resultStepOne['data']['time'];
$sig = $resultStepOne['data']['sig'];
$appId = $resultStepOne['data']['appId'];

$params = array(
    'appId' => $appId,
    'file' => $video,
    'timestamp' => $timestamp,
    'sig' => $sig
);
$responseStepTwo = $zalo->uploadVideo($uploadLink, $params);
$resultStepTwo = $responseStepTwo->getDecodedBody(); // result

// Step 3 - Lấy id của video
$token = $resultStepTwo['data']['token'];
$data = array(
    'token' => $token, // from step 2
    'videoName' => $video->getFileName(),
    'videoSize' => $video->getSize(),
    'time' => $timestamp, // from step 1
    'sig' => $sig // from step 1
);
$params = ['data' => $data];
$responseStepThree = $zalo->get(ZaloEndpoint::API_OA_ARTICLE_GET_VIDEO_ID, $params);
$resultStepThree = $responseStepThree->getDecodedBody();

// step 4 - Kiểm tra trạng thái của video
$videoId = $resultStepThree['data']['videoId'];
$data = array(
    'videoId' => $videoId // get from step 3
);
$params = ['data' => $data];
$responseStepFour = $zalo->get(ZaloEndpoint::API_OA_ARTICLE_GET_VIDEO_STATUS, $params);
$resultStepFour = $responseStepFour->getDecodedBody(); // result
```

**Lấy danh sách bài viết video**
```php
$data = array(
    'offset' => 0,
    'count' => 10
);
$params = ['data' => $data];
$response = $zalo->get(ZaloEndpoint::API_OA_ARTICLE_GET_SLICE_VIDEO, $params);
$result = $response->getDecodedBody();
```

**Chỉnh sửa bài viết video**
```php
$relatedArticle = array(
    'id' => 'put_related_article_id_here'
);
$media = array(
    'title' => 'update_video_article',
    'desc' => 'put_description_here',
    'avatar' => 'put_avatar_here',
    'videoId' => 'put_video_id_here',
    'relatedMedias' => [$relatedArticle],
    'status' => 'show'
);
$data = array(
    'mediaid' => 'put_media_id_here',
    'media' => $media
);
$params = ['data' => $data];
$response = $zalo->post(ZaloEndpoint::API_OA_ARTICLE_UPDATE_VIDEO, $params);
$result = $response->getDecodedBody();
```

**Tạo bài viết video**
```php
$relatedArticle = array(
    'id' => 'put_related_article_id_here'
);
$media = array(
    'title' => 'create_video_article',
    'desc' => 'put_description_here',
    'avatar' => 'put_avatar_here',
    'videoId' => 'put_video_id_here',
    'relatedMedias' => [$relatedArticle],
    'status' => 'show'
);
$params = ['media' => $media];
$response = $zalo->post(ZaloEndpoint::API_OA_ARTICLE_CREATE_VIDEO, $params);
$result = $response->getDecodedBody();
```

## Versioning

Current version is 1.0.3. We will update more features in next version.

## Authors

* **Zalo's Developer** 

## License

This project is licensed under the MIT License.
