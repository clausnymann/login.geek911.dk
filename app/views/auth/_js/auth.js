// JavaScript Document
$.fn.center = function () {
			this.css({"top": Math.max(0, (($(window).height() - this.outerHeight()) / 2)) + "px",
					   "left": Math.max(0, (($(window).width() - this.outerWidth()) / 2)) + "px"});
			
	}
	
$( document ).ready(function() {
	
	var auth = (function() {

		var $loginBtn = $('.loginBtn');
		var xhr;
		
		var toggleModalBg = function(){
			var $modalBg = $('#modal-bg');
			if(!$modalBg[0]){
				$modalBg = $('<div id="modal-bg" class="show"></div>').appendTo('body');
				$modalBg.on('click', function(){
					$('.modal').removeClass('show');
					$('#modal-bg').removeClass('show');
				});
			}else {
				$modalBg.toggleClass('show');
			}
		}
		
		window.onresize = function() {
			$('.modal').center();
		}
		
		var toggleModal = function(id, html){
			    var $modal = $('#'+id+'-modal');
				if(!$modal[0]){
					$('<div id="'+id+'-modal" class="modal show"><div id="'+id+'-modal-close" class="modal-close">X</div>'+html+'</div>')
					.appendTo('body')
					.center();
					 var $modal = $('#'+id+'-modal');
					toggleModalBg();
					$('#'+id+'-modal-close').on('click', function(){
						$('.modal').removeClass('show');
						$('#modal-bg').removeClass('show');
					});
				}else{
					$modal.toggleClass('show').center();
					toggleModalBg();
				}
				$modal.find('input').first().focus();
		}
		
		var initForgetPassword = function(){
			//handling form submit
			var $forgetPasswordForm = $("#forgetPasswordForm");
		
			$forgetPasswordForm.submit(function () {
				if(typeof xhr !== "undefined") xhr.abort();
				xhr = $.ajax({
					type: "POST",
					url: '/auth/send_password/true',
					data: {
						ajax : true, //loading index.php with REQUEST ajax true to avoid displaying default HTML content.
						email : $forgetPasswordForm.find('#email').val()
					},
					dataType: 'json',
					cache: false,
					success: function (json) {
						
						if(json.msg)//error message
							$forgetPasswordForm.find('.form_info').html(json.msg);
						
						if(json.focus)//error message
							$forgetPasswordForm.find('#'+json.focus).focus();
							
						
					},
					error: function(jqXHR, textStatus, errorThrown) {
						
					}
				});
				return false;
			});
			$forgetPasswordForm.find("#loginBtn").on('click', function() {
			$('.modal').removeClass('show');
					$('#modal-bg').removeClass('show');
			loadHtml('login', '/auth/login', 'initLogin()');
			return false;
		});
		}
		var initLogin = function(){
			//handling form submit
			var $loginForm = $("#loginForm");
		
			$loginForm.submit(function (e) {
				if(typeof xhr !== "undefined") xhr.abort();
				xhr = $.ajax({
					type: "POST",
					url: '/auth/try_login/true',
					data: {
						ajax : true, //loading index.php with REQUEST ajax true to avoid displaying default HTML content.
						email : $loginForm.find('#email').val(),
						password : $loginForm.find('#password').val(),
					},
					dataType: 'json',
					cache: false,
					success: function (json) {
						if(json.msg == 'success') //login success
							window.location.href = "http://" + window.location.hostname;
						
						else if(json.msg)//error message
							$loginForm.find('.form_info').html(json.msg);
					},
					error: function(jqXHR, textStatus, errorThrown) {
						
					}
				});
				return false;
			});
			
			$forgetPassword = $('#forget-password');
			if($forgetPassword[0]){
				$forgetPassword.on('click', function() {
					$('.modal').removeClass('show');
					$('#modal-bg').removeClass('show');
					loadHtml('forget-password', '/auth/forget-password', 'initForgetPassword()');
					return false;
				});
			}
		}
		
		
		var loadHtml = function(id, url, returnFunction){
			if($('#'+id+'-modal')[0]){
				toggleModal(id, false);
				return false;
			}
			if(typeof xhr !== "undefined") xhr.abort();
			xhr = $.ajax({
				url: url,
				data: {
					ajax : true //loading index.php with GET value to avoid displaying default content (header/footer)
				},
				dataType: 'html',
				cache: true,
				success: function (html) {
					$('.modal').removeClass('show');
					toggleModal(id, html);
					delete xhr;
					if(typeof returnFunction !== "undefined") 
					eval(returnFunction);
				}
			});
		}
		
		
		$loginBtn.on('click', function() {
			loadHtml('login', '/auth/login', 'initLogin()');
			return false;
		});
		
		
		
	})();
	
});
