# consul
consul

# [下载地址](https://www.consul.io/downloads.html)
### [1.服务管理](https://github.com/jhq0113/consul/blob/master/1.%E6%9C%8D%E5%8A%A1%E7%AE%A1%E7%90%86.md)
### [2.启动参数介绍](https://github.com/jhq0113/consul/blob/master/2.启动参数.md)
### [3.HTTP-API](https://github.com/jhq0113/consul/blob/master/3.HTTP-API.md)
### [4.Cloud Auto-join](https://github.com/jhq0113/consul/blob/master/4.Cloud-Auto-join.md)
### [5.Services](https://github.com/jhq0113/consul/blob/master/5.Services.md)
### [6.Checks](https://github.com/jhq0113/consul/blob/master/6.Checks.md)
### [7.Watches](https://github.com/jhq0113/consul/blob/master/7.Watches.md)
### [8.Catalog](https://github.com/jhq0113/consul/blob/master/8.Catalog.md)
### [9.Forwarding-DNS](https://github.com/jhq0113/consul/blob/master/9.Forwarding-DNS.md)

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

ZooKeeper提供短暂的节点，这些节点是K/V条目，当客户端断开连接时，这些条目将被删除。这些系统比心跳系统更复杂，但仍然存在固有的可伸缩性问题，并且增加了客户端的复杂性。
所有客户端必须保持与ZooKeeper服务器的活动连接，并执行keep alives。另外，这需要“臃肿的客户端”，这些客户机很难编写，并且常常导致调试挑战。

Consul使用非常不同的体系结构进行健康检查。Consul客户机不只是拥有服务器节点，而是在集群中的每个节点上运行。这些客户机是`gossip`池的一部分，它提供多种功能，包括分布式健康检查。
`gossip`协议实现了一个有效的故障检测器，它可以扩展到任意大小的集群，而无需将工作集中在任何选定的服务器组上。客户机还可以在本地运行一组更丰富的健康检查，而ZooKeeper临时节点是一个非常原始的活动检查。
使用Consul，客户机可以检查Web服务器是否返回200个状态代码、内存利用率是否不重要、是否有足够的磁盘空间等。Consul客户机公开一个简单的HTTP接口，并避免以与ZooKeeper相同的方式向客户机公开系统的复杂性。

Consul为服务发现、健康检查、K/V存储和多个数据中心提供一流的支持。为了支持简单的k/v存储以外的任何东西，所有这些其他系统都需要在顶部构建额外的工具和库。通过使用客户机节点，Consul提供了一个只需要瘦客户机的简单API。
此外，通过使用配置文件和DNS接口，可以完全避免使用API，从而拥有一个完全不需要开发的服务发现解决方案。