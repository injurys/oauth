<?php
/**
 * @name: github 登陆
 * @Created by PhpStorm
 * @author: injurys
 * @file: GithubLogin.php
 * @Date: 2019/7/7 8:23
 */

namespace injurys\oauth\oauth;

use injurys\tools\HttpRequest;
use injurys\oauth\Exception\MessageException;

class SinaLogin extends Base
{

    public function __construct($info=[])
    {
        if(empty($info['app_key']))
            throw new MessageException('缺少配置参数：app_key', 400);
        if(empty($info['app_secret']))
            throw new MessageException('缺少配置参数：app_secret', 400);
        if(empty($info['redirect_uri']))
            throw new MessageException('缺少配置参数：redirect_uri', 400);
        $this->redirect_uri = $info['redirect_uri'];
        $this->client_id = $info['app_key'];
        $this->client_secret = $info['app_secret'];
    }

    /**
     * 获取 access_token
     * @return mixed
     */
    public function getAccessToken()
    {
        $code = isset($_GET["code"]) ? $_GET["code"] : false;
        if(empty($code))
            throw new MessageException('code获取失败', 400);

        $data = [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirect_uri,
        ];
        $url = 'https://api.weibo.com/oauth2/access_token?'.http_build_query($data);
        $result = json_decode(HttpRequest::post($url), true);
        if(!isset($result['access_token'], $result['uid']))
            throw new MessageException('access_token 获取失败', 400);

        return $result;
    }

    /**
     * 获取用户信息
     * @param array $access_token
     * @return mixed
     */
    public function getUserInfo($access_token=[])
    {
        if(!isset($result['access_token']))
            throw new MessageException('缺少主要参数：access_token', 400);

        if(empty($result['uid']) && empty($result['screen_name']))
            throw new MessageException('uid 与 screen_name最少要有一个参数', 400);

        $data = [
            'access_token'=>$access_token['access_token'],
        ];
        if(!empty($result['uid']))
            $date['uid'] = $access_token['uid'];
        elseif(!empty($result['screen_name']))
            $date['screen_name'] = $access_token['screen_name'];

        $url = 'https://api.weibo.com/2/users/show.json';
        $result = json_decode(HttpRequest::get($url, $data), true);
        return $result;
    }

    /**
     * @description: 登陆获取用户信息
     * @return mixed|string
     * @author: injurys
     * @updater:
     */
    public function login()
    {
        try{
            $result = $this->getAccessToken();
            return $this->getUserInfo($result);
        }catch (MessageException $e){
            return $e->getMessage();
        }
    }


}
