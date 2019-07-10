<?php
/**
 * @name: github 登陆
 * @Created by PhpStorm
 * @author: injurys
 * @file: GithubLogin.php
 * @Date: 2019/7/7 8:23
 */

namespace injurys\third\login;

use injurys\tools\HttpRequest;
use injurys\third\Exception\MessageException;

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
     * @description: 获取 access_token
     * @return mixed
     * @throws MessageException
     * @author: injurys
     * @updater:
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
     * @description:  获取用户信息
     * @param array $info
     * @return mixed
     * @throws MessageException
     * @author: injurys
     * @updater:
     */
    public function getUserInfo($info=[])
    {
        if(!isset($info['access_token']))
            throw new MessageException('缺少主要参数：access_token', 400);

        if(empty($info['uid']) && empty($info['screen_name']))
            throw new MessageException('uid 与 screen_name最少要有一个参数', 400);

        $data = [
            'access_token'=>$info['access_token'],
        ];
        if(!empty($info['uid']))
            $data['uid'] = $info['uid'];
        elseif(!empty($result['screen_name']))
            $data['screen_name'] = $info['screen_name'];

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
