<?php
namespace Wechat\VModel;
use Think\Model;

class AccountMemberModel extends Model{
    public $viewFields=array(
        'account'=>array('*','_type'=>'left'),
        'member'=>array('*','_on'=>'account.uid=member.uid')
    );   
}
?>