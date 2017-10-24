# Zalo SDK for PHP (v1)

[![N|Solid](https://developers.zalo.me/web/static/prodution/images/logo.png)](https://developers.zalo.me/)

## Installation

The Zalo PHP SDK can be installed with [Composer](https://getcomposer.org/). Run this command:

```sh
composer require hoanglinh91/composer-zsdk-library
```

## How To Use

**Import Autoload** 
```php
require_once __DIR__ . '/vendor/autoload.php';
```

**Configuration**
ZaloConfig.php
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
$app_id = 'put_your_app_id_here';
$app_secret = 'put_your_app_secret_here';
$zalo = new Zalo(ZaloConfig::getInstance()->getConfig());
```

**Get Login Url**
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
$response = $this->zalo->get('/me', $accessToken, $params, Zalo::API_TYPE_GRAPH);
$result = $response->getDecodedBody(); // result
```

**Get Friends List**
```php
$accessToken = 'put_your_access_token_here';
$params = ['offset' => 0, 'limit' => 10, 'fields' => "id, name"];
$response = $this->zalo->get('/me/friends', $accessToken, $params, Zalo::API_TYPE_GRAPH);
$result = $response->getDecodedBody(); // result
```

**Get Invitable Friends**
```php
$accessToken = 'put_your_access_token_here';
$params = ['offset' => 0, 'limit' => 10, 'fields' => "id, name"];
$response = $this->zalo->get('/me/invitable_friends', $accessToken, $params, Zalo::API_TYPE_GRAPH);
$result = $response->getDecodedBody(); // result
```

**Post feed**
```php
$accessToken = 'put_your_access_token_here';
$params = ['message' => 'put_your_text_here', 'link' => 'put_your_link_here'];
$response = $this->zalo->post('/me/feed', $accessToken, $params, Zalo::API_TYPE_GRAPH);
$result = $response->getDecodedBody(); // result
```

**Send Invite To User To Use The App**
```php
$accessToken = 'put_your_access_token_here';
$params = ['message' => 'put_your_message_here', 'to' => 'put_user_id_receive_here'];
$response = $this->zalo->post('/apprequests', $accessToken, $params, Zalo::API_TYPE_GRAPH);
$result = $response->getDecodedBody(); // result
```

**Send A Message To Friends**
```php
$accessToken = 'put_your_access_token_here';
$params = ['message' => 'put_your_message_here', 'to' => 'put_user_id_receive_here', 'link' => 'put_your_link_here'];
$response = $this->zalo->post('/me/message', $accessToken, $params, Zalo::API_TYPE_GRAPH);
$result = $response->getDecodedBody(); // result
```

## Versioning

Current version is 1.0.0. We will update more features in next version.

## Authors

* **LinhNDH** 

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
