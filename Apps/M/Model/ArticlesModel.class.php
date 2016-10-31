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
		return $this->field('articleId, catId,articleTitle,articleImg,articleImgThumb,isrecommend')->where('isShow=1 and isrecommend=1')
		->order('catId, articleId desc')->select();
    }
    
    public function queryRecommend() {
    	$pageSize = (int)I('pageSize', 4);
		$pageNo = intval(I('pageNo', 1));
		return $this->field('articleId, catId,articleTitle,articleImg,articleImgThumb,isrecommend,createTime')
					->where('isShow=1 and isrecommend=1')
					->order(' articleId desc')
					->page($pageNo, $pageSize)->select();
    }
    
	public function detail() {
		$articleId = I('id',0);
//		echo $articleId;
		$field = 'articleId, catId,articleTitle,articleContent,isrecommend,clicknum,appraisenum';
		$data = $this->field($field)->where(array('articleId'=> $articleId))->find();
		
//	echo	$this->getLastSql();
		return $data;
	}
	
	/**
	 * 相关资讯
	 * */
	public function GetArticlesOther($catId,$articleId){
		$sql=" 
			SELECT 
				articleId, catId,articleTitle,articleImg,articleImgThumb,isrecommend 
			FROM `cky_articles` art
			WHERE 1=1
				 and art.isShow = 1
				 and art.catId=  $catId		
				 and art.articleId <$articleId
			ORDER BY art.catId, art.articleId desc
			LIMIT 0,3
 
		 ";

		$rs = $this->query($sql);
		return $rs;
	 }
	 
    
	/**
	  * 获取列表
	  */
	  public function queryCats(){
		$m = M('article_cats');
		return $m->where('catFlag=1 and parentId=0')->order('catSort desc,catId asc')->select(); 
	  }
	  
	  
	  public function GetArticlesTop(){
		$sql="
SELECT	 
	*
FROM(
	SELECT		
		heyf_tmp.*,			
		IF (
			@pdept = heyf_tmp.catId ,@rank :=@rank + 1 ,@rank := 1
		) AS rank,
		@pdept := heyf_tmp.catId
	FROM
	(
			SELECT 
				articleId, catId,articleTitle,articleImg,articleImgThumb,isrecommend 
			FROM `cky_articles` art
			WHERE 1=1
				 and art.isShow = 1 				  
			ORDER BY art.catId, art.articleId desc
			LIMIT 0,2000
	) 
	heyf_tmp,
	(
		SELECT @pdept := NULL ,@rank := 0
	) a
) result
where rank <10
		 ";
//		 echo $sql;
		$rs = $this->query($sql);
		return $rs;
	 }
	 
	 
	 	/****
	 * 根据活动节点，查询商品
	 * ****/
	public function queryCatsArticles() {
 		$catsid	=I("catsid",0);
 		$articleId	=I("articleId",0);
 		$pageSize	=I("pageSize",10);
		$sql="
 			SELECT 
				articleId, catId,articleTitle,articleImg,articleImgThumb,isrecommend,createTime
			FROM `cky_articles` art
			WHERE 1=1
				 and art.isShow = 1 	
				 and isShow=1 
				 and (art.catId=$catsid or ($catsid=0 and isrecommend=1))
				 and art.articleId<$articleId
			ORDER BY 
				 art.articleId desc
			LIMIT 0,$pageSize
		 ";
		$rs = $this->query($sql);
//		echo $this->getLastSql();
		return $rs;
	}
	 
};
?>