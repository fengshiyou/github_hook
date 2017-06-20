<?php
namespace Github\Hook;
/**
 * Created by PhpStorm.
 * User: fsy
 * Date: 2017/6/19
 * Time: 14:07
 */

class GithubHook
{
    public function actionGit()
    {
        $header = $this->getHeader();
        if (isset($header['X-HUB-SIGNATURE'])) {
            $hub_signature = $header['X-HUB-SIGNATURE'];//获取github签名
            list($algo, $hash) = explode('=', $hub_signature, 2);
            $pay_load = file_get_contents('php://input'); //php://input 是个可以访问请求的原始数据的只读流
            $hook_secret = union_config('config.hook.hook_secret');//在hook服务器上设置的密码,在配置文件中配置
            $pay_load_hash = hash_hmac($algo, $pay_load, $hook_secret);//制作签名
            if ($pay_load_hash == $hash) {
                $this->git_pull();
            } else {
                die('signature fails');
            }

        } else {
//            $hook_secret = union_config('config.hook.hook_secret');
//            die($hook_secret);
            die('not git_hub_request');
        }

        return $header;
    }

    /**
     * 获取header信息
     */
    public function getHeader()
    {
        $header = array();
        foreach ($_SERVER as $key => $val) {
            if ('HTTP_' == substr($key, 0, 5)) {
                $header[str_replace('_', '-', substr($key, 5))] = $val;
            }
        }
        return $header;
    }

    /**
     * git pull
     */
    public function git_pull()
    {
        $path = union_config('config.hook.hook_path');

        //需要用apache用户去执行shell脚本，所以需要apache用户拥有执行脚本权限(给创建一个用户，拥有sudo权限 vim /etc/php-fpm.d/www.conf)
        $apache_user_passwd = union_config('config.hook.apache_user_passwd');
        $shell_command = "cd {$path} && echo '$apache_user_passwd' | /usr/bin/sudo -S git reset --hard origin/master ";
        $shell_command .= "&& echo '$apache_user_passwd' | /usr/bin/sudo -S git clean -f ";
        $shell_command .= "&& echo '$apache_user_passwd' | /usr/bin/sudo -S git pull 2>&1 ";
        $shell_command .= "&& echo '$apache_user_passwd' | /usr/bin/sudo -S git checkout master";
        shell_exec($shell_command);

    }
}