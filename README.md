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
