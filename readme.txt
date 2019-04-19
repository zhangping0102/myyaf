可以按照以下步骤来部署和运行程序:
1.请确保机器root@MyLinux已经安装了Yaf框架, 并且已经加载入PHP;
2.把myYaf目录Copy到Webserver的DocumentRoot目录下;
3.需要在php.ini里面启用如下配置，生产的代码才能正确运行：
  yaf.environ="product"
4.重启Webserver;

