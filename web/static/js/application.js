(function( factory ) {
	if ( typeof define === "function" && define.amd ) {
		require([
			"jquery",
			"artDialog",
			], function(){
			factory();
		});
	}
	else {
		factory();
	}
})(function(){

	/**
	 * 放置一些全局范围内初始化的脚本或组件
	 */
	window.Ibosapp = {
		op: {
			// 检查用户是否处于登入状态
			checkIsLogin: function(callback){
				$.get(Ibos.app.url("user/default/checklogin"), callback, "json");
			},

			ajaxLogin: function(info, callback){
				info = $.extend({
					formhash: Ibos.app.g("formHash"),
					logintype: "1"
				}, info);

				$.post(Ibos.app.url("user/default/ajaxlogin"), info, callback, "json");
			}
		},
		// 表单验证
		formValidate: {
			pageError: function(msg, elem, errorList){
				var data;
				// 如果设置了errorFocus(即出错后focus到第一个出错的控件)
				// 则判断控件的类型，对一些特殊控件做出相应的处理
				if(this.errorFocus){
					// 如果是下拉控件、选人控件
					if(data = $.data(elem, "select2")){
						data.focus();
					}
				}
			},
			setGroupState: function(input, state){
				var $group = $(input).closest(".input-group"),
					errorCls = "input-group-error",
					correctCls = "input-group-correct";

				switch(state) {
					case "correct":
						$group.removeClass(errorCls).addClass(correctCls);
						break;
					case "error":
						$group.removeClass(correctCls).addClass(errorCls);
						break;
					default:
						$group.removeClass(correctCls).removeClass(errorCls);
				}
			}
		},

		dropnotify: {
			interval: 60000,
			init: function(dropclass, containerId, url) {
				this.dropclass = dropclass;
				this.$container = $("#" + containerId);
				this.startCount();
			},
			//显示父对象
			show: function() {
				this.$container.show();
			},

			//隐藏
			hide: function() {
				this.$container.hide();
				// 在没有新提醒前，两小时内不再显示提醒框
				if(this.data.unread_total !== 0) {
					U.setCookie('dropnotify', $.toJSON(this.data), 7200);
					$(this).trigger("ignore");
				}
			},
			getCount: function(){
				var _this = this;
				$.get('/manage/user_message/readcount', function(res) {
					var data = res.data,
						notifycookie;

					if (res && ("undefined" === typeof(res.data) || res.status !== 1)) {
						return false;
					} else {
						notifycookie = U.getCookie('dropnotify');
						// 若用户关闭了提示框(即cookie中有dropnotify字段)时，只有当有新消息时才显示
						if(notifycookie && U.isEqualObject($.parseJSON(notifycookie), res.data)) {
							return false;
						}
						// 若新消息与上次相同，即自次消息以来没有发生变化，则返回
						if(U.isEqualObject(_this._prev, res.data)) {
							return false;
						}

						// 清空cookie
						U.setCookie('dropnotify', '');

						_this.data = res.data;

						if (data.unread_total <= 0) {
							_this.hide();
						} else {
							_this.show();
						}

						$('.' + _this.dropclass + " li").each(function() {
							var name = $(this).attr('rel');
							num = data[name];
							if (num > 0) {
								$(this).find('span').html("<strong class='xco'>" + num + "</strong> " + Ibos.l("DN." + name.toUpperCase()));
								$(this).show();
							} else {
								$(this).hide();
							}
						});
						_this._prev = res.data;

						$(_this).trigger("new", res.data);
					}
				}, 'json');
			},

			startCount: function() {
				var _this = this;
				setInterval(function(){
					_this.getCount();
				}, _this.interval);
				this.getCount();
			}
		}
	};

	// 新提醒
	$(Ibosapp.dropnotify).on("new", function(evt, data){
		// 当有未读新提醒时，标题闪动
		if(data.unread_total > 0) {
			Ui.blinkTitle(Ibos.l("NOTIFY.UNREAD_TOTAL", { count: data.unread_total }));
			// 浏览器窗口通知
			// Ui.Notification.show(Ibos.l("NOTIFY.UNREAD_TOTAL", { count: data.unread_total }), Ibos.l("NOTIFY.TO_VIEW"));
		} else {
			Ui.blinkTitle(false);
		}
	})
	.on("ignore", function(evt){
		Ui.blinkTitle(false);
	});


	$(function(){
		// 用户登录状态卡
		(function () {
			var $loginCtrl = $("#user_login_ctrl"),
				$loginCard = $("#user_login_card");

			var menu = new Ui.PopMenu($loginCtrl, $loginCard, {
				position: {
					at: "right bottom",
					my: "right top+25",
					of: $loginCtrl
				},
				showDelay: 0,
				animate: true
			});

			$loginCard.on("show", function() {
				$loginCtrl.addClass("active");
			})
			.on("hide", function(){
				$loginCtrl.removeClass("active")
			})
		})();

		(function () {
			var $funCtrl = $("#user_fun_ctrl"),
				$funCard = $("#user_fun_card");

			var menu = new Ui.PopMenu($funCtrl, $funCard, {
				position: {
					at: "right bottom",
					my: "right top+25",
					of: $funCtrl
				},
				showDelay: 0,
				animate: true
			});

			$funCard.on("show", function() {
				$funCtrl.addClass("active");
			})
			.on("hide", function(){
				$funCtrl.removeClass("active")
			})
		})();


		// 二级导航
		$("#nv li").each(function(){
			var $ctrl = $(this),
				$menu = $($ctrl.attr("data-target"));

			if($menu.length) {
				new Ui.PopMenu($ctrl, $menu, {
					position: {
						my: "left top-10"
					},
					hideDelay: 0,
					showDelay: 0
				});

				$menu.on({
					"show": function(evt){ $ctrl.addClass("open") },
					"hide": function(evt){ $ctrl.removeClass("open") }
				});
			}
		}).on("click", function(){
			var $this = $(this),
				target = $this.data("target"),
				hasChild = $(target).length;
			if(!hasChild){
				var isNew = $this.find(".o-new-nav-tip").length,
					id = $this.data("id");
				if(isNew){
					var url = "";
					$.post(url, {id : id}, function(res){});
				}
			}
		});

		//新增二级导航的操作
		$("#subnv_wrap .subnv li").on("click", function(){
			var $this = $(this),
				isNew = $this.find(".o-new-nav-tip").length,
				id = $this.data("id"),
				pid = $this.parent().data("pid");
			if(isNew){
				var newNavLength = $this.parent().find(".o-new-nav-tip").length - 1,
					param = {id: id, pid: pid, newlength: newNavLength},
					url = "";
				$.post(url, param, function(res){});
			}
		});


		// 滑动导航
		(function(){
			var navScroll = {
				$navWrap: $("#nvw"),

				maxScrollLeft: 0, // 导航条左移极限值

				wrapWidth: 0, // 容器宽度

				// 计算内部子节点总宽度
				_getTotalWidth: function(){
					var totalWidth = 0;

					this.$navWrap.find("li").each(function(){
						totalWidth += $(this).outerWidth();
					});

					return totalWidth;
				},

				init: function(){
					var _this = this,
						cooldown = false; // 减少 mousemove 执行次数

					var wrapOffsetLeft, // 容器左侧页面距离
						totalWidth; // 导航项总宽度和，实际导航宽度

					if(!this.$navWrap.length) {
						return false;
					}

					this.wrapWidth = this.$navWrap.outerWidth();
					totalWidth = this._getTotalWidth();

					this.maxScrollLeft =  totalWidth - this.wrapWidth;

					// 如果左移极限值小于值，即子节点的宽度小于容器宽度，则没必要出现滑动导航
					if(this.maxScrollLeft <= 0){
						return false;
					}

					wrapOffsetLeft = this.$navWrap.offset().left;

					this.$navWrap.on({
						"mousemove": function(e){
							// 如果在 mousemove 事件冷却时间内，则直接返回 false
							if(cooldown){ return false; }

							var elem = this,
								x = e.clientX - wrapOffsetLeft, // x 为鼠标实际在容器内的 x 坐标位置
								scrollLeft = 0;

							// 前后各有一段拉伸区间，默认 80px
							// 即前后 80px 导航条始终保持在头尾位置
							if(x > _this.wrapWidth - 80) {
								scrollLeft = totalWidth - _this.wrapWidth;
							} else if(x > 80) {
								scrollLeft = (totalWidth - _this.wrapWidth) * (x - 80) / (_this.wrapWidth - 80 * 2);
							}

							// 如果 scrollLeft 值没有变化，则不继续执行
							if(_this.$navWrap.scrollLeft() == scrollLeft) {
								return false;
							}

							// 使用动画缓冲
							_this.$navWrap.stop().animate({ "scrollLeft": scrollLeft }, 100, function(){
								_this.positionMenu();
							});

							_this.positionMenu();

							cooldown = true;

							setTimeout(function(){
								cooldown = false;
							}, 50);
						}
					})
				},

				// 重定位活动中的菜单
				positionMenu: function(){
					// 找到当前活动中的导航项及其对应
					var $activeNav = this.$navWrap.find("li.open");
					var $activeMenu;

					if($activeNav.length) {

						$activeMenu = $($activeNav.attr("data-target"));

						if($activeMenu.length) {
							$activeMenu.position({
								at: "left bottom",
								my: "left top-10",
								of: $activeNav
							})
						}
					}
				}
			}

			navScroll.init();
		})();

		(function(){
			var mark = {
				subNavMark: function(){}
			}

			var $navLink = $("#subnv_wrap .subnv li"),
				$newLink = $navLink.filter(function(index) {
					return $(this, ".o-new-nav-tip").length == 1;
				});
			$newLink.on("click", function(){

			});

		})();


		// 积分提示
		if(Ibos.app.g("creditRemind") && U.getCookie("creditremind")){
			Ui.showCreditPrompt();
		};

		// 全局表单提交，跳转，等成功提示，cookie来自各表单或消息提示，跳转的成功页等
		if(U.getCookie("globalRemind") && U.getCookie("globalRemindType")){
			Ui.tip(decodeURI(U.getCookie("globalRemind")), U.getCookie("globalRemindType"));
			U.setCookie('globalRemind', '');
			U.setCookie('globalRemindType', '');
		};
	});


	/**
	 * 打开文件上传对话框
	 * @method uploadDialog
	 * @param  {Object} param 上传控件参数
	 * @return {[type]}
	 */
	Ibos.uploadDialog = function(param){
		var dialog = Ui.getDialog("d_upload");
		if(dialog) {
			dialog.position("50%", "50%");
		} else {
			// 调整对话框的最小 zIndex
			artDialog.defaults.zIndex = 2010;
			dialog = Ui.ajaxDialog(Ibos.app.url('main/default/getUploadDlg'), {
				id: "d_upload",
				title: Ibos.l("UPLOAD.MANAGE"),
				width: 600,
				padding: 0,
				cancel: true,
				cancelVal: Ibos.l("CLOSE"),
				init: function(){
					Ibos.dialogUpload($.extend(true, {
						button_placeholder_id: "datt_upload_placeholder",
						custom_settings: {
							containerId: "datt_list"
						}
					}, param));
				},
				close: function(){
					// 由于 swfupload 生成的 object 对象一旦被隐藏(display:none; visiability: hidden) 会重新初始化
					// 而导致上传失败及上传队列消失，所以此时用位置偏移来替代
					this.position("-9999px");
					return false;
				}
			});
		}
		return dialog;
	};

	/**
	 * 打开高级搜索设置框
	 * @method openAdvancedSearchDialog
	 * @param  {[type]} config [description]
	 * @return {[type]}        [description]
	 */
	Ibos.openAdvancedSearchDialog = function(config){
		var defaults = {
			id: "d_advanced_search",
			title: Ibos.l("ADVANCED_SETTING"),
			cancel: true,
			lock: true,
			ok: true
		}
		return Ui.dialog($.extend({}, defaults, config));
	};

	/**
	 * 弹出用户登录窗口
	 * @method showLoginDialog
	 * @param  {Object} defaults 默认登录信息
	 * @return {[type]}          [description]
	 */
	Ibos.showLoginDialog = function(defaults){
		if(typeof $.formValidator === "undefined") {
			var formValidatorUrl = Ibos.app.getStaticUrl("/js/lib/formValidator");
			Ibos.statics.load({
				type: "css",
				url: formValidatorUrl + "/themes/Ibos/style/style.css"
			});
			Ibos.statics.load(formValidatorUrl + "/formValidator.packaged.js")
			.done(function(){
				fv_scriptSrc = formValidatorUrl + "/formValidator.packaged.js";
				Ibos.showLoginDialog(defaults);
			});
			return;
		}
		var inited = false;
		var dialog = Ui.dialog({
			id: "d_login",
			title: false,
			lock: true,
			ok: function(){
				if(!inited) {
					return false;
				}
				var _this = this,
					$ajaxUser = $("#ajax_username"),
					$ajaxPwd = $("#ajax_password");

				// 如果所有控件都已通过请求，则提交数据
				if($.formValidator.pageIsValid("100")) {
					Ibosapp.op.ajaxLogin({
						username: $ajaxUser.val(),
						password: $ajaxPwd.val()
					}, function(res){
						// 登陆成功
						if(res.isSuccess) {
							_this.close();
						// 登陆失败
						} else {
							// 用户名不存在或被锁定禁用
							if(typeof res.error !== "undefined") {
								$ajaxUser.select();
								$.formValidator.setTipState($ajaxUser[0], "onError", res.msg);
							// 密码错误
							} else {
								$ajaxPwd.select();
								$.formValidator.setTipState($ajaxPwd[0], "onError", res.msg)
							}
						}
					});
				}
				return false;
			},
			okVal: Ibos.l("LOGIN.LOGIN"),
			close: function(){
				// 关闭窗口时，重置表单提示
				$.formValidator.resetTipState("100");
			}
		});

		Ibos.statics.load({
			type: "html",
			url: Ibos.app.getAssetUrl("main", "/templates/ajax_login.html")
		}).done(function(res){
			dialog.content($.template(res, $.extend({ username: "", password: "" }, defaults)));
			// 初始化表单验证
			var pwdSettings = Ibos.app.g("password");
			$.formValidator.initConfig({
				formId: "ajax_login_form",
				validatorGroup: "100"
			})
			$("#ajax_username").formValidator({
				onFocus: Ibos.l("V.INPUT_USERNAME"),
				validatorGroup: "100"
			})
			.regexValidator({
				dataType: "enum",
				regExp: "notempty",
				onError: Ibos.l("V.INPUT_USERNAME")
			});

			$("#ajax_password").formValidator({
				onFocus: Ibos.l("V.INPUT_POSSWORD"),
				validatorGroup: "100"
			})
			.regexValidator({
				dataType: "enum",
				regExp: "notempty",
				onError: Ibos.l("V.INPUT_POSSWORD")
			});
			// 按回车键提交
			$("#ajax_username, #ajax_password").on("keydown", function(evt){
				if(evt.which === 13) {
					dialog.config.ok.call(dialog);
				}
			});

			inited = true;
		});

		return dialog;
	};

	/**
	 * 弹出私信窗口
	 * @method showPmDialog
	 * @todo  整理代码，优化接口，模板独立出来
	 * @param  {String} toUid         发送对象的id
	 * @param  {Object} ajaxOptions   ajax配置
	 * @param  {Object} selectOptions select2插件配置
	 * @return {}
	 */
	Ibos.showPmDialog = function(toUid, onsend, selectOptions){
		var dialog =  $.artDialog.open('/manage/user_message/add',{
			id: "pm_dialog",
			title: Ibos.l("SEND_PM"),
			padding: 0,
			top:'80px',
			width:'600px',
			lock: true,
			zIndex: 6500			
		});
		return dialog;
	}

	
	/**
	 * 加载打电话拨号盘显示框
	 * @method showMeetingDialog
	 * @return {[type]}
	 */
	Ibos.showMeetingDialog = function(){
		var url = Ibos.app.url('main/call/meeting'),
			dialog = Ui.dialog({
							title: false,
							padding: 0,
							id: "opt_call",
							lock: true,
							left: "50%",
							top: "52%"
						});
		$.get(url, function(res){
			if(res.isSuccess){
				var html = res.html;
				dialog.content(html);
			}
		});
	}
	


	$(function(){
		// 全局级别的事件
		Ibos.evt.add({
			// 使用外部文档阅读器
			"viewOfficeFile": function(param, elem, evt){
				var suffix = param.href.substr( param.href.lastIndexOf('.')+1 );
				if( suffix == 'txt'){
					Ui.openFrame(param.href, {
						title: false,
						id: "d_office_file",
						width: 800,
						height: 600
					});
				}else if( $.inArray(suffix, ["jpg", "jpeg", "png", "gif"]) > -1 ){
					// 读取初始化需要的文件
					var _loadFiles = function(callback){
						if(typeof FullGallery !== "undefined") {
							callback && callback();
						} else {
							U.loadCss(Ibos.app.getStaticUrl("/js/lib/gallery/jquery.gallery.css"));
							U.loadCss(Ibos.app.getStaticUrl("/js/app/fullGallery/fullGallery.css"));

							var galleryJsPath = Ibos.app.getStaticUrl("/js/lib/gallery/jquery.gallery.js"),
								mousewheelJsPath = Ibos.app.getStaticUrl("/js/lib/jquery.mousewheel.js"),
								fullGalleryJsPath = Ibos.app.getStaticUrl("/js/app/fullGallery/fullGallery.js");

							$.when($.getScript(galleryJsPath), $.getScript(mousewheelJsPath))
							.done(function(){
								$.getScript(fullGalleryJsPath, callback);
							});
						}
					};
					_loadFiles(function(){
						new FullGallery(
							[{
								url: param.href,
								thumburl: param.href
							}],
							{ start_at_index: 0 }
						).$nav.hide();
					});
				}else{
					window.open(param.href, "_blank");
				}
			},

			// 使用外部文档编辑器
			"editOfficeFile": function(param, elem){
				window.open(param.href, "_blank");
			},

			// 从文件柜中选择附件
			"selectFile": function(param){
				// 如果没安装文件柜模块，将不作任何处理
				if(!Ibos.app.getAssetUrl("file")) {
					return false;
				}

				$.when(
					Ibos.statics.load({ url: Ibos.app.getAssetUrl("main", "/templates/attach_item.html"), type: "html" }),
					Ibos.statics.load(Ibos.app.getAssetUrl("file", "/js/cabinet_file_selector.js"))
				).done(function(a1, a2){
					var addFilesFromFileSelector = function(files) {
						var aids = "",
							html = "";

						for(var i = 0; i < files.length; i++) {
							aids += aids ? "," + files[i].attachmentid : files[i].attachmentid;
							html += $.template(a1, files[i]);
						}

						$(param.target).append(html);
						U.addValue(param.input, aids);
					}
					Ibos.openFileSelector(addFilesFromFileSelector);
				});
			},

			// 前台查看证书
			"showCert": function(){
				Ui.ajaxDialog(Ibos.app.url('main/default/getCert'), {
					id: "d_cert",
					title: false,
					ok: false,
					width: 661,
					height: 471,
					padding: 0
					// cancel: false
				})
			},

			// 微博关注
			"follow": function(param, elem) {
				var $elem = $(elem);
				$elem.button("loading");
				param.formhash = Ibos.app.g('formHash');
				$.post(Ibos.app.url('message/api/dofollow'), param, function(res) {
					$elem.button("reset");
					if (res.isSuccess) {
						// 改变按钮状态，“相互关注”和“已关注”视图不一样
						$elem.html(res.both ? '<i class="om-geoc"></i> ' + Ibos.l("FOLLOWBOTH") : '<i class="om-gcheck"></i> ' + Ibos.l("FOLLOWED"))
						.removeClass("btn-warning")
						.attr({
							"data-action": "unfollow",
							"data-node-type": "unfollowBtn",
							"data-loading-text": Ibos.l("UNFOLLOWING")
						})
						// 使用attr设置loading-text后，data的缓存并没有即时更新，所以这里还需要重新设置data
						.data("loading-text", Ibos.l("UNFOLLOWING"))
						// 更新资料卡缓存, 延迟到按钮恢复原状态后执行
						setTimeout(function(){
							$("#ui_card").data("usercard_" + param.fid, $("#ui_card").html());
						}, 0)
					} else {
						Ui.tip(res.msg, 'danger');
						return false;
					}
				}, 'json');
			},

			// 取消微博关注
			"unfollow": function(param, elem) {
				var $elem = $(elem);
				$elem.button("loading");
				param.formhash = Ibos.app.g('formHash');
				$.post(Ibos.app.url('message/api/unfollow'), param, function(res) {
					$elem.button("reset");
					if (res.isSuccess) {
						// 改变按钮状态
						$elem.html('<i class="om-plus"></i> ' + Ibos.l("FOLLOW"))
						.removeClass("btn-danger").addClass("btn-warning")
						.attr("data-action", "follow")
						.attr("data-loading-text", Ibos.l("FOLLOWING"))
						.data("loading-text", Ibos.l("FOLLOWING"))
						.removeAttr("data-node-type");
						// 更新资料卡缓存, 延迟到按钮恢复原状态后执行
						setTimeout(function(){
							$("#ui_card").data("usercard_" + param.fid, $("#ui_card").html());
						}, 0)
					} else {
						Ui.tip(res.msg, 'danger');
						return false;
					}
				}, 'json');
			},

			"back": function(){
				window.history.go(-1);
			},
			//拨打电话
			"calling": function(param, elem){
				Ibos.showMeetingDialog();
			},

			//发送私信
			"sendPrivateLetter": function(param, elem){
				Ibos.showPmDialog();
			},

			// app 下载
			"appDownload": function(){
				var pageUrl = "http://www.ibos.com.cn/home/download/mobile";
				return $.artDialog.open(pageUrl, {
					id: "d_app_download",
					title: false,
					width: 650,
					height: 280,
					lock: true,
					padding: 0
				});
			},

			// 关注微信号
			"followWx": function() {
				return $.artDialog.open(Ibos.app.g("followWxUrl"), {
					id: "d_follow_wx",
					title: false,
					width: 880,
					skin: "art-autoheight art-ifame-top",
					height: 613,
					lock: true,
					padding: 0
				});
			}
		})

		/**
		 * 定时消息提醒
		 */
		if(document.getElementById("message_container")){
			setTimeout(function() {
				Ibosapp.dropnotify.init('reminder-list', 'message_container');
			}, Ibos.app.g("settings").notifyInterval);
		}

		var  $doc = $(document);
		// 切换显示隐藏状态
		$doc.on("click", "[data-toggle='display']", function(){
			var showSelector = $.attr(this, "data-toggle-show"),
				hideSelector = $.attr(this, "data-toggle-hide");
			showSelector && $(showSelector).show();
			hideSelector && $(hideSelector).hide();
		})

		$doc.on({
			"focus": function(){
				
				$(this).parent().addClass("has-focus");
			},
			"blur": function(){
				 $(this).parent().removeClass('has-focus');
			}
		}, "input:not([nofocus])")

		// 悬停取消关注
		$doc.on({
			"mouseenter": function(){
				var $elem = $(this);
				$elem.data("oldHtml", $elem.html()).addClass("btn-danger")
				.html('<i class="om-chi"></i> ' + Ibos.l("UNFOLLOW"));
			},
			"mouseleave": function(){
				var $elem = $(this);
				$elem.removeClass("btn-danger").html($elem.data("oldHtml"));
			}
		}, '[data-node-type="unfollowBtn"]');

		// 支持 IE 9 及以下的 placeholder功能
		if(!("placeholder" in document.createElement("input"))) {
			$.getScript(Ibos.app.getStaticUrl("/js/lib/jquery.placeholder.js"), function(){
				$("[placeholder]").placeholder();
			});
		}

		// 登录超时时，弹出登录框
		(function(){
			var page = Ibos.app.g("page"),
				timeout = +Ibos.app.g("loginTimeout"),
				timer;
			// 当前页不为登陆页, 定时检查登录状态, 5 分钟一次
			if(page !== "login" && timeout) {
				timer = setInterval(function(){
					var lastActivity = +U.getCookie("lastactivity"),
						nowTime = +new Date/1000;
					// 当前时间与上次活动时间的时间差 大于 超时限定时间时
					// 发送 ajax 到后端检测此时的登陆状态，若此时已离线，则弹出登陆窗口
					// 否则继续定时检测
					if(lastActivity && (nowTime - lastActivity >= timeout )){
						Ibosapp.op.checkIsLogin(function(res){
							if(!res.islogin) {
								Ibos.showLoginDialog({
									username: res.username
								});
							}
						})
					}
				}, 300000);
			}

		})();

		$(document).on("change", "input[type='checkbox']", function(evt, data){
			if(data && data.outloop) return false;
			var leaderName = $.attr(this, "data-name"),
				isChecked = this.checked,
				name;
			if(leaderName) {
				$('[name="' + leaderName + '"], [data-check="'+ leaderName +'"]').prop("checked", isChecked).trigger("change", { outloop: true });
			} else {
				name = $.attr(this, "data-check") || $.attr(this, "name");
				if(name) {
					if(!isChecked) {
						$('[data-name="' + name + '"]').prop("checked", false).trigger("change", { outloop: true });
					}
				}
			}
		});

		// IE 8 以下跳转至浏览器升级页
		// @hack: 由于 IE 11 去掉了相关的userAgent, jq1.8.3 判断 IE 12时会判为 mozilla
		if(!$.browser.mozilla && $.browser.msie && parseInt($.browser.version, 10) < 8) {
			window.location.href = Ibos.app.url("main/default/unsupportedBrowser");
		}
	});


	(function(){
		/**
		 * 编辑器内容本地缓存处理
		 * 百度编辑器 1.4.1 以后的版本增加草稿箱功能，等到版本升级后可以去掉这里的代码
		 * @class EditorCache
		 * @constructor
		 * @for Ibos
		 * @param {Ueditor} ue   百度编辑器实例
		 * @param {String|Element|Jquery} form 编辑器所在表单节点
		 * @param {[type]} id   自定义标识符
		 */
		var EditorCache = function(ue, form, id){
			if(!ue || !ue instanceof UE.Editor) {
				$.error("EditorCache: 参数 ue 必须为 UE.Editor 的实例");
			}
			this.ue = ue;
			this.$form = form ? $(form) : $(ue.iframe).closest("form");
			this.id = id || ue.key;
			this._timer = void 0;

			this.start();
			this.$form.submit($.proxy(this.clear, this));
		}
		$.extend(EditorCache.prototype, {
			start: function(){
				this._timer = setInterval($.proxy(this.set, this), 3000);
			},

			stop: function(){
				clearInterval(this._timer);
			},

			set: function(){
				return Ibos.local.set("uecache_" + this.id, this.ue.getContent(false, false, false, true));
			},

			get: function(){
				return Ibos.local.get("uecache_" + this.id) || "";
			},

			clear: function(){
				this.stop();
				return Ibos.local.remove("uecache_" + this.id);
			},

			restore: function(){
				var content = this.get();
				if(content) {
					this.ue.setContent(content, false, true);
				}
			}
		});

		Ibos.EditorCache = EditorCache;
	})();
})
function dialogclose()
{
	$.each($.artDialog.list, function (index, item) {  
	    item.close();  
	});  
}

