$(function(){
	//点击右侧选择框
	$(document).on('click','.checkBox',function(){
		var $that=$(this);
		setTimeout(function(){
			var checkBoxLen=$that.parents('.listContent').find('.checkBox').length;
			var checkBoxCkLen=$that.parents('.listContent').find('.checkBox.checkClass').length;
			if(checkBoxLen==checkBoxCkLen){
				$that.parents('td').siblings('td').find('.faCheckBox').addClass('checkClass');
			}else{
				$that.parents('td').siblings('td').find('.faCheckBox').removeClass('checkClass');
			}
			var faCheckBoxLen=$('.faCheckBox').length;
			var faCheckBoxCkLen=$('.faCheckBox.checkClass').length;
			if(faCheckBoxLen==faCheckBoxCkLen){
				$('.allCheckBox').addClass('checkClass');
			}else{
				$('.allCheckBox').removeClass('checkClass');
			}	
		},100)	
		if($(this).hasClass('checkClass')){
			$(this).removeClass('checkClass');
		}else{
			$(this).addClass('checkClass');
		}
	})
	//点击全选选择框
	$(document).on('click','.allCheckBox',function(){
		if($(this).hasClass('checkClass')){
			$(this).removeClass('checkClass');
			$('.checkBox').removeClass('checkClass');
			$('.faCheckBox').removeClass('checkClass');		
		}else{
			$(this).addClass('checkClass')
			$('.checkBox').addClass('checkClass');
			$('.faCheckBox').addClass('checkClass');		
		}
	})
	//点击全选按钮
	$(document).on('click','.allCkButton',function(){
		if($('.allCheckBox').hasClass('checkClass')){
			$('.allCheckBox').removeClass('checkClass');
			$('.checkBox').removeClass('checkClass');
			$('.faCheckBox').removeClass('checkClass');	
		}else{
			$('.allCheckBox').addClass('checkClass');
			$('.checkBox').addClass('checkClass');
			$('.faCheckBox').addClass('checkClass')	;	
		}
	})
	//点击左侧选择框
	$(document).on('click','.faCheckBox',function(){
		setTimeout(function(){
			var faCheckBoxLen=$('.faCheckBox').length;
			var faCheckBoxCkLen=$('.faCheckBox.checkClass').length;
			if(faCheckBoxLen==faCheckBoxCkLen){
				$('.allCheckBox').addClass('checkClass');
			}else{
				$('.allCheckBox').removeClass('checkClass');
			}
		},100)
		if($(this).hasClass('checkClass')){
			$(this).removeClass('checkClass');
			$(this).parents('td').siblings().find('.checkBox').removeClass('checkClass');
		}else{
			$(this).addClass('checkClass');
			$(this).parents('td').siblings().find('.checkBox').addClass('checkClass');
		}	
	})
	
	$('.allCkButton,.allCkBack').mouseover(function(){
		$(this).animate({opacity:0.6},1000)
	})
	$('.allCkButton,.allCkBack').mouseleave(function(){
	$(this).animate({opacity:1},500)
	})
})