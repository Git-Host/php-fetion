关注PHPFetion动态请到：http://blog.quanhz.com/

**最新版本下载(v1.5)：** http://code.google.com/p/php-fetion/downloads/list


---


**2012年8月28日：修改移动飞信登录接口**

**2012年8月3日：修改移动飞信登录接口**

**2012年7月23日：修复了不能给好友发送信息的问题，在给好友发信息时加上csrfToken字段**


---


## （一）使用说明 ##

1. 需要包含进你的程序的文件只有一个：PHPFetion.php。如：

```
require 'PHPFetion.php';
```

2. 调用方法如：

```
$fetion = new PHPFetion('13500001111', '123123'); // 手机号、飞信密码

$fetion->send('13500001111', 'Hello Fetion!'); // 接收人手机号、飞信内容
```

3. 其他

**(1) 信息内容需要是utf-8编码的。如果不是，请参考下面的代码转编码：**

```
//对$message进行转码

$message = iconv('gbk', 'utf-8', $message);

$fetion->send('13500001111', $message);
```

(2) send()方法是有返回值的，可以通过分析文本判断是否发送成功。考虑到wap界面会改动，所以没有在类里写死关于发送成功的判断。


## （二）实现原理 ##

1. 用PHP发送HTTP请求模拟登录WAP版的飞信，并模拟发送飞信。实现原理可查看：http://blog.quanhz.com/archives/118


## （三）其他 ##

1. wap飞信登录地址：http://f.10086.cn

2. 作者博客：http://blog.quanhz.com