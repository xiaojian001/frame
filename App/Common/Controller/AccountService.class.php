<?php
namespace Common\Controller;
use Think\Controller;

/**
 * 资金操作类
 * @package Common
 */
class AccountService  extends Controller
{
    private $intUserId = 0;
    private $floMoney = 0;
    private $intBusId = 0;

    public function __construct($intUserId, $floMoney)
    {
        header("Access-Control-Allow-Origin: *");
        $this->intUserId = $intUserId;
        $this->floMoney = $floMoney;
        if ($intUserId < 0) {
            return Array("缺少参数：用户编号");
        }
        if (floatval($this->floMoney) <= 0)//最多只能接受1元的异常
        {
            return Array('操作金额小于0。', 0);
        }
    }

    /**
     * 用户通用性的入帐。
     * @param $intUserId
     * @param $strContent
     * @param $floMoney
     * @param $intBusId
     * @param $intType
     * @return array
     */
    public function inputCommonMoney($strContent, $intBusId = 0, $intType = 1, $intPayment = 1)
    {
        $arrValue = Array(
            'money' => $this->floMoney,//操作的现金额
            'total_money' => Array('exp', 'total_money+' . $this->floMoney),
            'use_money' => Array('exp', 'use_money+' . $this->floMoney),
        );
        return $this->updateUserAccount(
            $strContent,
            $intType,//通用性入帐
            $arrValue,
            $intBusId,
            $intPayment
        );
    }

    /**
     * 用户通用性的出账。
     * @param $strContent
     * @param $floMoney
     * @param $intBusId
     * @param $intType
     * @return array
     */
    public function outPutCommonMoney($strContent, $intBusId, $intType = 2, $intPayment = 2)
    {
        $arrValue = Array(
            'money' => $this->floMoney,//操作的现金额
            'total_money' => Array('exp', 'total_money-' . $this->floMoney),
            'use_money' => Array('exp', 'use_money-' . $this->floMoney),
        );
        return $this->updateUserAccount(
            $strContent,
            $intType,//通用性入帐
            $arrValue,
            $intBusId,
            $intPayment
        );

    }

    /**
     * 用户锁定金额
     */
    public function lockMoney($strContent, $intBusId, $intType = 3, $intPayment = 3)
    {
        $arrValue = Array(
            'money' => $this->floMoney,//操作的现金额
            'lock_money' => Array('exp', 'lock_money+' . $this->floMoney),
            'use_money' => Array('exp', 'use_money-' . $this->floMoney),
        );
        return $this->updateUserAccount(
            $strContent,
            $intType,//通用性入帐
            $arrValue,
            $intBusId,
            $intPayment
        );
    }

    /**
     * 去掉锁定金额，减去totalmoney
     */
    public function useLockMoney($strContent, $intBusId, $intType = 5, $intPayment = 1)
    {
        $arrValue = Array(
            'money' => $this->floMoney,//操作的现金额
            'lock_money' => Array('exp', 'lock_money-' . $this->floMoney),
            'total_money' => Array('exp', 'total_money-' . $this->floMoney),
        );
        return $this->updateUserAccount(
            $strContent,
            $intType,//通用性入帐
            $arrValue,
            $intBusId,
            $intPayment
        );
    }

    /**
     * 用户解锁锁定金额
     */
    public function unlockMoney($strContent, $intBusId, $intType = 4, $intPayment = 4)
    {
        $arrValue = Array(
            'money' => $this->floMoney,//操作的现金额
            'lock_money' => Array('exp', 'lock_money-' . $this->floMoney),
            'use_money' => Array('exp', 'use_money+' . $this->floMoney),
        );
        return $this->updateUserAccount(
            $strContent,
            $intType,//通用性入帐
            $arrValue,
            $intBusId,
            $intPayment
        );
    }

    /**
     * @param $intUserId
     * @param $strContent
     * @param $intType
     * @param $arrValue
     * @param $intBusId
     * @return array
     */
    public function updateUserAccount($strContent, $intType, $arrValue, $intBusId, $intPayment)
    {
        if ($this->intUserId < 1) {
            return Array($this->intUserId . '更新用户表时，用户ID为空。', 0);
        }
        if (!$strContent) {
            return Array($this->intUserId . '操作内容说明不能为空。', 0);
        }
        if (intval($intType) < 0) {
            return Array($this->intUserId . '操作类型不明。', 0);
        }
        if (!is_array($arrValue)) {
            return Array($this->intUserId . '更新的字段为空。', 0);
        }
        $result = M("member_account")->where("member_id={$this->intUserId}")->save($arrValue);

        if ($result === false) {
            return Array($this->intUserId . '操作帐户失败：' . M("worker_account")->getDbError(), 0);
        }

        $arrAccountInfo = M("member_account")->where("member_id={$this->intUserId}")->find();
        foreach ($arrAccountInfo as $k => $v) {
            if ($v < 0) {
                return Array($this->intUserId . "帐户资金异常，无法继续操作。{$k}={$v}", 0);
            }
        }

        if($intType !=3 && $intType !=4)   //锁定金额、解锁金额不记录资金记录
        {
            $arrType = Array(
                "",
                "收入",
                "支出",
                "锁定",
                "解锁",
                "支出"
            );
            $arrResult = Array();
            if (isset($arrValue['money']) && $arrValue['money'] > 0) {
                $data = Array(
                    'member_id' => $this->intUserId,
                    'money' => $arrValue['money'],
                    'type' => $intType,
                    'payment' => $intPayment,
                    'type_info' => $arrType[$intType],
                    'content' => $strContent,
                    'ip' => get_client_ip(),
                    'add_time' => time(),
                    'b_id' => $intBusId,
                    'current_total' => $arrAccountInfo['total_money'],
                    'current_use' => $arrAccountInfo['use_money'],
                    'current_lock' => $arrAccountInfo['lock_money'],
                );
                $result = M("member_account_log")->add($data);
                if (intval($result) < 1) {
                    return Array($this->intUserId . '添加余额记录失败。', 0);
                }
                $arrResult[] = $result;
            }
        }
        
        return Array("操作资金记录成功", 1);
    }

}