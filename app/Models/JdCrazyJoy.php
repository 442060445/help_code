<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JdCrazyJoy extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'jd_jdcrazyjoy';
    protected $guarded = [];

    //绑定两个插入的
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    const DELETED_AT = 'delete_time';

    /**
     * 模型日期列的存储格式
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

}
