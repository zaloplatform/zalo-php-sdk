<?php

namespace Tests\Authentication;

use PHPUnit\Framework\TestCase;
use Zalo\Authentication\OAuth2Client;
use Zalo\Exceptions\ZaloSDKException;
use Zalo\Zalo;

class OAuth2ClientTest extends TestCase
{
    protected $appId = 'put_your_app_id_here';
    protected $appSecret = 'put_your_app_secret_here';
    protected $userCallbackUrl = 'put_your_user_callback_url_here';
    protected $oaCallbackUrl = 'put_your_oa_callback_url_here';

    protected $userCodeVerifier = 'put_your_user_code_verifier_here';
    protected $userCodeChallenge = 'put_your_user_code_challenge_here';
    protected $userState = 'put_your_user_state_here';
    protected $oauthCodeByUser = 'put_your_oauth_code_by_user_here';

    protected $oaCodeVerifier = 'put_your_oa_code_verifier_here';
    protected $oaCodeChallenge = 'put_your_oa_code_challenge_here';
    protected $oaState = 'put_your_oa_state_here';
    protected $oauthCodeByOA = 'put_your_oauth_code_by_oa_here';

    /**
     * @var OAuth2Client
     */
    protected $oauth2Client;
    
    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $config = [
                'app_id' => $this->appId,
                'app_secret' => $this->appSecret,
            ];
            $zalo = new Zalo($config);
            $this->oauth2Client = new OAuth2Client($zalo->getApp(), $zalo->getClient());
        } catch (\Exception $e) {
            print_r('Initial Test fail');
            exit;
        }
    }

    public function testGetAuthorizationUrlByUser()
    {
        $url = $this->oauth2Client->getAuthorizationUrlByUser($this->userCallbackUrl, $this->userCodeChallenge, $this->userState);
        print_r('AuthorizationUrl By User: ' . $url);
        if (isset($url)) {
            $this->assertTrue(true);
        } else {
            $this->fail('Zalo SDK getAuthorizationUrlByUser fail');
        }
    }

    public function testGetAuthorizationUrlByOA()
    {
        $url = $this->oauth2Client->getAuthorizationUrlByOA($this->oaCallbackUrl, $this->oaCodeChallenge, $this->oaState);
        print_r('AuthorizationUrl By OA: ' . $url);
        if (isset($url)) {
            $this->assertTrue(true);
        } else {
            $this->fail('Zalo SDK getAuthorizationUrlByOA fail');
        }
    }

    public function testGetZaloTokenByUser() {
        try {
            // test getZaloTokenFromCodeByUser
            $userZaloToken = $this->oauth2Client->getZaloTokenFromCodeByUser($this->oauthCodeByUser, $this->userCodeVerifier);

            // test getZaloTokenFromByUserRefreshToken
            $this->oauth2Client->getZaloTokenFromRefreshTokenByUser($userZaloToken->getRefreshToken());

            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }

    public function testGetZaloTokenByOA() {
        try {
            // test getZaloTokenFromCodeByOA
            $oaZaloToken = $this->oauth2Client->getZaloTokenFromCodeByOA($this->oauthCodeByOA, $this->oaCodeVerifier);

            // test getZaloTokenFromRefreshTokenByOA
            $this->oauth2Client->getZaloTokenFromRefreshTokenByOA($oaZaloToken->getRefreshToken());

            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }
}
