<?php
/**
 * Zalo © 2017
 *
 */

namespace Zalo;

use Zalo\Zalo;
use Zalo\ZaloEndpoint;

/**
 * Class ZaloAPIManager
 *
 * @package Zalo
 */
class ZaloAPIManager {

    protected $MapEndpointApi;

    /** @var self */
    protected static $instance;

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

    public function __construct() {
        $this->MapEndpointApi = array(
            ZaloEndpoint::API_OAUTH_GET_ACCESS_TOKEN => Zalo::API_TYPE_AUTHEN,
            ZaloEndpoint::API_GRAPH_APP_REQUESTS => Zalo::API_TYPE_GRAPH,
            ZaloEndpoint::API_GRAPH_FRIENDS => Zalo::API_TYPE_GRAPH,
            ZaloEndpoint::API_GRAPH_INVITABLE_FRIENDS => Zalo::API_TYPE_GRAPH,
            ZaloEndpoint::API_GRAPH_ME => Zalo::API_TYPE_GRAPH,
            ZaloEndpoint::API_GRAPH_MESSAGE => Zalo::API_TYPE_GRAPH,
            ZaloEndpoint::API_GRAPH_POST_FEED => Zalo::API_TYPE_GRAPH,
            ZaloEndpoint::API_OA_SEND_FOLLOW_MSG => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_GET_LIST_TAG => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_REMOVE_TAG => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_REMOVE_USER_FROM_TAG => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_TAG_USER => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_CREATE_QR_CODE => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_GET_MSG_STATUS => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_GET_PROFILE => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_SEND_CUSTOMER_CARE_MSG => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_SEND_CUSTOMER_CARE_MSG_BY_PHONE => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_REPLY_LINK_MSG => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_REPLY_PHOTO_MSG => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_REPLY_TEXT_MSG => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_SEND_ACTION_MSG => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_SEND_GIF_MSG => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_SEND_LINK_MSG => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_SEND_PHOTO_MSG => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_SEND_STICKER_MSG => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_SEND_TEXT_MSG => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_UPLOAD_GIF => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_UPLOAD_PHOTO => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_CREATE_ATTRIBUTE => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_UPDATE_ATTRIBUTE => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_GET_SLICE_ATTRIBUTE => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_GET_SLICE_ATTRIBUTE_TYPE => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_UPDATE_VARIATION => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_ADD_VARIATION => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_GET_ATTRIBUTE_INFO => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_CREATE_PRODUCT => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_GET_ORDER => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_GET_PRODUCT => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_GET_SLICE_CATEGORY => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_GET_SLICE_ORDER => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_GET_SLICE_PRODUCT => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_REMOVE_PRODUCT => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_UPDATE_CATEGORY => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_UPDATE_ORDER => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_UPDATE_PRODUCT => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_UPDATE_SHOP => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_UPLOAD_CATEGORY_PHOTO => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_STORE_UPLOAD_PRODUCT_PHOTO => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_ONBEHALF_CONVERSATION => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_ONBEHALF_GET_OA => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_ONBEHALF_GET_PROFILE => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_ONBEHALF_RECENT_CHAT => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_ONBEHALF_REPLY_LINK_MSG => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_ONBEHALF_REPLY_PHOTO_MSG => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_ONBEHALF_REPLY_TEXT_MSG => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_ONBEHALF_SEND_ACTION_MSG => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_ONBEHALF_SEND_GIF_MSG => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_ONBEHALF_SEND_LINK_MSG => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_ONBEHALF_SEND_PHOTO_MSG => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_ONBEHALF_SEND_STICKER_MSG => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_ONBEHALF_SEND_TEXT_MSG => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_ONBEHALF_UPLOAD_GIF => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_ONBEHALF_UPLOAD_PHOTO => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_STORE_ONBEHALF_CREATE_CATEGORY => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_STORE_ONBEHALF_CREATE_PRODUCT => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_STORE_ONBEHALF_GET_ORDER => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_STORE_ONBEHALF_GET_PRODUCT => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_STORE_ONBEHALF_GET_SLICE_CATEGORY => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_STORE_ONBEHALF_GET_SLICE_ORDER => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_STORE_ONBEHALF_GET_SLICE_PRODUCT => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_STORE_ONBEHALF_REMOVE_PRODUCT => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_STORE_ONBEHALF_UPDATE_CATEGORY => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_STORE_ONBEHALF_UPDATE_ORDER => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_STORE_ONBEHALF_UPDATE_PRODUCT => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_STORE_ONBEHALF_UPDATE_SHOP => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_STORE_ONBEHALF_UPLOAD_CATEGORY_PHOTO => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::API_OA_STORE_ONBEHALF_UPLOAD_PRODUCT_PHOTO => Zalo::API_TYPE_OA_ONBEHALF,
            ZaloEndpoint::UPLOAD_VIDEO_URL => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_ARTICLE_UPLOAD_VIDEO => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_ARTICLE_GET_VIDEO_ID => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_ARTICLE_GET_VIDEO_STATUS => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_ARTICLE_CREATE_MEDIA => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_ARTICLE_GET_MEDIA_ID => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_ARTICLE_UPDATE_MEDIA => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_ARTICLE_REMOVE_MEDIA => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_ARTICLE_GET_SLICE_MEDIA => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_ARTICLE_BROADCAST_MEDIA => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_ARTICLE_CREATE_VIDEO => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_ARTICLE_UPDATE_VIDEO => Zalo::API_TYPE_OA,
            ZaloEndpoint::API_OA_ARTICLE_GET_SLICE_VIDEO => Zalo::API_TYPE_OA
        );
    }

    public function getMapEndPoint() {
        return $this->MapEndpointApi;
    }

}
