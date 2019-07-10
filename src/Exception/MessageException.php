<?php
/**
 * @Created by PhpStorm
 * @author: injurys
 * @file: MessageException.php
 * @Date: 2019/6/21 9:44
 */

namespace third\oauth\Exception;


class MessageException extends DingTalkCloudException
{

    public function __construct($errorMessage, $errorCode, $previous = null)
    {
        parent::__construct($errorMessage, 0, $previous);
        $this->errorMessage = $errorMessage;
        $this->errorCode    = $errorCode;
    }

}
