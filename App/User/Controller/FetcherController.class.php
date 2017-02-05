<?php
namespace User\Controller;
use Think\Controller;
use Common\Controller\WeixinTvSdk;

class FetcherController extends Controller {
   public function index(){
       Vendor("PhpFetcher.phpfetcher");
       
   }

    public function test(){
        header("Content-Type: text/html; charset=UTF-8");
        Vendor("PhpFetcher.mycrawler");
        $crawler = new  \mycrawler();
        $arrJobs = array(
            //任务的名字随便起，这里把名字叫qqnews
            //the key is the name of a job, here names it qqnews
            'qqnews' => array(
                'start_page' => 'http://www.woyaogexing.com/touxiang/weixin/index_17.html', //起始网页
                'link_rules' => array(
                    /*
                     * 所有在这里列出的正则规则，只要能匹配到超链接，那么那条爬虫就会爬到那条超链接
                     * Regex rules are listed here, the crawler will follow any hyperlinks once the regex matches
                     */
                    //'#/touxiang/nv/index_\d+\.html$#',
                ),
                //爬虫从开始页面算起，最多爬取的深度，设置为1表示只爬取起始页面
                //Crawler's max following depth, 1 stands for only crawl the start page
                'max_depth' => 2,

            ) ,
        );

        $crawler->setFetchJobs($arrJobs)->run(); //这一行的效果和下面两行的效果一样
    }
}