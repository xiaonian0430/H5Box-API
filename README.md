# H5BOx API

服务器环境要求：
最低配置：
- 4G内存
- 40G磁盘
- 2v CPU

# 相关软件

- centos 7
- openssl
- php 7.2 以上版本
- swoole 4.4.16
- 使用 Composer 作为依赖管理工具


# 安装

1）yum install openssl-devel


2）例如：php 7.2 安装 如下

yum install -y epel-release
rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm
yum -y remove php*
yum -y install php72w-cli php72w-fpm php72w-common php72w-mbstring php72w-devel

3）例如 swoole 安装如下
### swoole 扩展
wget https://github.com/swoole/swoole-src/archive/v4.5.2.tar.gz -O swoole.tar.gz \
    && mkdir -p swoole \
    && tar -xf swoole.tar.gz -C swoole --strip-components=1 \
    && rm swoole.tar.gz \
    && ( \
    cd swoole \
    && phpize \
    && ./configure --enable-openssl \
    && make \
    && make install \
    ) \
    && sed -i "2i extension=swoole.so" /etc/php.ini \
    && rm -r swoole


### redis 扩展
wget http://pecl.php.net/get/redis-5.3.1.tgz -O redis.tar.gz \
    && mkdir -p redis \
    && tar -xf redis.tar.gz -C redis --strip-components=1 \
    && rm redis.tar.gz \
    && ( \
    cd redis \
    && phpize \
    && ./configure \
    && make \
    && make install \
    ) \
    && sed -i "2i extension=redis.so" /etc/php.ini \
    && rm -r redis

4） composer 安装
 
 中文官网 ： https://www.phpcomposer.com/



5） 项目依赖安装 

composer install


php vendor/easyswoole/easyswoole/bin/easyswoole install  选择默认参数


# 启动服务

bin/start.sh


如下命令已经在 shell 脚本中写好

php easyswoole start produce  d

php easyswoole stop produce

php easyswoole reload produce

php easyswoole restart produce



# 使用Nginx反向代理 支持wss 和 https

server {

    # 下面这个部分和你https的配置没有什么区别，如果你是 宝塔 或者是 oneinstack 这里用生成的也是没有任何问题的
    listen 443;
    server_name 这里是你申请的域名;

    ssl on;

    # 这里是你申请域名对应的证书(一定要注意路径的问题，建议绝对路径)
    ssl_certificate 你的证书.crt;
    ssl_certificate_key 你的密匙.key;

    ssl_session_timeout 5m;
    ssl_session_cache shared:SSL:10m;
    ssl_protocols TLSv1 TLSv1.1 TLSv1.2 SSLv2 SSLv3;
    ssl_ciphers ALL:!ADH:!EXPORT56:RC4+RSA:+HIGH:+MEDIUM:+LOW:+SSLv2:+EXP;
    ssl_prefer_server_ciphers on;
    ssl_verify_client off;

    # 下面这个部分其实就是反向代理 如果你是 宝塔 或者是 oneinstack 请把你后续检查.php相关的 和重写index.php的部分删除
    location / {
        proxy_redirect off;
        proxy_pass http://127.0.0.1:9501;      # 转发到你本地的9501端口 这里要根据你的业务情况填写 谢谢
        proxy_set_header Host $host;
        proxy_set_header X-Real_IP $remote_addr;
        proxy_set_header X-Forwarded-For $remote_addr:$remote_port;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;   # 升级协议头
        proxy_set_header Connection upgrade;
        proxy_connect_timeout 4s; #配置点1
        proxy_read_timeout 600s; #如果600秒内没有通讯，会断开。前端可以做心跳包，保持连接不中断
        proxy_send_timeout 12s; #配置点3
    }
}