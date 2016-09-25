<?php
namespace Admin\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 首页（默认）控制器
 */
class KanjiaAction extends BaseAction {
		
	/*砍价规则列表*/
	public function index(){
		$map['status']=1;
		$map['type']=1;
		$list=M('kanjiarule')->where($map)->order('kjr_yikan')->select();
		$this->list=$list;
		$this->assign('title', '活动金额设置');
		$this->assign('action', U('index', '', ''));
		$this->assign('pid', 'activity');
		$this->assign('mid', 'activitymgr #index');
		
		
		$this->display();
	}
	
	public function paralist()
	{
		$this->isLogin();
		$this->checkAjaxPrivelege('gggl_00');
		self::RTCAssigns(); 
		
		$m = D('Admin/KanjiaPara');
    	$page = $m->queryByPage();
		 
    	$pager = new \Think\Page($page['total'],$page['pageSize']);
    	$pager->setConfig('header','');
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
		$this->display();
	}
	
	public function paraedit()
	{
		$m = D('Admin/KanjiaPara');
    	$object = array();
    	if(I('kjid',0)>0){
    		$this->checkPrivelege('gggl_02');
    		$object = $m->get();
    	}else{
    		$this->checkPrivelege('gggl_01');
    		$object = $m->getModel(); 
    	}
		//卡券加载
		$mshop = D('Admin/Ticket');
		$shops=$mshop->queryByList();		
	    $this->assign('tickets',$shops);
		
    	$this->assign('object',$object);
		$this->display();
	}
	public function paraeditsave(){
		$this->isAjaxLogin();
		$m = D('Admin/KanjiaPara');
    	$rs = array();
    	if(I('kjid',0)>0){
    		$this->checkAjaxPrivelege('gggl_02');
    		$rs = $m->edit();
    	}else{
    		$this->checkAjaxPrivelege('gggl_01');
    		$rs = $m->insert();
    	}
    	$this->ajaxReturn($rs);
	}
	public function paradel(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('gggl_03');
		$m = D('Admin/KanjiaPara');
    	$rs = $m->del();
    	$this->ajaxReturn($rs);
	}
	
	public function kanjiaitemdel(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('gggl_03');
		$m = D('Admin/KanjiaPara');
    	$rs = $m->kanjiaItemDel();
    	$this->ajaxReturn($rs);
	}
	
	/* 添加砍价规则*/
	public function kanjia_add(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('gggl_03');
		$data=I('post.');
		//$data['create_time']=date('Y-m-d h:i:s');
		$rs=M('kanjiarule')->add($data);
		if($rs!==false){
			$this->ajaxReturn(array('status'=>1,'info'=>'添加成功'));
		}
		else{
			$this->ajaxReturn(array('status'=>0,'info'=>'添加失败，请稍后重试'));
		}
	}
	/* 编辑等级*/
	public function kanjia_edit(){
		if(!IS_AJAX)$this->error('非法操作');
		$data=I('post.');
		$rs=M('kanjiarule')->where(array('kjr_id'=>$data['kjr_id']))->save($data);
		if($rs!==false){
			$this->ajaxReturn(array('status'=>1,'info'=>'编辑成功'));
		}
		else{
			$this->ajaxReturn(array('status'=>0,'info'=>'编辑失败，请稍后重试'));
		}
	}

	
	/*删除等级*/
	public function del(){
		if(!IS_AJAX)$this->error('非法操作');
		$data=I('post.kjr_id');
		if(false!==M('kanjiarule')->where(array('kjr_id'=>$data))->delete()){
			$this->ajaxReturn(array('status'=>1,'info'=>'删除成功'));
		}
		else{
			$this->ajaxReturn(array('status'=>0,'info'=>'删除失败，请刷新后重试'));
		}

	}

	 
	
