<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/9
 * Time: 19:35
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class ListAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/list.css',
        'style/common.css',
        'style/bottomnav.css',
        'style/footer.css',
    ];
    public $js = [
        'js/jquery-1.8.3.min.js',
        'js/header.js',
        'js/list.js',
    ];
    public $depends = [
        //JqueryAsset::className(),
        'yii\web\JqueryAsset',
//        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
}