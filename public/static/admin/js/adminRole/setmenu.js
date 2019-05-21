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
			if(checkBoxCkLen>=1){
				console.log(1212)
                $that.parents('td').siblings('td').find('.faCheckBox').addClass('checkClass');
			}else{
                $that.parents('td').siblings('td').find('.faCheckBox').removeClass('checkClass');
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
	//点击提交按钮
	$(document).on('click','.submitButton',function(){
		//获取角色id
		var id = $('.role_id').val();
		// 获取左侧菜单
		var faCheckBoxAll=$('.faCheckBox');
		// 获取右侧菜单
        var checkBoxAll=$('.checkBox');
        // 获取全选按钮
        var allCheckBoxAll=$('.allCheckBox');
        // 这是获取左侧选中的id
        var faStr='';
        $.each(faCheckBoxAll,function (k,v) {
            if($(this).hasClass('checkClass')){
                var faAllId=$(this).attr('data-id');
                faStr +=faAllId + ",";
            };

        });
        // 这是获取右侧选中的id
        var ckStr='';
        $.each(checkBoxAll,function (i,j) {
            if($(this).hasClass('checkClass')){
                var ckAllId=$(this).attr('data-id');
                ckStr +=ckAllId + ",";
            };
        });
		var menu_ids = ckStr+faStr;
		// console.log(menu_ids);
		// return false;
        $.ajax({
            url 	: "/admin/admin_role/setMenu",
            type 	: "post",
            dataType: "json",
            data	: {'id':id,'menu_ids':menu_ids},
            success : function(res) {
                if (res.code == 1) {
                    layer.msg(res.msg, {icon: 1,time: 1000});
                    setTimeout(function () {
                        editBankUrl(res.url);
                    },1000);
                } else {
                    layer.msg(res.msg, {icon: 5,time: 1000});
                }
            }
        });
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
		$(this).animate({opacity:0.6},1000);
	})
	$('.allCkButton,.allCkBack').mouseleave(function(){
	$(this).animate({opacity:1},500);
	});
});