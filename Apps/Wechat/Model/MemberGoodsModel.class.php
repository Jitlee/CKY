<?php
namespace Wechat\VModel;
use Think\Model;

class MemberGoodsModel extends Model{
    public $viewFields=array(
        'member_miaosha'=>array('*','_type'=>'left'),
        'miaosha'=>array('*','_on'=>'member_miaosha.gid=miaosha.gid')
    );   
}
?>