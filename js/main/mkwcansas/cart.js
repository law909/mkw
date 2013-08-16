var cart=function($) {

	function initUI() {
		if ($('.js-cart').length>0) {
			$('.kosardelbtn').on('click',function(e) {
				var $this=$(this);
				e.preventDefault();
				$.ajax({
					type:'POST',
					url:$this.attr('href'),
					data:{
						jax:1
					},
					beforeSend:function(x) {
						mkw.showMessage('A terméket töröljük a kosarából...');
					}
				})
				.done(function(data) {
					window.location='/kosar/get';
				})
				.always(function() {
					mkw.closeMessage();
				});
			});
			$('.kosareditbtn').on('click',function(e) {
				var $this=$(this),
					id=$this.attr('data-id');
				e.preventDefault();
				$.ajax({
					type:'POST',
					url:'/kosar/edit',
					data:{
						jax:1,
						id:id,
						mennyiseg:$('#mennyedit_'+id).val()
					},
					beforeSend:function(x) {
						mkw.showMessage('A terméket módosítjuk a kosarában...');
					}
				})
				.done(function(data) {
					window.location='/kosar/get';
				})
				.always(function() {
					mkw.closeMessage();
				});
			});
		}
	}

	return {
		initUI: initUI
	};

}(jQuery);