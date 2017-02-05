<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width">
<title><?php echo C('WEB_SITE_TITLE');?> | <?php echo C('SOFT_NAME');?></title>
<link href="/Public/Admin/images/favicon.ico" mce_href="/Public/Admin/images/favicon.ico" rel="bookmark" type="image/x-icon" /> 
<link href="/Public/Admin/images/favicon.ico" mce_href="/Public/Admin/images/favicon.ico" rel="icon" type="image/x-icon" /> 
<link href="/Public/Admin/images/favicon.ico" mce_href="/Public/Admin/images/favicon.ico" rel="shortcut icon" type="image/x-icon" /> 
<link rel="stylesheet" type="text/css" href="/Public/Static/Easyui/themes/metro/easyui.css">
<link rel="stylesheet" type="text/css" href="/Public/Static/Easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/Public/Static/Easyui/themes/color.css">
<link rel="stylesheet" type="text/css" href="/Public/Admin/css/login.css">
<style>
<?php if(C('ADMIN_LOGIN_BG_TYPE') == '0'): ?>.bg{position: absolute;right: 0px;top: 0px;bottom: 0px;left: 0px; background:#09F}
<?php elseif(C('ADMIN_LOGIN_BG_TYPE') == '1'): ?>
.bg{position: absolute;right: 0px;top: 0px;bottom: 0px;left: 0px;-moz-background-size: 100% 100%;-o-background-size: 100% 100%;-webkit-background-size: 100% 100%;-ms-background-size: 100% 100%;background-size: 100% 100%;filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale')";background-image:url(<?php echo C('ADMIN_LOGIN_BG_IMG');?>);}
<?php else: ?>
<?php $bjsj=rand(1,4); ?>
.bg{position: absolute;right: 0px;top: 0px;bottom: 0px;left: 0px;-moz-background-size: 100% 100%;-o-background-size: 100% 100%;-webkit-background-size: 100% 100%;-ms-background-size: 100% 100%;background-size: 100% 100%;filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale')";background-image:url(./Public/Admin/images/Login/bg_<?php echo ($bjsj); ?>.jpg);}<?php endif; ?>
</style>
<script type="text/javascript" src="/Public/Static/Jquery/jquery.min.js"></script>
<script type="text/javascript" src="/Public/Static/Easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/Public/Static/Easyui/locale/easyui-lang-zh_CN.js"></script>
</head>
<body>
<div class="bg"></div>
<div class="login_box">
  <div class="login_title">
    <div class="logo"><?php echo C('SOFT_NAME');?></div>
    <div class="info">—— V <?php echo C('SOFT_VERSION');?></div>
  </div>
  <form method="POST" action="<?php echo U('login');?>">
    <div class="form">
      <div class="inputs">
        <div><span>用户名：</span>
          <input id="username" name="username" class="easyui-textbox" data-options="prompt:'请输入用户名'" style="height:33px;" type="text">
        </div>
        <div><span>密码：</span>
          <input id="password" name="password" class="easyui-textbox" style="height:33px;" type="password">
        </div>
      </div>
      <div class="actions">
        <input type="submit" id="submit" value="登陆">
        <input type="checkbox" class="checkbox" name="rember_password" id="rm" checked="checked">
        <label for="rm" style="color:#999">记住密码</label>
      </div>
      <div class="msg"></div>
      <div style="clear:both;"></div>
      <div class="extend"> <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo C('ADMIN_QQ');?>&site=qq&menu=yes" target="_blank">联系管理员</a></div>
      
    </div>
  </form>
</div>
<div class="common_footer">Powered by <a href="<?php echo C('SOFT_SITE');?>" style="color:#FFF"><?php echo C('SOFT_NAME');?> v <?php echo C('SOFT_VERSION');?></a> | Copyright © <a href="<?php echo C('SOFT_AUTHOR_SITE');?>" style="color:#FFF"><?php echo C('SOFT_AUTHOR');?></a> All rights reserved.</div>
</body>
</html>