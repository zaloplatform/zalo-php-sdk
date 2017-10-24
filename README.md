# Zalo SDK for PHP (v1.0.0)

[![N|Solid](https://developers.zalo.me/web/static/prodution/images/logo.png)](https://developers.zalo.me/)

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
$zalo = new Zalo(ZaloConfig::getInstance()->getConfig());
```

## Social API

***Get Login Url***
```php
$helper = $zalo -> getRedirectLoginHelper();
$callBackUrl = "www.put_your_call_backack_url_here.com";
$loginUrl = $helper->getLoginUrl($callBackUrl); // This is login url
```

**Get AccessToken**
>When user click to login url,

>server will process that request and redirect to your callback url with oauth code,

>put this method to your callback url to get oauth code and access token.

[![N|Solid](http://cms.developer.zapps.vn/wp-content/uploads/2017/06/Oauth2.jpg)](https://developers.zalo.me/docs/api/social-api-4)

```php
$callBackUrl = "www.put_your_call_backack_url_here.com";
$oauthCode = isset($_GET['code']) ? $_GET['code'] : "THIS NOT CALLBACK PAGE !!!"; // get oauthoauth code from url params
$accessToken = $helper->getAccessToken($callBackUrl); // get access token
if ($accessToken != null) {
    $expires = $accessToken->getExpiresAt(); // get expires time
}
```

**Get User Information**
```php
$accessToken = 'put_your_access_token_here';
$params = [];
$response = $zalo->get('/me', $accessToken, $params, Zalo::API_TYPE_GRAPH);
$result = $response->getDecodedBody(); // result
```

**Get Friends List**
```php
$accessToken = 'put_your_access_token_here';
$params = ['offset' => 0, 'limit' => 10, 'fields' => "id, name"];
$response = $zalo->get('/me/friends', $accessToken, $params, Zalo::API_TYPE_GRAPH);
$result = $response->getDecodedBody(); // result
```

**Get Invitable Friends**
```php
$accessToken = 'put_your_access_token_here';
$params = ['offset' => 0, 'limit' => 10, 'fields' => "id, name"];
$response = $zalo->get('/me/invitable_friends', $accessToken, $params, Zalo::API_TYPE_GRAPH);
$result = $response->getDecodedBody(); // result
```

**Post feed**
```php
$accessToken = 'put_your_access_token_here';
$params = ['message' => 'put_your_text_here', 'link' => 'put_your_link_here'];
$response = $zalo->post('/me/feed', $accessToken, $params, Zalo::API_TYPE_GRAPH);
$result = $response->getDecodedBody(); // result
```

**Send Invite To User To Use The App**
```php
$accessToken = 'put_your_access_token_here';
$params = ['message' => 'put_your_message_here', 'to' => 'put_user_id_receive_here'];
$response = $zalo->post('/apprequests', $accessToken, $params, Zalo::API_TYPE_GRAPH);
$result = $response->getDecodedBody(); // result
```

**Send A Message To Friends**
```php
$accessToken = 'put_your_access_token_here';
$params = ['message' => 'put_your_message_here', 'to' => 'put_user_id_receive_here', 'link' => 'put_your_link_here'];
$response = $zalo->post('/me/message', $accessToken, $params, Zalo::API_TYPE_GRAPH);
$result = $response->getDecodedBody(); // result
```

## Official Account Open API
**Send text message**
```php
$data = array(
    'uid' => 1785179753369910605, // user id
    'message' => 'put_your_text_message_here'
);
$params = ['data' => $data];
$response = $this->zalo->post('/sendmessage/text', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Send image message**
```php
 $data = array(
    'uid' => 1785179753369910605, // user id
    'imageid' => 'put_your_uploaded_image_id',
    'message' => 'put_your_text_message_here'
);
$params = ['data' => $data];
$response = $this->zalo->post('/sendmessage/image', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Send link message**
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
$response = $this->zalo->post('/sendmessage/links', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Send interaction message**
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
$response = $this->zalo->post('/sendmessage/actionlist', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Get profile user followed OA**
```php
$params = ['uid' => 1785179753369910605]; // put user id here
$response = $this->zalo->get('/getprofile', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Upload image**
```php
$filePath = 'path_to_image';
$params = ['file' => new ZaloFile($filePath)];
$response = $this->zalo->post('/upload/image', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Get message status**
```php
$params = ['msgid' => 'put_your_message_id_here'];
$response = $this->zalo->get('/getmessagestatus', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Send customer care message**
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
$response = $this->zalo->post('/sendmessage/cs', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Send customer care message by phone number**
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
$response = $this->zalo->post('/sendmessage/phone/cs', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

## Store API
**Create product**
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
$response = $this->zalo->post('/store/product/create', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Update product**
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
$response = $this->zalo->post('/store/product/update', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Get product infomation**
```php
$data = array(
    'productid' => 'put_your_product_id_here'
);
$params = ['data' => $data];
$response = $this->zalo->get('/store/product/getproduct', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Get list product**
```php
$data = array(
    'offset' => '0',
    'count' => '10'
);
$params = ['data' => $data];
$response = $zalo->get('/store/product/getproductofoa', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Upload product image**
```php
$filePath = 'path_to_your_image';
$params = ['file' => new ZaloFile($filePath)];
$response = $zalo->post('/store/upload/productphoto', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Create category**
```php
$data = array(
    'name' => 'put_your_category_name_here',
    'desc' => 'put_your_description_here',
    'photo' => 'put_your_photo_id_here',
    'status' => 0 // 0 - show | 1 - hide
);
$params = ['data' => $data];
$response = $zalo->post('/store/category/create', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Update category**
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
$response = $zalo->post('/store/category/update', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Get list category**
```php
$data = array(
    'offset' => '0',
    'count' => '10'
);
$params = ['data' => $data];
$response = $zalo->get('/store/category/getcategoryofoa', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Upload image category**
```php
$filePath = 'path_to_your_image';
$params = ['file' => new ZaloFile($filePath)];
$response = $zalo->post('/store/upload/categoryphoto', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Update order**
```php
$data = array(
    'orderid' => 'put_your_order_id_here',
    'status' => 2,
    'reason' => 'put_your_reason_here',
    'cancelReason' => 'put_your_reason_here'
);
$params = ['data' => $data];
$response = $zalo->post('/store/order/update', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Get list order**
```php
$data = array(
    'offset' => 0,
    'count' => 10,
    'filter' => 0
);
$params = ['data' => $data];
$response = $this->zalo->get('/store/order/getorderofoa', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

**Get order infomation**
```php
$params = ['orderid' => 'put_your_order_id_here'];
$response = $this->zalo->get('/store/order/getorder', null, $params, Zalo::API_TYPE_OA);
$result = $response->getDecodedBody(); // result
```

## Versioning

Current version is 1.0.0. We will update more features in next version.

## Authors

* **Zalo's Developer** 

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
