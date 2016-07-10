<?php
namespace Wechat\VModel;
use Think\Model;

class MemberRankModel extends Model{
    public $viewFields=array(
        'member'=>array('*','_type'=>'left'),
        'rank'=>array('sort','_on'=>'member.rank_id=rank.rank_id')
    );   
}
?>