<?php
 namespace Home\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 文章控制器
 */
class ArticlesAction extends BaseAction{
	
	/**
	 * 帮助中心
	 */
	public function index(){
		$m = D('Home/Articles');
    	$articleList = $m->getArticleList();
    	$obj["articleId"] = (int)I("articleId",0);
    	if(!$obj["articleId"]){
    		foreach($articleList as $key=> $articles){
    			$obj["articleId"] = $articles["articlecats"][0]["articleId"];
    			break;
    		}
    	}
    	
    	$article = $m->getArticle($obj);
    	$this->assign('articleList',$articleList);
    	$article['articleContent'] = htmlspecialchars_decode($article['articleContent']);
    	$this->assign('carticle',$article);
        $this->display("default/help_center");
        
	}
	
};
?>