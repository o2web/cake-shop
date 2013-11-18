(function( $ ) {
	$(function(){
		$("#ShopPromotionAddCoupons, #ShopPromotionCodeNeeded, #ShopPromotionCouponCodeNeeded").change(checkFields);
		$("#ShopPromotionAddCoupons").keypress(checkFields);
		$(".BtAddCoupons").click(function(){
			$(".AddCouponsBox").show();
			$(this).hide();
			return false;
	});
		$("div.methodDef select.methodSelect").change(checkMethodParams).each(checkMethodParams);
		checkFields();
	})
	function checkFields(){
		if(!$("#ShopPromotionCodeNeeded:checked").length){
			$("#ShopPromotionCouponCodeNeeded").attr("disabled",false);
			$("#ShopPromotionCouponCodeNeeded").closest("div").removeClass("disabled");
		}else{
			$("#ShopPromotionCouponCodeNeeded").closest("div").addClass("disabled");
			$("#ShopPromotionCouponCodeNeeded").attr("disabled",true);
			$("#ShopPromotionCouponCodeNeeded").val(0);
		}
		if(!$("#ShopPromotionCouponCodeNeeded:checked").length){
			$("#ShopPromotionCodeNeeded").attr("disabled",false);
			$("#ShopPromotionCodeNeeded").closest("div").removeClass("disabled");
		}else{
			$("#ShopPromotionCodeNeeded").closest("div").addClass("disabled");
			$("#ShopPromotionCodeNeeded").attr("disabled",true);
			$("#ShopPromotionCodeNeeded").val(0);
		}
	}
	function checkMethodParams(){
		var $container = $(this).closest(".methodDef");
		$(".preloadedMethodParams",$container).hide();
		var preloadedId = $(this).val().replace('.','')+"MethodParams";
		var $target = $(".methodParams",$container);
		$target.empty();
		if($(this).val() == ""){
		}else if($("."+preloadedId,$container).length){
			$("."+preloadedId,$container).show();
		}else{
			$target.empty();
			$target.addClass('loading');
			if(window.console){
				console.log($target);
				console.log($target.attr('field'));
				console.log($target.attr('class'));
			}
			var url = root+'admin/shop/shop_promotions/method_form/'+$(this).val()+"/"+$target.attr('field');
			$target.load(url, function(result, status){
				$target.removeClass('loading');
			});
		}
	}
})( jQuery );