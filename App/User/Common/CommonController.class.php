<?php
namespace User\Common;
use Think\Controller;


class CommonController extends Controller {
    protected $userInfo = null;
    protected $arrData = Array();
    protected $intUserId = 0;
    public function __construct()
    {
        parent::__construct();
        
    }



}