<?php
namespace Wechat\Model;
use Think\Model;

class KanjiaUserModel extends Model{
    public $viewFields=array(
        'kanjia'=>array('*','_type'=>'left'),
        'wx_user'=>array('*','_on'=>'wx_user.wx_id=kanjia.wx_id'),
        'member'=>array('uid,password,mobile','_on'=>'member.uid=kanjia.uid'),
    );   
}
?>