	 public function zhongjian_add(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('gggl_03');
		$data=I('post.');
		$data['time']=time();
		$rs=M('kanzhong')->add($data);
		if($rs!==false){
			$this->ajaxReturn(array('status'=>1,'info'=>'添加成功'));
		}
		else{
			$this->ajaxReturn(array('status'=>0,'info'=>'添加失败，请稍后重试'));
		}
	}
	public function zhongjian_edit(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('gggl_03');
		$data=I('post.');
		$rs=M('kanzhong')->where(array('zj_id'=>$data['zj_id']))->save($data);
		if($rs!==false){
			$this->ajaxReturn(array('status'=>1,'info'=>'编辑成功'));
		}
		else{
			$this->ajaxReturn(array('status'=>0,'info'=>'编辑失败，请稍后重试'));
		}
	}
	public function kanzhong_del(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('gggl_03');
		$data=I('post.zj_id');
		if(false!==M('kanzhong')->where(array('zj_id'=>$data))->delete()){
			$this->ajaxReturn(array('status'=>1,'info'=>'删除成功'));
		}
		else{
			$this->ajaxReturn(array('status'=>0,'info'=>'删除失败，请刷新后重试'));
		}

	}
	
	/*积分砍价规则列表*/
	 public function sendprize(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('gggl_03');
		$kj_id=I('post.kj_id');
		///false!==M('kanzhong')->where(array('zj_id'=>$data))->delete()
		//获取砍价相关信息
		$mdbpara = D('Admin/KanjiaPara');
		$KjPara=$mdbpara->GetKjParaByKjid($kj_id);
		
		//更新状态
		$Mobile=$KjPara["Mobile"];
		$prizeid=$KjPara["prizeid"];
		$uid=$KjPara["uid"];
		if(strlen($prizeid) >10)
		{
			//向一卡易申请发送卡券，并同步数据
			$m = D('M/OneCardTick');
			$rst  = $m->SendTick($prizeid,$Mobile,$uid);			 
			if($rst == FALSE)
			{
				$this->ajaxReturn(array('status'=>0,'info'=>'发送奖品失败，请刷新后重试..'));
			}
		}
		$rst  = $mdbpara->updatePrizeid($kj_id);
		if($rst!==false){
			$this->ajaxReturn(array('status'=>1,'info'=>'发送奖品成功'));
			exit;
		}
		$this->ajaxReturn(array('status'=>0,'info'=>'发送奖品失败，请刷新后重试.'));		
	}
	/*积分砍价规则列表*/
	public function ffindex(){
		$map['status']=1;
		$kjcode	=I("kjcode");
		$map['type']=$kjcode;
		$list=M('kanjiarule')->where($map)->select();
		$this->list=$list;
		$this->assign('title', '积分活动金额设置');
		$this->assign('action', U('ffindex', '', ''));
		$this->assign('kjcode', $kjcode);
		$this->display();
	}
		/*积分参与活动列表*/
    public function ffcanyu(){
    	$pageSize = 20;
		$pageNum = (int)I("p",1);
		$kjcode	=I("kjcode");
    	// 分页
    	$map['type']=$kjcode;
		$db = M('kanjia');
		$count = $db->where($map)->count();
		if(!$pageSize) {
			$pageSize = 20;
		}
		$pageNum = intval($pageNum);
		$pageCount = ceil($count / $pageSize);
		if($pageNum > $pageCount) {
			$pageNum = $pageCount;
		} 
		
    	$pager = new \Think\Page($count,$pageSize);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);

		$list=$db
			->join('kj inner join cky_wx_user u on u.wx_id=kj.wx_id')
			->join('inner join cky_member m on m.uid=kj.uid')
			->where($map)->page($pageNum, $pageSize)->select();
		foreach ($list as $key => $value) {
			$list[$key]['bangkan_count']=M('bangkan')->where(array('kj_id'=>$vlaue['kj_id']))->count();
			$shengyu=round(($value['shengyumoney']/$value['money'])*100,2);
			$list[$key]['shengyubili']=$shengyu;
			$candel=0;
			if($shengyu > 95)
			{
				$candel=1;
			}
			$list[$key]['candel']=$candel;
		}
    	$this->list=$list;
    	$this->assign('title','积分参与活动者列表');
    	$this->assign('kjcode', $kjcode);
    	$this->display();
    }
    /*积分中奖记录*/
    public function ffzhongjiang(){
    	$kjcode	=I("kjcode");
    	$map['type']=$kjcode;
    	$list=M('kanzhong')->where($map)->select();
		$this->list=$list;
		$this->assign('title', '中奖用户');
		$this->assign('kjcode', $kjcode);
		$this->assign('action', U('ffzhongjiang', '', ''));
	
		$this->display();
    }
}