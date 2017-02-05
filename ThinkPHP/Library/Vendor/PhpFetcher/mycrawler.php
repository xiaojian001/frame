<?php
//下面两行使得这个项目被下载下来后本文件能直接运行
$demo_include_path = dirname(__FILE__) . '/../';
set_include_path(get_include_path() . PATH_SEPARATOR . $demo_include_path);

require_once('phpfetcher.php');
class mycrawler extends Phpfetcher_Crawler_Default {
    public function handlePage($page) {
        $arr = [];
        $objContent = $page->sel("//div/a");
        for ($i = 0; $i < count($objContent); ++$i) {
            $objPic = $objContent[$i]->find("img");
            for ($j = 0; $j < count($objPic); ++$j) {
                $arr[] =  $objPic[$j]->getAttribute('src');
            }
        }
        foreach ($arr as $v){
            $str = file_get_contents($v);
            //获取图片的后缀
            $postfix = substr($v, strrpos($v, '.')+1);
            $strName = uniqid().".".$postfix;
            M("picture")->add(Array("title"=>"/Uploads/picture/".$strName));
            file_put_contents("./Uploads/picture/{$strName}",$str);
        }
        echo "获取完成";
    }
}