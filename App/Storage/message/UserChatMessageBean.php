<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/18
 * Time: 11:23
 */

namespace App\Storage\message;


use App\Utility\Common;
use EasySwoole\Spl\SplBean;

/**
 * 用户消息定义(layim)
 * Class UserMessageBean
 * @package App\Storage
 */
class UserChatMessageBean extends SplBean
{
    //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
    protected $id;

    protected $username;

    protected $avatar;

    protected $type;

    protected $content;

    protected $mine = false;

    //消息的发送者id
    protected $fromid = 0;

    protected $timestamp;

    //消息ID
    protected $cid;

    //发送者id/接收者id
    protected $sid;

    /**
     * @return mixed
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * @param mixed $sid
     */
    public function setSid($sid): void
    {
        $this->sid = $sid;
    }

    /**
     * @return mixed
     */
    public function getCid()
    {
        return $this->cid;
    }

    /**
     * @param mixed $cid
     */
    public function setCid($cid): void
    {
        $this->cid = $cid;
    }

    /**
     * @return int
     */
    public function getFromid(): int
    {
        return $this->fromid;
    }

    /**
     * @param int $fromid
     */
    public function setFromid(int $fromid): void
    {
        $this->fromid = $fromid;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return bool
     */
    public function isMine(): bool
    {
        return $this->mine;
    }

    /**
     * @param bool $mine
     */
    public function setMine(bool $mine): void
    {
        $this->mine = $mine;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp): void
    {
        $this->timestamp = $timestamp ?: Common::micTime();
    }
}