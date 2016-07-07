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
	/* 添加砍价规则*/
	public function kanjia_add(){
		if(!IS_AJAX)$this->error('非法操作');
		$data=I('post.');
		$data['create_time']=date('Y-m-d h:i:s',time());
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

	/*参与活动列表*/
    public function canyu(){
    	// 分页
    	$pageSize = 20;
		$pageNum = (int)I("p",1);
    	
		$map['type']=1;
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

		$list=$db->where($map)->page($pageNum, $pageSize)->select();
		foreach ($list as $key => $value) {
			$list[$key]['bangkan_count']=M('bangkan')->where(array('kj_id'=>$vlaue['kj_id']))->count();
			$list[$key]['shengyubili']=round(($value['shengyumoney']/$value['money'])*100,2);
		}
    	$this->list=$list;
    	$this->assign('title','参与活动者列表');
    	$this->assign('pid','activity');
    	$this->assign('mid','activitymgr #canyu');
    	$this->display();
    }

    public function zhongjiang(){
		$map['type']=1;
    	$list=M('kanzhong')->where($map)->select();
		$this->list=$list;
		$this->assign('title', '中奖用户');
		$this->assign('action', U('index', '', ''));
		$this->assign('pid', 'activity');
		$this->assign('mid', 'activitymgr #zhongjiang');
		$this->display();
    }
    public function zhongjian_add(){
		if(!IS_AJAX)$this->error('非法操作');
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
		if(!IS_AJAX)$this->error('非法操作');
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
		if(!IS_AJAX)$this->error('非法操作');
		$data=I('post.zj_id');
		if(false!==M('kanzhong')->where(array('zj_id'=>$data))->delete()){
			$this->ajaxReturn(array('status'=>1,'info'=>'删除成功'));
		}
		else{
			$this->ajaxReturn(array('status'=>0,'info'=>'删除失败，请刷新后重试'));
		}

	}

	/*积分砍价规则列表*/
	public function ffindex(){
		$map['status']=1;
		$map['type']=2;
		$list=M('kanjiarule')->where($map)->select();
		$this->list=$list;
		$this->assign('title', '积分活动金额设置');
		$this->assign('action', U('ffindex', '', ''));
		$this->assign('pid', 'activity');
		$this->assign('mid', 'activitymgr #ffindex');
		$this->display();
	}
		/*积分参与活动列表*/
    public function ffcanyu(){
    	$pageSize = 20;
		$pageNum = (int)I("p",1);
    	// 分页
    	$map['type']=2;
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

		$list=$db->where($map)->page($pageNum, $pageSize)->select();
		foreach ($list as $key => $value) {
			$list[$key]['bangkan_count']=M('bangkan')->where(array('kj_id'=>$vlaue['kj_id']))->count();
			$list[$key]['shengyubili']=round(($value['shengyumoney']/$value['money'])*100,2);
		}
    	$this->list=$list;
    	$this->assign('title','积分参与活动者列表');
    	$this->assign('pid','activity');
    	$this->assign('mid','activitymgr #ffcanyu');
    	$this->display();
    }
    /*积分中奖记录*/
    public function ffzhongjiang(){
    	$map['type']=2;
    	$list=M('kanzhong')->where($map)->select();
		$this->list=$list;
		$this->assign('title', '积分中奖用户');
		$this->assign('action', U('ffzhongjiang', '', ''));
		$this->assign('pid', 'activity');
		$this->assign('mid', 'activitymgr #ffzhongjiang');
		$this->display();
    }
}