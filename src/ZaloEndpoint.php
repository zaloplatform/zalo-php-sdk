<?php
/**
 * Zalo © 2017
 *
 */

namespace Zalo;

/**
 * Class ZaloEndpoint
 *
 * @package Zalo
 */
class ZaloEndpoint {

    /**
     * @const 
     */
    const API_OAUTH_GET_ACCESS_TOKEN = '/access_token';

    /**
     * @const 
     */
    const API_GRAPH_ME = '/me';

    /**
     * @const 
     */
    const API_GRAPH_FRIENDS = '/me/friends';

    /**
     * @const 
     */
    const API_GRAPH_INVITABLE_FRIENDS = '/me/invitable_friends';

    /**
     * @const 
     */
    const API_GRAPH_POST_FEED = '/me/feed';

    /**
     * @const 
     */
    const API_GRAPH_APP_REQUESTS = '/apprequests';

    /**
     * @const 
     */
    const API_GRAPH_MESSAGE = '/me/message';

    /* --------------------------------------------------------------------------------------------------- */
    /**
     * @const 
     */
    const API_OA_SEND_FOLLOW_MSG = '/sendmessage/phone/invite';
    
    /**
     * @const 
     */
    const API_OA_GET_LIST_TAG = '/tag/gettagsofoa';
    
    /**
     * @const 
     */
    const API_OA_REMOVE_TAG = '/tag/rmtag';
    
    /**
     * @const 
     */
    const API_OA_REMOVE_USER_FROM_TAG = '/tag/rmfollowerfromtag';
    
    /**
     * @const 
     */
    const API_OA_TAG_USER = '/tag/tagfollower';
    
    /**
     * @const 
     */
    const API_OA_SEND_TEXT_MSG = '/sendmessage/text';

    /**
     * @const 
     */
    const API_OA_SEND_PHOTO_MSG = '/sendmessage/image';

    /**
     * @const 
     */
    const API_OA_SEND_LINK_MSG = '/sendmessage/links';

    /**
     * @const 
     */
    const API_OA_SEND_ACTION_MSG = '/sendmessage/actionlist';

    /**
     * @const 
     */
    const API_OA_SEND_STICKER_MSG = '/sendmessage/sticker';

    /**
     * @const 
     */
    const API_OA_SEND_GIF_MSG = '/sendmessage/gif';

    /**
     * @const 
     */
    const API_OA_UPLOAD_PHOTO = '/upload/image';

    /**
     * @const 
     */
    const API_OA_UPLOAD_GIF = '/upload/gif';

    /**
     * @const 
     */
    const API_OA_GET_PROFILE = '/getprofile';

    /**
     * @const 
     */
    const API_OA_GET_MSG_STATUS = '/getmessagestatus';
    
    /**
     * @const 
     */
    const API_OA_SEND_CUSTOMER_CARE_MSG = '/sendmessage/cs';
    
    /**
     * @const 
     */
    const API_OA_SEND_CUSTOMER_CARE_MSG_BY_PHONE = '/sendmessage/phone/cs';
    
    /**
     * @const 
     */
    const API_OA_REPLY_TEXT_MSG = '/sendmessage/reply/text';

    /**
     * @const 
     */
    const API_OA_REPLY_PHOTO_MSG = '/sendmessage/reply/image';

    /**
     * @const 
     */
    const API_OA_REPLY_LINK_MSG = '/sendmessage/reply/links';

    /**
     * @const 
     */
    const API_OA_CREATE_QR_CODE = '/qrcode';

    /* --------------------------------------------------------------------------------------------------- */

    /**
     * @const 
     */
    const API_OA_ONBEHALF_GET_PROFILE = '/onbehalf/getprofile';

    /**
     * @const 
     */
    const API_OA_ONBEHALF_GET_OA = '/onbehalf/getoa';

    /**
     * @const 
     */
    const API_OA_ONBEHALF_CONVERSATION = '/onbehalf/conversation';

    /**
     * @const 
     */
    const API_OA_ONBEHALF_RECENT_CHAT = '/onbehalf/listrecentchat';

