<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/14
 * Time: 14:53
 */

namespace App\Model;

use EasySwoole\Mysqli\Mysqli;

class BaseModel
{
    protected $db;

    protected $table;

    function __construct(Mysqli $connection)
    {
        $this->db = $connection;
    }

    function getDbConnection(): Mysqli
    {
        return $this->db;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }
}