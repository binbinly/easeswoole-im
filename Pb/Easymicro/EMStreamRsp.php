<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: easymicro.proto

namespace Pb\Easymicro;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>pb.easymicro.EMStreamRsp</code>
 */
class EMStreamRsp extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string traceID = 1;</code>
     */
    private $traceID = '';
    /**
     * Generated from protobuf field <code>int32 streamSeq = 2;</code>
     */
    private $streamSeq = 0;
    /**
     * Generated from protobuf field <code>bytes streamData = 3;</code>
     */
    private $streamData = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $traceID
     *     @type int $streamSeq
     *     @type string $streamData
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Easymicro::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string traceID = 1;</code>
     * @return string
     */
    public function getTraceID()
    {
        return $this->traceID;
    }

    /**
     * Generated from protobuf field <code>string traceID = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setTraceID($var)
    {
        GPBUtil::checkString($var, True);
        $this->traceID = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 streamSeq = 2;</code>
     * @return int
     */
    public function getStreamSeq()
    {
        return $this->streamSeq;
    }

    /**
     * Generated from protobuf field <code>int32 streamSeq = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setStreamSeq($var)
    {
        GPBUtil::checkInt32($var);
        $this->streamSeq = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes streamData = 3;</code>
     * @return string
     */
    public function getStreamData()
    {
        return $this->streamData;
    }

    /**
     * Generated from protobuf field <code>bytes streamData = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setStreamData($var)
    {
        GPBUtil::checkString($var, False);
        $this->streamData = $var;

        return $this;
    }

}

