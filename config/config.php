<?php

return [
    'hook'=>[
        'hook_secret'=>'501453944',
        'hook_path'=>'/data/test/bluesecretary',
        'apache_user_passwd'=>'Ace___7'//需要用apache用户去执行shell脚本，所以需要apache用户拥有执行脚本权限(给创建一个用户，拥有sudo权限 并修改apache用户vim /etc/php-fpm.d/www.conf)
    ]



];
