<div id="form-product-container" class="row" style="display: none;">
	<form id="form-product" onsubmit="return false;">
		<fieldset>
			<legend class="grey-text">New product</legend>
			<div class="row">
				<div class="input-field col s12 m6">
					<label for="product-name">Name</label>
					<input type="text" name="product[name]" value="" id="product-name" class="validate">
				</div>
				<div class="input-field col s12 m3">
					<label for="product-price">Price</label>
					<input type="text" name="product[price]" value="" id="product-price" class="validate">
				</div>
				<div class="input-field col s12 m3">
					<button type="submit" class="btn teal lighten-2">
						Add
					</button>
				</div>
			</div>
		</fieldset>
		<input type="hidden" name="product[place_id]" value="<?php echo $place_id ?>">
	</form>
	<div id="product-message" class="blue-text text-darken-1"></div>
	<div class="row">
		<table>
			<tr id="product-table-header">
				<th>&nbsp;</th>
				<th>Item</th>
				<th>Price</th>
			</tr>
			<?php foreach ($products as $product): ?>
				<tr id="<?php echo "product-row-{$product['id']}" ?>">
					<td>
						<a href="#" class="red-text text-darken-2" 
							onclick="deleteProduct(<?php echo $product['id'] ?>)">
							<i class="material-icons">remove_circle</i>
						</a>
					</td>
					<td><?php echo $product['name'] ?>&nbsp;</td>
					<td class="right-align">$ <?php echo sprintf("%.2f", $product['price']) ?>&nbsp;</td>
				</tr>
			<?php endforeach ?>
		</table>
	</div>
</div>
<script type="text/javascript">
var	productMessage = jQuery('#product-message');
function deleteProduct(productId) {
	var params = [];
	params.push('place');
	params.push(placeId);
	params.push('product');
	params.push(productId);
	jQuery.getJSON('/places/ajax_detele_product/'+params.join('/'), function (response) {
		if (typeof response.success != 'undefined' && response.success) {
			// 
			jQuery('#product-row-'+productId).remove();
			reloadAfterModal = true;
		} else {
			productMessage.removeAttr('class').addClass('red-text text-darken-1');
			if (typeof response.message != 'undefined') {
				//
				productMessage.html(response.message);
			} else {
				// 
				productMessage.html("Unknown error. Please check your connection.");
			}
		}
	});
}
// evaluate the JSON response
function checkProductSubmit (response)
{
	if(typeof response.success != 'undefined' && response.success) {
		// do something with the response
		reloadAfterModal = true;
		var htmlCode = '<tr id="product-row-'+response.data.id+'">\n'+
			'<td>\n'+
			'<a href="#" class="red-text text-darken-2" onclick="deleteProduct('+response.data.id+')">\n'+
			'<i class="material-icons">remove_circle</i>\n'+
			'</a>\n'+
			'</td>\n'+
			'<td>\n'+
			response.data.name+'&nbsp;\n'+
			'</td>\n'+
			'<td class="right-align">\n'+
			'$ '+response.data.price+'&nbsp;\n'+
			'</td>\n'+
			'</tr>';
		jQuery('#product-table-header').after(htmlCode);
		document.getElementById('form-product').reset();
	} else {
		// productMessage.html('');
		productMessage.removeAttr('class').addClass('red-text text-darken-1');
		if(typeof response.message != 'undefined') {
			productMessage.html(response.message);
		} else {
			productMessage.html("Error logging in. Please check your connection.");
		}
	}
}
// process form
function validateProductForm() {
	return true;
}
// ajax submit
jQuery("#form-product").ajaxFormSubmit({
	action: siteUrl("/places/ajax_add_product"),
	callback: checkProductSubmit,
	validation: validateProductForm
});
// when loaded
jQuery(document).ready(function () {
	jQuery('#modal-product-loader').hide();
	jQuery('#form-product-container').slideDown();
});
</script>
