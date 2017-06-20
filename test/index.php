<?php
require __DIR__ . '/../vendor/autoload.php';
/**
 * Created by PhpStorm.
 * User: fsy
 * Date: 2017/6/19
 * Time: 14:24
 */
use Github\Hook\GithubHook;
$hookModel = new GithubHook();
$hookModel->actionGit();
