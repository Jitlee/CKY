$(function() {
	// 退回按钮
	$(".cky-back").click(function() {
		window.history.back();		
	});
	
	// 文本输入
	$("textarea[maxlength]").each(function() {
		var $this = $(this);
		var maxlen = $this.attr("maxlength");
		var len = $this.val().length;
		this.__tips = $("<p class=\"max-length-tips\">").text(len + " / " + maxlen);
		$this.after(this.__tips);
	}).bind("input", function() {
		var $this = $(this);
		var len = $this.val().length;
		var maxlen = Number($this.attr("maxlength"));
		this.__tips.text(len + " / " + maxlen);
	});
});

// cky扩展js
var cky = {
	/**
	 * 格式化订单状态
	 */
	formatOrderStatus: function(orderStatus) {
		orderStatus = Number(orderStatus);
		switch(orderStatus) {
			case -2:
				return "订单已关闭";
			case -1:
				return "订单已取消";
			case 0:
				return "待支付";
			case 1:
				return "订单已支付";
			case 2:
				return "商家已受理";
			case 3:
				return "配送中";
			case 4:
				return "已送达";
			case 5:
				return "已到货";
			case 6:
				return "订单已完成";
			default:
				return "订单已结束";
		}
	},
	
	/**
	 * 扩展包装html5storage, 每一项都可以设置失效时间(s)
	 */
	storage: {
		getItem: function(key) {
			var data = $.localStorage.getItem(key);
			if(data) {
				data = JSON.parse(data);
				var expires = data.expires;
				var time = data.time;
				if(expires > 0 && time > 0) {
					var now = new Date().getTime();
					if(now - time < expires * 1000) {
						return data.item;
					}
					return null;
				}
				return data;
			}
			return null;
		},
		setItem: function(key, item, expires) {
			var data = item;
			if(expires > 0) {
				data = {};
				data.item = item;
				data.expires = expires;
				data.time = new Date().getTime();
			}
			$.localStorage.setItem(key, JSON.stringify(data));
		},
		removeItem: function(key) {
			$.localStorage.removeItem(key);
		}
	},
	
	/**
	 * 关闭所有弹框
	 * @param {Object} id
	 */
	close: function(id) {
		layer.close(id);
	},
	
	/**
	 * 关闭所有弹框
	 * @param {Object} id
	 */
	closeAll: function(id) {
		layer.closeAll();
	},
	
	/**
	 * 弹出自动消失的提示框
	 * @param {Object} msg
	 */
	toast: function(msg) {
		return layer.open({
		    content: msg,
		    shade: false,
		    style: 'background-color:rgba(0,0,0,0.5); color:#FFF; border:none;',
		    time: 2
		});
	},
	
	loadding: function() {
		return layer.open({
		    type: 2
		});
	},
	
	alert: function(msg, callback) {
		return layer.open({
		    title: '提示',
		    content: msg,
		   	btn: ["确定"],
		    yes: function(index){
		    	layer.close(index);
		    	if(typeof callback == "function") {
			    	callback();
		    	}
		    }
		});
	}
};

// 下拉框设定
$.fn.select = function(list) {
	var $this = this;
	$this.unbind();
	
	this.click(function() {
		var html = [];
		html.push("<ul class=\"mui-table-view mui-input-group cky-select-list\">");
		$.each(list, function(i) {
			html.push("<li id=\"cky-select-")
			html.push(this.key);
			html.push("\" class=\"mui-table-view-cell mui-radio\"><a><label>");
			html.push(this.text);
			html.push("<input name=\"cky-select\" type=\"radio\" value=\"");
			html.push(this.key);
			html.push("\"/></label></a></li>");
		});
		html.push("</ul>");
		
		layer.open({
		    type: 1,
		    title: null,
		    closeBtn: 0, //不显示关闭按钮
		    shift: 2,
		    shadeClose: true, //开启遮罩关闭
		    content: html.join(""),
		    success: onopen
		});
	});
	
	function onopen(elem) {
		var selectedValue = $this.attr("selectedValue");
		if(selectedValue) {
			$("#cky-select-" + selectedValue + " input", elem).prop("checked", true);
		} else {
			$("input:first", elem).prop("checked", true);
		}
		
		$(elem).on("click", "li", function() {
			var key = $("input", this).val();
			var text = $("label", this).text();
			$this.attr("selectedValue", key);
			$this.text(text);
			
			layer.closeAll();
		})
	}
	
	// 设置初始值
	var selectedValue = $this.attr("selectedValue");
	if(selectedValue) {
		var len = list.length;
		while(len--) {
			if(list[len].key == selectedValue) {
				$this.text(list[len].text);
				break;
			}
		}
	} else if(list.length > 0){
		$this.text(list[0].text);
		$this.attr("selectedValue", list[0].key);
	} else {
		$this.attr("selecedValue", null);
		$this.text("请选择");
	}
	return $this;
}


