#!/bin/bash
echo $#
echo $2
echo $1

# 守护进程启动服务
if [ "$1"  = "start" ]
then
    php easyswoole start produce  d
fi

## 停止服务
if [ "$1"  = "stop" ]
then
    php easyswoole stop produce
fi

## 热重启
if [ "$1"  = "reload" ]
then
    php easyswoole reload produce
fi

## 强制重启
if [ "$1"  = "restart" ]
then
    php easyswoole restart produce
fi