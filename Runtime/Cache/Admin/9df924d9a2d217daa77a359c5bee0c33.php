<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理控制台 | <?php echo C('SOFT_NAME');?></title>
<link rel="stylesheet" type="text/css" href="/Public/Static/Easyui/themes/metro-gms/easyui.css">
<link rel="stylesheet" href="/Public/Static/Font/iconfont.css">
<link rel="stylesheet" type="text/css" href="/Public/Static/Easyui/themes/color.css">
<link rel="stylesheet" type="text/css" href="/Public/Admin/css/main.css">
<script type="text/javascript" src="/Public/Static/Jquery/jquery.min.js"></script>
<script type="text/javascript" src="/Public/Static/Easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/Public/Static/Easyui/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="/Public/Admin/js/main.js"></script>
</head>
<body class="easyui-layout" id="Main_Layout_Box">
<div id="Main_Layout_North" data-options="region:'north',split:false">
  <div id="header_logo"> <a href="<?php echo U('Admin/Index/index');?>"><?php echo C('SOFT_NAME');?></a> <i class="opacity-80">v<?php echo C('SOFT_VERSION');?></i> <a class="justify" href="javascript:void(0);"></a></div>
  <ul id="header_nav" class="header_nav">
    <?php if(is_array($AdminMenu)): $i = 0; $__LIST__ = $AdminMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="JavaScript:void(0);" id="nav_<?php echo ($v['id']); ?>"><li><?php echo ($v['title']); ?></li></a><?php endforeach; endif; else: echo "" ;endif; ?>
  </ul>
  <ul class="header_nav" style="float:right">
    <a href="JavaScript:void(0);" onclick="UpdateTabs('Admin/User/updatenickname','用户资料','<?php echo U('User/updatenickname');?>','iconfont icon-user')"><li><?php echo ($UserInfo['nickname']); ?>[<?php echo ($UserInfo['username']); ?>]</li></a>
    <a href="JavaScript:void(0);" onclick="UpdateTabs('Admin/Config/group','缓存更新','<?php echo U('Index/cache');?>','iconfont icon-shezhi')"><li>缓存更新</li></a>
    <a href="<?php echo U('Public/logout');?>"><li>退出</li></a>
  </ul>
</div>
<div id="Main_Layout_South" data-options="region:'south',split:false">Powered by <a href="<?php echo C('SOFT_SITE');?>" style="color:#666"><?php echo C('SOFT_NAME');?> v <?php echo C('SOFT_VERSION');?></a> | Copyright © <a href="<?php echo C('SOFT_AUTHOR_SITE');?>" style="color:#666"><?php echo C('SOFT_AUTHOR');?></a> All rights reserved.</div>
<div id="Main_Layout_West" data-options="region:'west',split:false,cls:'Main_Layout_West_box'">
<?php if(is_array($AdminMenu)): $i = 0; $__LIST__ = $AdminMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div id="nav_<?php echo ($v['id']); ?>_box" class="nav_box">
        <div class="right_box">
          <?php if(is_array($v['children'])): $i = 0; $__LIST__ = $v['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl id="right_<?php echo ($vo['id']); ?>_box_menu">
              <dd>
                <ul>
                  <?php if(is_array($vo['children'])): $i = 0; $__LIST__ = $vo['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?><a href="JavaScript:void(0);" onclick="UpdateTabs('<?php echo ($vo2['name']); ?>','<?php echo ($vo2['title']); ?>','<?php echo ($vo2['url']); ?>','<?php echo ($vo2['icon']); ?>')"><li><i class="icon iconfont <?php echo ($vo2['icon']); ?>"></i><?php echo ($vo2['title']); ?></li></a><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
              </dd>
            </dl><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <ul class="left_box">
            <?php if(is_array($v['children'])): $i = 0; $__LIST__ = $v['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="JavaScript:void(0);" id="left_<?php echo ($vo['id']); ?>_nav_menu" class="left_nav" onclick="LeftMenu('<?php echo ($vo['id']); ?>')"><li><i class="icon iconfont <?php echo ($vo['icon']); ?>"></i><div><?php echo ($vo['title']); ?></div></li></a><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
<div id="Main_Layout_Center" data-options="region:'center',split:false" style="padding:5px;">
  <div id="MainTabs" class="easyui-tabs" data-options="fit:true,border:false">
    <div title="控制台" data-options="closable:false,id:-1,iconCls:'iconfont icon-all',bodyCls:'tabs_box'" id="tabs_Index" style="padding: 5px;">
    <iframe scrolling="yes" frameborder="0"  src="<?php echo U('Admin/Index/main');?>" style="width:100%;height:100%;"></iframe>
    </div>
  </div>
</div>
</body>
</html>