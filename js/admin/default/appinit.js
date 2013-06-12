function pleaseWait(msg) {
	if (typeof(msg)!=='string') {
		msg='Kérem várjon...';
	}
	$.blockUI({
		message:msg,
		css:{
			border:'none',
			padding:'15px',
			backgroundColor:'#000',
			'-webkit-border-radius':'10px',
			'-moz-border-radius':'10px',
			opacity:.5,
			color:'#fff'
		}
	});
}

function messagecenterclick(e){
	e.preventDefault();
	$(this)
		.slideToggle('slow',function(){
			$(this).removeClass('matt-messagecenter ui-widget ui-state-highlight');
			$('#termekkarb').hide();
		});
};

function messagecenterclickonerror(e){
	e.preventDefault();
	$(this)
		.slideToggle('slow',function(){
			$(this).removeClass('matt-messagecenter ui-widget ui-state-error');
		});
};

$(document).ready(
	function(){

		var msgcenter=$('#messagecenter').hide();

		$('.menupont').button();
		var menu=$('#menu');
		$('#kozep').css('left',menu.outerWidth()+menu.offset().left*2+'px');
		var menubar=$('.menu-titlebar');
		menubar.addClass('mattedit-titlebar ui-widget-header ui-helper-clearfix ui-corner-all');
		menubar.each(function() {
			$this=$(this);
			var ref=$($this.attr('data-refcontrol'));
			if (ref.attr('data-visible')=='hidden') {
				$this.append('<a href="#" class="mattedit-titlebar-close">'+
					'<span class="ui-icon ui-icon-circle-triangle-s"></span></a>'+
					'<span class="ui-jqgrid-title">'+$this.attr('data-caption')+'</span>');
				ref.hide();
			}
			else {
				$this.append('<a href="#" class="mattedit-titlebar-close">'+
					'<span class="ui-icon ui-icon-circle-triangle-n"></span></a>'+
					'<span class="ui-jqgrid-title">'+$this.attr('data-caption')+'</span>');
			}
		});
		menubar.on('click',function(e) {
			e.preventDefault();
			var ref=$($(this).attr('data-refcontrol'));
			if (ref.attr('data-visible')=='hidden') {
				ref.attr('data-visible','visible');
				ref.slideDown(50);
				$('> a > span',this).removeClass('ui-icon-circle-triangle-s').addClass('ui-icon-circle-triangle-n');
			}
			else {
				ref.attr('data-visible','hidden');
				ref.slideUp(50);
				$('> a > span',this).removeClass('ui-icon-circle-triangle-n').addClass('ui-icon-circle-triangle-s');
			}
		});
		$(document)
				.ajaxStart(pleaseWait)
				.ajaxStop($.unblockUI)
				.ajaxError(function(e, xhr, settings, exception) {
					alert('error in: ' + settings.url + ' \n'+'error:\n' + exception);
				});
		$('#ThemeSelect').change(function(e) {
			$.ajax({url:'/admin/setuitheme',
				data:{uitheme:this.options[this.selectedIndex].value},
				success:function(data) {
					window.location.reload();
				}
			});
		});
		$('#GridEditButtonSelect').change(function(e) {
			$.ajax({url:'/admin/setgrideditbutton',
				data:{grideditbutton:this.options[this.selectedIndex].value},
				success:function(data) {
					window.location.reload();
				}
			});
		});
		$('#EditStyleSelect').change(function(e) {
			$.ajax({url:'/admin/seteditstyle',
				data:{editstyle:this.options[this.selectedIndex].value},
				success:function(data) {
					window.location.reload();
				}
			});
		});
	}
)