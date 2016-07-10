<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商品服务类
 */
class KanjiaModel extends BaseModel {
	public function GetByid() {
		$kj_id=I('kj_id',0);		
		$sql="
select k.*,wu.* from cky_kanjia k inner join cky_member m on m.uid=k.uid
inner join cky_wx_user wu on wu.openid=m.OpenID
where kj_id=$kj_id";
	$m = M('kanjia');
	$rsarr= $m->query($sql);
	return $rsarr[0];		
	}
	
	public function Insert($uid, $wx_id, $qr_url ,$type)
	{
		$money=7888;
		if($type==2)
		{
			$money="5000.00";
		}
		 
		$map=array(
            'uid'=>$uid,
            'wx_id'=>$wx_id,
            'time'=>time(),
            'money'=>$money,
            'shengyumoney'=>$money,
            'count'=>0,
            'status'=>1,
            'qr_url'=>$qr_url,
            'type'=>$type,
        );
        //保存砍主信息
        $kj_id=M('kanjia')->add($map);
		return $kj_id;
	}
	
	
	public function GetAddMoney($type, $money, $shengyumoney)
	{
		$rule_map['type']=$type;
		$add_money=0;
		$area=M('kanjiarule')->where($rule_map)->order('kjr_yikan ASC')->select();
        if(empty($area)){
            //区间砍价金额
            if($shengyumoney>=3000){
                $money100=mt_rand(0,1)*100;
                $money10=mt_rand(0,9)*10;
                $money1=mt_rand(0,9)*1;
                $moneyf=mt_rand(1,9)*0.1;
                $moneyf2=mt_rand(0,0)*0.01;
            }
            if($shengyumoney>=1000&&$shengyumoney<3000)
            {
                $money100=mt_rand(0,0)*100;
                $money10=mt_rand(0,7)*10;
                $money1=mt_rand(0,9)*1;
                $moneyf=mt_rand(1,9)*0.1;
                $moneyf2=mt_rand(0,0)*0.01;
            }
            if($shengyumoney>=500&&$shengyumoney<1000){
                $money100=mt_rand(0,0)*100;
                $money10=mt_rand(0,0)*10;
                $money1=mt_rand(0,9)*1;
                $moneyf=mt_rand(1,9)*0.1;
                $moneyf2=mt_rand(0,0)*0.01;
            }
            if($shengyumoney<500){
                $money100=mt_rand(0,0)*100;
                $money10=mt_rand(0,0)*10;
                $money1=mt_rand(0,0)*1;
                $moneyf=mt_rand(0,9)*0.1;
                $moneyf2=mt_rand(1,9)*0.01;
            }
            $add_money=$money100+$money10+$money1+$moneyf+$moneyf2;
        }
        else{
            //计算已砍比例
            $yikan=$money-$shengyumoney;
            $yikan_bl=round(($yikan/$money*100),2);
            //找到它所在的区间
            foreach ($area as $key => $value) {
                if($yikan_bl<=$value['kjr_yikan']){
                    $min=$value['kjr_min'];
                    $max=$value['kjr_max'];
                    if($min>0&&$max>0){
                        $min=(int)$min;
                        $max=(int)$max;
                        $add_money1=mt_rand($min,$max-1);
                        $add_money2=mt_rand(1,99)/100;
                        $add_money=$add_money1+$add_money2;
                    }
                    elseif($min<=0&&$max>=0){
                        $min=(int)($min*100);
                        $max=(int)$max;
                        $add_money1=mt_rand(0,$max-1);
                        $add_money2=mt_rand($min,99)/100;
                        $add_money=$add_money1+$add_money2;
                    }
                    elseif($min<=0&&$max<=0){
                        $min=(int)($min*100);
                        $max=(int)($max*100);
                        $add_money1=mt_rand(0,0);
                        $add_money2=mt_rand($min,$max)/100;
                        $add_money=$add_money1+$add_money2;
                    }
                    else{
                        $add_money1=mt_rand(0,99);
                        $add_money2=mt_rand(1,99)/100;
                        $add_money=$add_money1+$add_money2;
                    }
                    break;
                }
                else{
                    $add_money2=mt_rand(1,99)/100;
                    $add_money=$add_money2;
                }
            }
        }
		return $add_money;
	}
	
	/*
	 * 获取帮砍记录
	 * */
	public function GetBangkanList($kj_id)
	{
		$sql="
		SELECT  b.bk_money,b.bk_time,b.bk_id,wu.* FROM cky_bangkan b
INNER JOIN cky_wx_user wu on wu.wx_id =b.wx_id
WHERE kj_id=$kj_id";
		$m = M('bangkan');
		$rsarr= $m->query($sql);
		return $rsarr;		
	}
	
	public function GetNewsPara($newstype, $WebRoot ,$kj_id, $appid='')
	{
		$title="";
		$discription="";
		$url="";
		$picurl="";
		if($newstype=="1")
		{
			$title= "[有人@你]您有一台Iphone6S尚未领取。";
			$discription="此链接是您的专属链接，请分享让朋友帮您砍价，由“粗卡”助力夺宝";
			$url="$WebRoot/index.php/M/Kanjia/index?kj_id=".$kj_id;
			$picurl="$WebRoot/Public/images/kj.png";                                   
		}
		else if($newstype=="2")
		{
			$title= "[有人@你]5万积分等你来拿！！";
			$discription="此链接是您的专属链接，请分享让朋友帮您砍价，由“粗卡”助力夺宝";
			$url="$WebRoot/index.php/M/Kanjia/index?kj_id=".$kj_id;
			$picurl="$WebRoot/Public/images/kj2.png";                                   
		} 
		else if($newstype=="reg")
		{
			$title= "点击注册送福气！";
			$discription="只需几步即可完成注册";
			$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=http%3A%2F%2F$WebRoot%2Findex.php%2FM%2FPerson%2Findex&response_type=code&scope=snsapi_base&state=STATE";
			$picurl="http://pic.qiantucdn.com/58pic/18/32/60/10c58PICXbP_1024.jpg";                                            
		} 
                                            
		$rs=array(
			'title'=>$title,
			"discription"=>$discription,
			"url"=>$url,
			"picurl"=>$picurl
		);
		return $rs;
	}
	
}