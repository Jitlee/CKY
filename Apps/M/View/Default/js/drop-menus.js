(function() {
	function onblurclick(evt) {
		evt.stopPropagation();
		evt.preventDefault();
		$(".cky-drop-body.cky-active").removeClass("cky-active");
		$(".cky-drop-content.cky-active").removeClass("cky-active");
	}
	$.fn.extend({
		/**
		 * 下拉菜单 
		 */
		dropMenu: function(options) {
			var ths = this;
			var options = $.extend({
				auto: true,
				idProperty: "id",
				nameProperty: "name",
				parentIdProperty: "parentId",
				parentId: 0,
				defaultText: "不限"
			}, options);
			var container = $(".cky-drop-body");
			if(container.length == 0) {
				container = $("<div class=\"cky-drop-body cky-hidden\">");
				$(document.body).append(container);
			}
			var defaultName = this.text();
			
			var content = $("<div class=\"cky-drop-content cky-hidden\">");
			container.append(content);
			var table = $("<div class=\"cky-drop-table\">");
			content.append(table);
			
			var col = $("<ul class=\"cky-drop-table-col\">");
			table.append(col);
			
			// 选择事件
			col.on("click", ".cky-drop-table-cell", function(evt) {
				var $this = $(this);
				if($this.hasClass("cky-active")) {
					return;
				}
				$(".cky-drop-table-cell.cky-active").removeClass("cky-active");
				$this.addClass("cky-active");
				if(_list && _list.length > 0 && typeof options.callback == "function") {
					var item = _list[$this.index()];
					if(item[options.idProperty] == 0) {
						ths.text(defaultName);
						ths.removeClass("cky-active");
					} else {
						ths.addClass("cky-active");
						ths.text(item[options.nameProperty]);
					}
					options.callback.call(ths, item);
				}
			})
			
			ths.click(function(evt) {
				evt.stopPropagation();
				evt.preventDefault();
				
				$(window).unbind("click", onblurclick);
				if(content.hasClass("cky-active")) {
					container.removeClass("cky-active");
					content.removeClass("cky-active");
				} else {
					var offset = ths.offset();
					container.css("top", offset.top + ths.height());
					container.addClass("cky-active");
					$(".cky-drop-content.cky-active").removeClass("cky-active");
					content.addClass("cky-active");
					$(window).bind("click", onblurclick);
					
					if(!loaded && !options.auto) {
						loadData();
					}
				}
			});
			
			var loaded = false;
			var isLoadding = false;
			var _list;
			function loadData() {
				if(options.menus) {
					renderList(options.menus);
				} else {
					if(isLoadding) {
						return;
					}
					isLoadding = true;
					var data = {};
					if(typeof options.parentId == "function") {
						data[options.parentIdProperty] = options.parentId();
					} else {
						data[options.parentIdProperty] = options.parentId;
					}
					$.getJSON(options.api, data, function(list) {
						renderList(list);
					});
				}
			}
			
			function renderList(list) {
				var defaultData = {};
				defaultData[options.idProperty] = 0;
				defaultData[options.nameProperty] = options.defaultText;
				list.unshift(defaultData);
				_list = list;
				$.each(list, function() {
					var li = $("<li>")
						.addClass("cky-drop-table-cell")
						.text(this[options.nameProperty]);
					li.appendTo(col);
				});
				col.children().eq(0).addClass("cky-active");
				loaded = true;
				isLoadding = false;
			}
			
			if(options.auto) {
				loadData();
			}
			return ths;
		}
	});
})();
