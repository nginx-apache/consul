# consul
consul

# [下载地址](https://www.consul.io/downloads.html)
### 1.[服务管理](https://github.com/jhq0113/consul/blob/master/1.%E6%9C%8D%E5%8A%A1%E7%AE%A1%E7%90%86.md)
### 2.[启动参数介绍](https://github.com/jhq0113/consul/blob/master/2.启动参数.md)
### 3.[HTTP-API](https://github.com/jhq0113/consul/blob/master/3.HTTP-API.md)
### 4.[Cloud Auto-join](https://github.com/jhq0113/consul/blob/master/4.Cloud-Auto-join.md)
### 5.[Services](https://github.com/jhq0113/consul/blob/master/5.Services.md)
### 6.[Checks](https://github.com/jhq0113/consul/blob/master/6.Checks.md)
### 7.[Watches](https://github.com/jhq0113/consul/blob/master/7.Watches.md)
### 8.[Catalog](https://github.com/jhq0113/consul/blob/master/8.Catalog.md)

## Consul vs. ZooKeeper, doozerd, etcd

ZooKeeper, doozerd, and etcd的架构都很相似。这三个都有服务器节点，需要定额节点才能运行（通常是简单的多数）。
它们具有很强的一致性，并且公开了各种原语，这些原语可以通过应用程序中的客户端库来构建复杂的分布式系统。

Consul还使用单个数据中心内的服务器节点。在每个数据中心中，Consul服务器都需要仲裁才能运行并提供强一致性。
然而，Consul具有对多个数据中心的本机支持，以及链接服务器节点和客户机的功能更丰富的`gossip`系统。

在提供Key/Value存储时，所有这些系统都具有大致相同的语义：读取是强一致的，并且为了网络分区的一致性牺牲了可用性。
然而，当这些系统用于高级案例时，差异变得更加明显。

这些系统提供的语义对于构建服务发现系统很有吸引力，但必须强调必须构建这些特性，这一点很重要。
ZooKeeper等只提供原始K/V存储，并要求应用程序开发人员构建自己的系统来提供服务发现。
相比之下，Consul为服务发现提供了一个有观点的框架，并消除了猜测工作和开发工作。客户机只需注册服务，然后使用DNS或HTTP接口执行发现。
其他系统需要自己实现一个解决方案。

一个引人注目的服务发现框架必须包含健康检查和失败的可能性。如果节点A出现故障或服务崩溃，则知道该节点A提供foo服务并不有用。
不成熟的系统利用心跳、定期更新和TTL。这些方案要求工作与节点数量成线性关系，并将需求放在固定数量的服务器上。
此外，故障检测窗口至少与TTL一样长。