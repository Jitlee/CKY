<?php
namespace Home\Model;
/**
 * ============================================================================
 * 粗卡云:
 * 联系方式:
 * ============================================================================
 * 友情连接服务类
 */
class FriendlinksModel extends BaseModel {
	/**
     * 获取友情链接
     */
	public function getFriendlinks(){
		return $this->cache('RTC_CACHE_FRIENDLINK_000',31536000)->order('friendlinkSort asc')->select();
	}
}