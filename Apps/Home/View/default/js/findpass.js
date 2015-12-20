$("#getPhoneVerify").click(function(){
	if(findPassTime>0)return;
	var params = {};
	params.loginName = $("#loginName").val();
	params.time = Math.random();
	params.userPhone = $("#userPhone").val();
	if ($("#userPhone").val() == '') {
	    RTC.msg('你没有预留手机号码，不能通过手机号码找回，请尝试用邮箱找回！',{icon:5});
	}else{
		if(RTC.SMS_VERFY=='1'){
			var html = [];
			html.push('<table class="rtc-smsverfy"><tr><td width="80" align="right">');
			html.push('验证码：</td><td><input type="text" id="smsVerfy" size="12" class="rtc-text" maxLength="8">');
			html.push('<img style="vertical-align:middle;cursor:pointer;height:39px;" class="verifyImg" src="'+domainURL+'/Apps/Home/View/default/images/clickForVerify.png" title="刷新验证码" onclick="javascript:getVerify()"/>');
			html.push('</td></tr></table>');
			layer.open({
				title:'请输入验证码',
			    type: 1,
			    area: ['420px', '150px'], //宽高
			    content: html.join(''),
			    btn: ['发送验证码', '取消'],
			    success: function(layero, index){
			    	getVerify();
			    },
			    yes: function(index, layero){
			    	params.smsVerfy = $.trim($('#smsVerfy').val());
			    	if(params.smsVerfy==''){
				    	RTC.msg('请输入验证码!', {icon: 5});
				   		return;
				    }
			    	$.post(Think.U('Home/Users/getPhoneVerify'),params,function(data,textStatus){
			   			var json = RTC.toJson(data);
			   			if(json.status!=1){
							RTC.msg(json.msg, {icon: 5});
							time = 0;
							isSend = false;
							getVerify();
						}if(json.status==1){
							layer.close(index);
							RTC.msg('短信已发送，请注意查收');
							findPassTime = data.time;
		                    $("#getPhoneVerify").val(data.time).click(function(){return false;});
		                    resetVerify();
			   			}
			   		});
			    }
			});
		}else{
			$.post(Think.U('Home/Users/getPhoneVerify'),params,function(data,textStatus){
	   			var json = RTC.toJson(data);
	   			if(json.status!=1){
					RTC.msg(json.msg, {icon: 5});
					time = 0;
					isSend = false;
				}if(json.status==1){
					RTC.msg('短信已发送，请注意查收');
					findPassTime = data.time;
                    $("#getPhoneVerify").val(data.time).click(function(){return false;});
                    resetVerify();
	   			}
	   		});
		}
	}
});
$("#sendEmail").click(function(){
	var msg= layer.load('正在发送邮件，请稍后...', 3);
	$.getJSON(Think.U('Home/Users/getEmailVerify'), { loginName: $("#loginName").val(), time: Math.random(),userPhone:$("#userPhone").val()},
	          function(data){
	        	layer.close(msg);
	            if (data.status==1){
	                $("#sendEmail").val('请查看邮件！并进行激活操作');
	                $("#sendEmail").click(function(){return false;});
	            }else{
	                $("#sendEmail").val('发送邮件失败！请重新发送！');
	            }
	});
})
/**
 * 重置手机验证码获取时间
 */
function resetVerify(){
	var item = $("#getPhoneVerify");      
	var flag = setInterval(function(){
	            if (findPassTime > 0) {
	                item.val(--findPassTime);
	            }else{
	                item.val("点击获取");
	                clearInterval(flag);
	            }
	},1000)
}