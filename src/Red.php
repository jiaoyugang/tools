<?php
namespace tools;
/**
 * 微信发送红包算法
 */
final class Red{

    /** 创建静态私有的变量保存该类对象 */
    private static $instance;

    //防止使用new直接创建对象
    private function __construct(){}

    //防止使用clone克隆对象
    private function __clone(){}

    public static function getInstance()
    {
        //判断$instance是否是Singleton的对象，不是则创建
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * 生成红包的函数2
     * 
     * 保证每个人至少能分到0.01元
     * 在分配当前红包金额时，先预留剩余红包所需最少金额，然后在0.01~(总金额-预留金额)间取随机数，得到的随机数就是当前红包分配的金额。
     * 
     * @param mixed $totalMoney
     * @param int   $totalPeople
     * @param int   $miniMoney
     */
    final public function getRandMoney_2($total, $num = 2, $min = 0.01)
    {
        $money_arr = array(); //存入随机红包金额结果
        for ($i = 1; $i < $num; $i++) {
            $safe_total = ($total - ($num - $i) * $min) / ($num - $i); //随机安全上限
            $money = mt_rand($min * 100, $safe_total * 100) / 100;
            $total = $total - $money;
            $money_arr[] = $money;
        }
        $money_arr[] = round($total, 2);
        return $money_arr;
    }

    /**
     * 生成红包的函数1
     * 
     * @param mixed $totalMoney
     * @param int   $totalPeople
     * @param int   $miniMoney
     */
    final public function getRandMoney_1($totalMoney, $totalPeople = 2, $miniMoney = 0.01)
    {
        $randRemainMoney = $totalMoney - $totalPeople * $miniMoney; //剩余需要随机的钱数
        return $this->_getRandMoney($randRemainMoney, $totalPeople, $miniMoney);
    }

    /**
     * 红包生成的逻辑代码
     */
    final private function _getRandMoney($totalMoney, $totalPeople, $miniMoney)
    {
        $returnMessage = array('code'=>1, 'data'=>NULL);
        if($totalMoney > 0){
            $returnMessage['data'] = $this->_randMoney($totalMoney, $totalPeople, $miniMoney);
        }elseif($totalMoney == 0){
            $returnMessage['data'] = array_fill(0, $totalPeople, 1);
        }else{
            $returnMessage['code'] = -1;
            $returnMessage['data'] = '参数传递有误，生成红包失败';
        }
		shuffle($returnMessage['data']);
        return $returnMessage;
    }

    /**
     * 参数无误，开始生成对应的红包金额
     */
    final private function _randMoney($totalMoney, $totalPeople, $miniMoney)
    {
        $data = array_fill(0, $totalPeople, $miniMoney);
        if($totalPeople > 1){
            foreach($data as $k => $v){
                if($k == $totalPeople -1){
                    $data[$k] = $totalMoney + $v;
                    break;
                }else {
                    if($totalMoney == 0) break;
                    $randMoney = rand(0, $totalMoney);
                    $totalMoney -= $randMoney;
                    $data[$k] = $randMoney + $v;
                }               
            }
        }
        return $data;
    }
}