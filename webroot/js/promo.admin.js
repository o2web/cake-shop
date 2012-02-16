$(function(){
	$('#ShopPromotionActionId').change(function(){
		$('#ActionSubForm .loader').show();
		$('#ActionSubForm').load(root+'admin/shop/shop_actions/form/'+$(this).val());
	});
});