<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 广告服务类
 */
class ArticlesModel extends BaseModel {
    public function queryByType($type) {
		return $this->field('articleId, catId,articleTitle,articleImg,articleImgThumb,isrecommend')->where('isShow=1 and isrecommend=1')->order('adSort')->select();
    }
    
    public function queryRecommend() {
    	$pageSize = (int)I('pageSize', 10);
		$pageNo = intval(I('pageNo', 1));
		return $this->field('articleId, catId,articleTitle,articleImg,articleImgThumb,isrecommend,createTime')
					->where('isShow=1 and isrecommend=1')
					->order('istop,sort,clicknum desc')
					->page($pageNo, $pageSize)->select();
    }
    
	public function detail() {
		$articleId = I('id');
		$field = 'articleId, catId,articleTitle,articleContent,isrecommend,clicknum,appraisenum';
		$data = $this->field($field)->where(array('articleId'=> $articleId))->find();
		return $data;
	}
    
	/**
	  * 获取列表
	  */
	  public function queryCats(){
		$m = M('article_cats');
		return $m->where('catFlag=1 and parentId=0')->order('catSort desc,catId asc')->select(); 
	  }
	  
};
?>