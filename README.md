###安装
首先 

```
cp .env.example .env
```

####选择缓存方式：
- 文件缓存
```
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```
- REDIS 缓存
```

CACHE_DRIVER=redis
QUEUE_CONNECTION=sync
CACHE_PREFIX=缓存前缀   

REDIS_HOST=REDIS的主机
REDIS_DATABASE=REDIS的库
REDIS_PASSWORD=REDIS的密码
```
 *注意V1 版本 可以不用分隔符结尾，V2版本建议都分隔符结尾。

####数据库链接：
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

####修改赞助人名称
```
SPONSOREDBY=赞助人名称
```

####决定使用的语言:
- 中文
```
APP_LOCALE=zh-CN
```
- 英文
```
APP_LOCALE=en
```
####语言包存放路径：
- `resource/lang/en`
- `resource/lang/zh-CN`

####规定每组助力码输出的最大次数
```
MAX_OUTPUT_TIME=输入最大次数
```
####V2版本逻辑是否使用数据库
```
MYSQL_USE = false/true        意思：不使用/使用
```

---
####版本选择
改这个选择  选择引入的包  ( V1 /  V2 )\CodeBusiness。
V1 是按顺序，存放数据库，redis 做辅助的。
V2 是用redis的随机获取的，Mysql 如果有开启使用的话，那么就插入的时候会做多一个备份在Mysql
```
app/Http/Controllers/CodeController.php
app/Console/Commands/ResetDailyCommand.php
app/Console/Commands/ResetWeeklyCommand.php
```

----
###两个清空脚本
```
WEEKLY_CLEAN_DAY=1,10,20
```
只要开启每天跑清理，加上这个上面的值，就可以时间日清周期清了。
默认值是1,10,20 。
脚本是 
```
php artisan reset_daily
```
设置定时任务，1 0 * * *  在本项目路径下，执行这个就可以了。

---
#### 添加一个新类别要做的事

假设现在那个活动叫 `表名` ,取这个叫`jd_table_name`

注意下面的  `jd_table_name`  和  `JdTableName`  ,你可以Ctrl + F 选高亮，可能会方便点哦。



- 数据库执行
        
        ```
        CREATE TABLE `jd_table_name`  (
          `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID ',
          `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '助力码',
          `create_time` datetime(0) NULL DEFAULT NULL,
          `update_time` datetime(0) NULL DEFAULT NULL,
          `delete_time` datetime(0) NULL DEFAULT NULL,
          PRIMARY KEY (`id`) USING BTREE,
          INDEX `code_index`(`code`) USING BTREE COMMENT '助力码索引'
        ) ENGINE = InnoDB AUTO_INCREMENT = 34 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;
        
        ```

- 在 app/Models 创建一个文件 叫  JdTableName.php,内容如下：
        
        ```
        <?php
        
        namespace App\Models;
        
        use Illuminate\Database\Eloquent\Model;
        use Illuminate\Database\Eloquent\SoftDeletes;
        
        class JdTableName extends Model
        {
            use SoftDeletes;
        
            protected $connection = 'mysql';
            protected $table = 'jd_table_name';
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
        ```
        
        *$table = '(数据库表名)';
        
        *文件名就是把首字母和下划线后的字母变大写，之后去掉所有的下划线。
        
        *class **** 就是一个 和文件名一样

- app/Providers/ModelServiceProvider.php编辑
        
        ```
        public function register()
        {
            ...
            //写一个注释自己知道的
            $this->app->bind('JdTableNameModel' ,JdTableName::class); //这里就复制这行！！！！！，注意看上面的方法名
        
        }
        public function provides()
        {
            return [
                ... , 'JdTableNameModel'
            ];
        }
            
        ```
        
        *将第二步的文件名 填到  ***::class 
        
        \* '****Model'是第二步的文件名 + "Model"，做好规范嘛~ 这个名字嘛，我们叫`类名`
        
        *下面那里是用逗号隔开，补在最后，把列明写进去，注意格式哦，是  `,'(类名)'`

- config/typeToModel.php 编辑,不是在最后追加哦，是在内容下继续加

        ```
        'table' => 'JdTableNameModel',
        ```
        
        *'(别名)' => '(类名)'
        
        *记住这个别名哦，到时候别人添加查询都是用这个别名放在中间的！！！

- config/typeToQuantity.php 编辑,和第四步有点类似哦

        ```
        'table' => '10'
        ```
        
        *'(别名)' => '(京东要求输出最多数量)'
        
        *注意一定要保持别名一致哦

- 访问 ： 域名/别名/count，如果可以返回的话，那么就成功啦

###整理好发给@lxk0301
京东序互助码api 
https://域名/api/v1/jd/xxx/create/助力码/
查看上车人数
https://域名/api/v1/jd/xxx/count
获取助力码
https://域名/api/v1/jd/xxx/read/随机数
---



#### 版本 说明

| 版本号 |                           更新内容                           |  更新时间  |
| :----: | :----------------------------------------------------------: | :--------: |
|   V1   | 以Mysql为主的一个功能，缓存到Redis为辅，保证执行率。预构思中没有随机数量的情况，已补上。基础功能是想通过redis来提高现有的只读库的效率。 | 2020-12-09 |
|   V2   | 吸取@lxk0301的建议不要顺序，tg@N 的建议用redis-set-SRANDMEMBER 去随机，小范围测试10个数随机，出现率可实现全覆盖。该版本以redis为主，mysql作为一个备份功能，如果配置中没有配置开启，相关的mysql功能不会开启（功能包括：LoadIp ,助力码备份） | 2020-12-10 |
|  ...   |                                                              |            |



