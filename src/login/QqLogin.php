<?php
/**
 * @name: github 登陆
 * @Created by PhpStorm
 * @author: injurys
 * @file: GithubLogin.php
 * @Date: 2019/7/7 8:23
 */

namespace third\oauth\login;

use injurys\tools\HttpRequest;
use third\oauth\Exception\MessageException;

class QqLogin extends Base
{

    public function __construct($info=[])
    {
        if(empty($info['app_id']))
            throw new MessageException('缺少配置参数：app_id', 400);
        if(empty($info['app_key']))
            throw new MessageException('缺少配置参数：app_key', 400);
        if(empty($info['redirect_uri']))
            throw new MessageException('缺少配置参数：redirect_uri', 400);
        $this->redirect_uri = $info['redirect_uri'];
        $this->client_id = $info['app_id'];
        $this->client_secret = $info['app_key'];
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
            throw new MessageException('code 获取失败', 400);

        $data = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'code' => $code,
            'redirect_uri' => $this->redirect_uri
        ];

        $url = 'https://graph.qq.com/oauth2.0/token';
        parse_str(HttpRequest::get($url, $data), $result);

        if(!isset($result['access_token']))
            throw new MessageException('access_token 获取失败', 400);

        return $result['access_token'];
    }

    /**
     * @description: 获取 open_id union_id
     * @param string $access_token
     * @return array
     * @throws MessageException
     * @author: injurys
     * @updater:
     */
    public function getOpenid($access_token='')
    {
        if(empty($access_token))
            throw new MessageException('缺少参数：access_token', 400);

        try{
            $url = "https://graph.qq.com/oauth2.0/me?access_token={$access_token}&unionid=1";
            $result = HttpRequest::get($url);
            $result = json_decode(trim(substr($result, 9), " );\n"), true);

            return [
                'openid' => $result['openid'],
                'union_id' => $result['unionid'],
                'access_token' => $access_token
            ];
        }catch (\Exception $e){
            throw new MessageException('open_id 获取失败', 400);
        }
    }

    /**
     * @description:  获取用户信息
     * @param array $info
     * @return array
     * @throws MessageException
     * @author: injurys
     * @updater:
     */
    public function getUserInfo($info=[])
    {
        if(empty($info['access_token']))
            throw new MessageException('用户信息获取失败，缺少参数：access_token', 400);
        if(empty($info['openid']))
            throw new MessageException('用户信息获取失败，缺少参数：openid', 400);

        $data = [
            'access_token' => $info['access_token'],
            'oauth_consumer_key' => $this->client_id,
            'openid' => $info['openid']
        ];
        $url = "https://graph.qq.com/user/get_user_info?".http_build_query($data);
        $result = json_decode(HttpRequest::get($url), true);
        if($result['ret'] != 0)
            throw new MessageException($result['msg'], 400);

        return array_merge($result, $data);
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
            $access_token = $this->getAccessToken();
            $indo = $this->getOpenid($access_token);
            return $this->getUserInfo($indo);
        }catch (MessageException $e){
            return $e->getMessage();
        }
    }


}
