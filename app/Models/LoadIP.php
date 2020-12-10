<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoadIP extends Model
{
    protected $connection = 'mysql';
    protected $table = 'load_ip';
    protected $guarded = [];

    //绑定两个插入的
    const CREATED_AT = 'date_time';
    const UPDATED_AT = 'update_time';

    /**
     * 模型日期列的存储格式
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

}
