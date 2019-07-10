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

class GithubLogin extends Base
{

    public function __construct($info=[])
    {
        if(empty($info['client_id']))
            throw new MessageException('缺少配置参数：client_id', 400);
        if(empty($info['client_secret']))
            throw new MessageException('缺少配置参数：client_secret', 400);
        $this->client_secret = $info['client_secret'];
        $this->client_id = $info['client_id'];
    }


    /**
     * @description:  获取 access_token
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
            'code' => $code,
            'client_id' => $this-> client_id,
            'client_secret' => $this->client_secret
        ];
        $url = "https://github.com/login/oauth/access_token?".http_build_query($data);
        parse_str(HttpRequest::get($url), $result);
        if(!isset($result['access_token']))
            throw new MessageException('access_token 获取失败', 400);

        return $result['access_token'];
    }

    /**
     * @description:  获取用户信息
     * @param string $access_token
     * @return mixed
     * @throws MessageException
     * @author: injurys
     * @updater:
     */
    public function getUserInfo($access_token='')
    {
        if(empty($access_token))
            throw new MessageException('缺少主要参数：access_token', 400);
        $url = "https://api.github.com/user?access_token=".$access_token;
        $headers = [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36',
        ];
        $result = json_decode(Tool::get($url, [], $headers), true);
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
            $access_token = $this->getAccessToken();
            return $this->getUserInfo($access_token);
        }catch (MessageException $e){
            return $e->getMessage();
        }
    }


}
