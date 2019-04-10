# consul
consul

# 1.[下载地址](https://www.consul.io/downloads.html)

# 2.服务管理
```shell
# 第一个服务节点
consul agent -server -bind=172.17.0.5 -bootstrap-expect=3 -node=node1 -data-dir=/tmp/consul >/dev/null 2>&1 &

# 第二个服务节点
consul agent -server -bind=172.17.0.6 -retry-join=172.17.0.5 -node=node2 -data-dir=/tmp/consul >/dev/null 2>&1 &

# 第三个服务节点
consul agent -server -bind=172.17.0.7 -retry-join=172.17.0.5 -node=node3 -data-dir=/tmp/consul >/dev/null 2>&1 &
```

# 3.启动参数介绍