    /**
     * @const 
     */
    const API_OA_ONBEHALF_UPLOAD_PHOTO = '/onbehalf/upload/image';

    /**
     * @const 
     */
    const API_OA_ONBEHALF_UPLOAD_GIF = '/onbehalf/upload/gif';

    /**
     * @const 
     */
    const API_OA_ONBEHALF_SEND_TEXT_MSG = '/onbehalf/sendmessage/text';

    /**
     * @const 
     */
    const API_OA_ONBEHALF_SEND_PHOTO_MSG = '/onbehalf/sendmessage/image';

    /**
     * @const 
     */
    const API_OA_ONBEHALF_SEND_LINK_MSG = '/onbehalf/sendmessage/links';

    /**
     * @const 
     */
    const API_OA_ONBEHALF_SEND_ACTION_MSG = '/onbehalf/sendmessage/actionlist';

    /**
     * @const 
     */
    const API_OA_ONBEHALF_SEND_STICKER_MSG = '/onbehalf/sendmessage/sticker';

    /**
     * @const 
     */
    const API_OA_ONBEHALF_SEND_GIF_MSG = '/onbehalf/sendmessage/gif';

    /**
     * @const 
     */
    const API_OA_ONBEHALF_REPLY_TEXT_MSG = '/onbehalf/sendmessage/reply/text';

    /**
     * @const 
     */
    const API_OA_ONBEHALF_REPLY_PHOTO_MSG = '/onbehalf/sendmessage/reply/image';

    /**
     * @const 
     */
    const API_OA_ONBEHALF_REPLY_LINK_MSG = '/onbehalf/sendmessage/reply/links';

    /* --------------------------------------------------------------------------------------------------- */

    /**
     * @const 
     */
    const API_OA_STORE_UPDATE_VARIATION = '/store/product/updatevariation';
    
    /**
     * @const 
     */
    const API_OA_STORE_ADD_VARIATION = '/store/product/addvariation';
    
    /**
     * @const 
     */
    const API_OA_STORE_GET_SLICE_ATTRIBUTE = '/store/product/getattrofoa';
    
    /**
     * @const 
     */
    const API_OA_STORE_UPDATE_ATTRIBUTE = '/store/product/updateattr';
    
    /**
     * @const 
     */
    const API_OA_STORE_CREATE_ATTRIBUTE = 'store/product/createattr';
    
    /**
     * @const 
     */
    const API_OA_STORE_GET_SLICE_ATTRIBUTE_TYPE = '/store/product/getattrtypeofoa';
    
    /**
     * @const 
     */
    const API_OA_STORE_GET_ATTRIBUTE_INFO = '/store/product/mgetattr';
    
    /**
     * @const 
     */
    const API_OA_STORE_CREATE_PRODUCT = '/store/product/create';

    /**
     * @const 
     */
    const API_OA_STORE_UPDATE_PRODUCT = '/store/product/update';

    /**
     * @const 
     */
    const API_OA_STORE_REMOVE_PRODUCT = '/store/product/remove';

    /**
     * @const 
     */
    const API_OA_STORE_GET_PRODUCT = '/store/product/getproduct';

    /**
     * @const 
     */
    const API_OA_STORE_GET_SLICE_PRODUCT = '/store/product/getproductofoa';

    /**
     * @const 
     */
    const API_OA_STORE_UPLOAD_PRODUCT_PHOTO = '/store/upload/productphoto';

    /**
     * @const 
     */
    const API_OA_STORE_CREATE_CATEGORY = '/store/category/create';

    /**
     * @const 
     */
    const API_OA_STORE_UPDATE_CATEGORY = '/store/category/update';

    /**
     * @const 
     */
    const API_OA_STORE_GET_SLICE_CATEGORY = '/store/category/getcategoryofoa';

    /**
     * @const 
     */
    const API_OA_STORE_UPLOAD_CATEGORY_PHOTO = '/store/upload/categoryphoto';

    /**
     * @const 
     */
    const API_OA_STORE_UPDATE_ORDER = '/store/order/update';

    /**
     * @const 
     */
    const API_OA_STORE_GET_SLICE_ORDER = '/store/order/getorderofoa';

