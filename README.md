# consul
consul

# 1.[下载地址](https://www.consul.io/downloads.html)

# 2.服务管理

## 2.1 启动服务
```shell
# 第一个服务节点
consul agent -server -bind=172.17.0.5 -bootstrap-expect=3 -node=node1 -data-dir=/tmp/consul >/dev/null 2>&1 &

# 第二个服务节点
consul agent -server -bind=172.17.0.6 -retry-join=172.17.0.5 -node=node2 -data-dir=/tmp/consul >/dev/null 2>&1 &

# 第三个服务节点
consul agent -server -bind=172.17.0.7 -retry-join=172.17.0.5 -node=node3 -data-dir=/tmp/consul >/dev/null 2>&1 &
```

## 2.2 停止服务
```shell
# 查看consul进程pid
ps -ef|grep consul
# 停止consul服务
kill -INT consul_pid

```

## 2.3 启动脚本

```shell
#!/bin/sh
# chkconfig:   2345 90 10
# description:  consul

EXEC=/usr/local/consul/bin/consul
PIDFILE=/var/run/consul.pid
DATADIR=/tmp/consul
NODE=node2
JOIN="172.17.0.5"
BIND="0.0.0.0"
STARTPARAMS="agent -server"

case "$1" in
    start)
        if [ -f $PIDFILE ]
        then
                echo "$PIDFILE exists, process is already running or crashed"
        else
                echo "Starting consul server..."
                $EXEC $STARTPARAMS -bind=$BIND -retry-join=$JOIN -node=$NODE -data-dir=$DATADIR -pid-file=$PIDFILE >/dev/null 2>&1 &
        fi
        ;;
    stop)
        if [ ! -f $PIDFILE ]
        then
                echo "$PIDFILE does not exist, process is not running"
        else
                PID=$(cat $PIDFILE)
                kill -INT $PID
                echo "Stopping ..."
                while [ -x /proc/${PID} ]
                do
                    echo "Waiting for consul to shutdown ..."
                    sleep 1
                done
                echo "consul stopped"
        fi
        ;;
    restart)
	$0 stop
	$0 start
        ;;
    *)
        echo "Usage: $0 {start|stop|restart}"
        ;;
esac
```

# 3.启动参数介绍