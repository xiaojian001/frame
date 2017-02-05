<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title> <?php echo C('SOFT_NAME');?>|Gms管理系统</title>
    <link href="/Public/Admin/images/favicon.ico" mce_href="/Public/Admin/images/favicon.ico" rel="bookmark" type="image/x-icon" /> 
    <link href="/Public/Admin/images/favicon.ico" mce_href="/Public/Admin/images/favicon.ico" rel="icon" type="image/x-icon" /> 
    <link href="/Public/Admin/images/favicon.ico" mce_href="/Public/Admin/images/favicon.ico" rel="shortcut icon" type="image/x-icon" /> 
    <link rel="stylesheet" type="text/css" href="/Public/Static/Easyui/themes/metro-gms/easyui.css">
    <link rel="stylesheet" type="text/css" href="/Public/Static/Font/iconfont.css">
    <link rel="stylesheet" type="text/css" href="/Public/Static/kindeditor/themes/default/default.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Static/Easyui/themes/color.css">
    <link rel="stylesheet" href="/Public/Admin/css/skin.css" />
    <script type="text/javascript" src="/Public/Static/Jquery/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/Static/Easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/Public/Static/Easyui/locale/easyui-lang-zh_CN.js"></script>
    <script charset="utf-8" src="/Public/Static/kindeditor/kindeditor-min.js"></script>
    <script charset="utf-8" src="/Public/Static/kindeditor/lang/zh_CN.js"></script>
    <script charset="utf-8" src="/Public/Static/Echarts/echarts.js"></script>
    <script charset="utf-8" src="/Public/Admin/js/LodopFuncs.js"></script>
    <script charset="utf-8" src="/Public/Admin/js/base.js" /></script><script>
	var ke_pasteType=2;
	var ke_fileManagerJson="<?php echo U('Admin/FilesUpdata/filemanager');?>";
	var ke_uploadJson="<?php echo U('Admin/FilesUpdata/upload');?>";
	var ke_Uid='<?php echo session(C("AUTH_KEY"));;?>';
	var Root='';
	</script>
</head>
<body>

<link rel="stylesheet" type="text/css" href="<?php echo ($ADDON_PATH); ?>base.css">
<div class="fixed-bar datagrid-toolbar">
  <div class="item-title">
    <h3>系统信息</h3>
  </div>
</div>
<div class="info-panel">
  <dl>
    <dt>
      <h3>系统信息</h3>
      <div class="ps-container">
        <ul>
  <?php if(is_array($server_info)): $i = 0; $__LIST__ = $server_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><?php echo ($key); ?>  <span><?php echo ($vo); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <div class="ps-scrollbar-x" style="left: 0px; bottom: 3px; width: 0px;"></div>
        <div class="ps-scrollbar-y" style="top: 0px; right: 3px; height: 25px;"></div>
      </div>
    </dt>
    <dd>
      <ul>
        <li class="w50pre none"><a href='<?php echo C("SOFT_SITE");?>' target='_blank'><?php echo C('SOFT_NAME').C('SOFT_VERSION');?></a></li>
        <li class="w50pre none"><a href="<?php echo C('SOFT_BBS');?>" target='_blank'>官方论坛</a></li>
      </ul>
    </dd>
  </dl>
  <dl>
    <dt>
      <h3>版权信息</h3>
      <div class="ps-container">
        <ul>
          <li>版权所有  <span><a href="<?php echo C('SOFT_SITE');?>" target="_blank"><?php echo C('SOFT_NAME');?></a></span></li>
          <li>负责人  <span><a href="<?php echo C('SOFT_AUTHOR_SITE');?>" target="_blank"><?php echo C('SOFT_AUTHOR');?></a></span></li>
          <li>软件作者  <span><a href="<?php echo C('SOFT_AUTHOR_SITE');?>" target="_blank"><?php echo C('SOFT_AUTHOR');?></a></span></li>
          <li>联系邮箱  <span><?php echo C('SOFT_AUTHOR_EMAIL');?></span></li>
        </ul>
        <div class="ps-scrollbar-x" style="left: 0px; bottom: 3px; width: 0px;"></div>
        <div class="ps-scrollbar-y" style="top: 0px; right: 3px; height: 25px;"></div>
      </div>
    </dt>
    <dd>
      <ul>
        <li class="w50pre none"><a href="<?php echo C('SOFT_AUTHOR_SITE');?>" target='_blank'>负责人：<?php echo C('SOFT_AUTHOR');?></a></li>
        <li class="w50pre none"><a href='tencent://message/?uin=<?php echo C("SOFT_AUTHOR_QQ");?>&Site=<?php echo C('SOFT_SITE');?>&Menu=yes' target='_blank'>QQ:<?php echo C('SOFT_AUTHOR_QQ');?></a></li>
      </ul>
    </dd>
  </dl>
  <div class=" clear"></div>
</div>

</body>
</html>