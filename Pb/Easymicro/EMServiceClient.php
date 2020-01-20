<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Pb\Easymicro;

/**
 */
class EMServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * 一元调用
     * @param \Pb\Easymicro\EMReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function UnaryCall(\Pb\Easymicro\EMReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/pb.easymicro.EMService/UnaryCall',
        $argument,
        ['\Pb\Easymicro\EMRsp', 'decode'],
        $metadata, $options);
    }

    /**
     * 服务端流
     * @param \Pb\Easymicro\EMReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function ServerStreamCall(\Pb\Easymicro\EMReq $argument,
      $metadata = [], $options = []) {
        return $this->_serverStreamRequest('/pb.easymicro.EMService/ServerStreamCall',
        $argument,
        ['\Pb\Easymicro\EMStreamRsp', 'decode'],
        $metadata, $options);
    }

}
