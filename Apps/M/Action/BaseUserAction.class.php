<?php
namespace M\Action;
use Think\Controller;
 

class BaseUserAction extends BaseAction {
	protected function _initialize() {
 		test_login();
	}
}