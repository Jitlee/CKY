<?php
namespace Wechat\VModel;
use Think\Model;

class BangkanUserModel extends Model{
    public $viewFields=array(
        'bangkan'=>array('*','_type'=>'left'),
        'wx_user'=>array('*','_on'=>'wx_user.wx_id=bangkan.wx_id'),
    );   
}
?>