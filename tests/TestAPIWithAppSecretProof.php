<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Zalo\Builder\MessageBuilder;
use Zalo\Zalo;
use Zalo\ZaloEndPoint;

class TestAPIWithAppSecretProof extends TestCase
{

    /**
     * @var string
     */
    protected $userAccessToken = 'put your user access token';
    protected $oaAccessToken = 'put your oa access token';

    /**
     * @var Zalo
     */
    protected $zalo;

    public function __construct()
    {
        try {
            $config = array(
                'app_id' => 'put your app id',
                'app_secret' => 'put your app secret key'
            );

            $this->zalo = new Zalo($config);
            $this->zalo->setUseAppSecretProof(true);
        } catch (\Exception $e) {
            print_r('Initial test fail ' . $e->getMessage());
            exit;
        }
    }

    public function testSocialAPI()
    {
        try {
            $params = ['fields' => 'id,name,picture'];
            $response = $this->zalo->get(ZaloEndPoint::API_GRAPH_ME, $this->userAccessToken, $params);
            $result = $response->getDecodedBody();
            print_r('testSocialAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testOpenAPI()
    {
        try {
            $msgBuilder = new MessageBuilder(MessageBuilder::MSG_TYPE_TXT);
            $msgBuilder->withUserId('user_id');
            $msgBuilder->withText('Message Text');

            $msgText = $msgBuilder->build();

            // send request
            $response = $this->zalo->post(ZaloEndPoint::API_OA_SEND_CONSULTATION_MESSAGE_V3, $this->oaAccessToken, $msgText);
            $result = $response->getDecodedBody();
            print_r('testOpenAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }
}