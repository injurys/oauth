<?php
/**
 * @Created by PhpStorm
 * @author: injurys
 * @file: DingTalkCloudException.php
 * @Date: 2019/6/21 9:39
 */

namespace injurys\third\Exception;

use Exception;

abstract class DingTalkCloudException extends Exception
{

    /**
     * @var string
     */
    protected $errorCode;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

}
