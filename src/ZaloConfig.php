<?php
/**
 * 
 * @author : linhndh
 */

namespace Zalo;
/**
 * 
 */
class ZaloConfig {

    /** @var self */
    protected static $instance;
    
    /** config your app id here */
    const ZALO_APP_ID_CFG = "960183778787479128";
    
    /** config your app secret key here */
    const ZALO_APP_SECRET_KEY_CFG = "coLo667bj6S0LBd57TOU";
    
    /** config your offical account id here */
    const ZALO_OA_ID_CFG = "2491302944280861639";
    
    /** config your offical account secret key here */
    const ZALO_OA_SECRET_KEY_CFG = "Tb418kOM4WJLQzwYGqqw";

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

    /**
     * Get zalo sdk config
     * @return []
     */
    public function getConfig() {
        return [
            'app_id' => static::ZALO_APP_ID_CFG,
            'app_secret' => static::ZALO_APP_SECRET_KEY_CFG,
            'oa_id' => static::ZALO_OA_ID_CFG,
            'oa_secret' => static::ZALO_OA_SECRET_KEY_CFG
        ];
    }

}