    /**
     * @const 
     */
    const API_OA_STORE_GET_ORDER = '/store/order/getorder';

    /**
     * @const 
     */
    const API_OA_STORE_UPDATE_SHOP = '/store/updateshop';

    /* --------------------------------------------------------------------------------------------------- */

    /**
     * @const 
     */
    const API_OA_STORE_ONBEHALF_CREATE_PRODUCT = '/store/onbehalf/product/create';

    /**
     * @const 
     */
    const API_OA_STORE_ONBEHALF_UPDATE_PRODUCT = '/store/onbehalf/product/update';

    /**
     * @const 
     */
    const API_OA_STORE_ONBEHALF_REMOVE_PRODUCT = '/store/onbehalf/product/remove';

    /**
     * @const 
     */
    const API_OA_STORE_ONBEHALF_GET_PRODUCT = '/store/onbehalf/product/getproduct';

    /**
     * @const 
     */
    const API_OA_STORE_ONBEHALF_GET_SLICE_PRODUCT = '/store/onbehalf/product/getproductofoa';

    /**
     * @const 
     */
    const API_OA_STORE_ONBEHALF_UPLOAD_PRODUCT_PHOTO = '/store/onbehalf/upload/productphoto';

    /**
     * @const 
     */
    const API_OA_STORE_ONBEHALF_CREATE_CATEGORY = '/store/onbehalf/category/create';

    /**
     * @const 
     */
    const API_OA_STORE_ONBEHALF_UPDATE_CATEGORY = '/store/onbehalf/category/update';

    /**
     * @const 
     */
    const API_OA_STORE_ONBEHALF_GET_SLICE_CATEGORY = '/store/onbehalf/category/getcategoryofoa';

    /**
     * @const 
     */
    const API_OA_STORE_ONBEHALF_UPLOAD_CATEGORY_PHOTO = '/store/onbehalf/upload/categoryphoto';

    /**
     * @const 
     */
    const API_OA_STORE_ONBEHALF_UPDATE_ORDER = '/store/onbehalf/order/update';

    /**
     * @const 
     */
    const API_OA_STORE_ONBEHALF_GET_SLICE_ORDER = '/store/onbehalf/order/getorderofoa';

    /**
     * @const 
     */
    const API_OA_STORE_ONBEHALF_GET_ORDER = '/store/onbehalf/order/getorder';

    /**
     * @const 
     */
    const API_OA_STORE_ONBEHALF_UPDATE_SHOP = '/store/onbehalf/updateshop';

    /* --------------------------------------------------------------------------------------------------- */

    /**
     * @const 
     */
    const API_OA_ARTICLE_CREATE_VIDEO = '/media/video/create';
    /**
     * @const 
     */
    const API_OA_ARTICLE_UPDATE_VIDEO = '/media/video/update';
    /**
     * @const 
     */
    const API_OA_ARTICLE_GET_SLICE_VIDEO = '/media/video/getslice';
    /**
     * @const 
     */
    const UPLOAD_VIDEO_URL = 'http://upload.media.zapps.vn/upload';
    /**
     * @const 
     */
    const API_OA_ARTICLE_UPLOAD_VIDEO = '/media/upload/video';
    /**
     * @const 
     */
    const API_OA_ARTICLE_GET_VIDEO_ID = '/media/getvideoid';
    /**
     * @const 
     */
    const API_OA_ARTICLE_GET_VIDEO_STATUS = '/media/getvideostatus';
    /**
     * @const 
     */
    const API_OA_ARTICLE_CREATE_MEDIA = '/media/create';
    /**
     * @const 
     */
    const API_OA_ARTICLE_GET_MEDIA_ID = '/media/verify';
    /**
     * @const 
     */
    const API_OA_ARTICLE_UPDATE_MEDIA = '/media/update';
    /**
     * @const 
     */
    const API_OA_ARTICLE_REMOVE_MEDIA = '/media/remove';
    /**
     * @const 
     */
    const API_OA_ARTICLE_GET_SLICE_MEDIA = '/media/getslice';
    /**
     * @const 
     */
    const API_OA_ARTICLE_BROADCAST_MEDIA = '/broadcast/medias';
}
