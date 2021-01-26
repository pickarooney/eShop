					
$(document).ready(function() { 

	$('#catsearch').niceSelect();
	$('#sortorder').niceSelect();
	
	//refresh button 	
	$("#refresh").on("click", () => {
		var searchTerm = '';
		$('#catsearch').val('All Items').prop('selected', true);
		$('#catsearch').niceSelect('update');
		$.ajax({
			url:"searchProduct.php",    
			type: "post",   
			data: {searchTerm: searchTerm},
			dataType: 'text',
			success:function(result){
				$("#product-grid").html(result);
			}
		});
	});
	
	
	//search field (with or without category filter)		
	$("#searchproduct").on('keyup', function (e) {
		if (e.key === 'Enter' || e.keyCode === 13) {
			var searchTerm = $('#searchproduct').val();
			var searchCat = $('#catsearch').val();
			$.ajax({
				url:"searchProduct.php",    
				type: "post",   
				data: {searchTerm: searchTerm, searchCat: searchCat},
				dataType: 'text',
				success:function(result){
					$("#product-grid").html(result);
				}
			});
			
			document.getElementById('searchproduct').value = '';
		}
	});
	

	//order items (fix so it works on current filter)
	$("#sortorder").on("change", () => {
		var sortOrder = $('#sortorder').val();
		$('#sortorder').niceSelect('update');
		$.ajax({
			url:"searchProduct.php",    
			type: "post",    
			data: {sortOrder: sortOrder},
			dataType: 'text',
			success:function(result){
				$("#product-grid").html(result);
			}
		});
	});
	
	
	//filter by brand
	$(".brand").click(function(event) {
		var filterBrand = event.target.id.replace("brand", "");
		$.ajax({
			url:"searchProduct.php",   
			type: "post",   
			data: {filterBrand: filterBrand},
			dataType: 'text',
			success:function(result){
				$("#product-grid").html(result);
			}
		});
	});
	
	//filter by category
	$(".cat").click(function(event) {
		var filterCat = event.target.id.replace("cat", "");
		$.ajax({
			url:"searchProduct.php",    
			type: "post",   
			data: {filterCat: filterCat},
			dataType: 'text',
			success:function(result){
				$("#product-grid").html(result);
			}
		});
	});
	

	//shadows on brands/categories
	$(".brand, .cat").mouseover(function()
	  {
			  if ($('li ul li:hover').length) 
			  {
				  $('li ul li:hover').css('background','gray'); 
			  }
			  else
			  {
				   $('li:hover').css('background','gray'); 
			  }
	});
	$(".brand, .cat").mouseout(function()
	  {
			  $(this).css('background', 'transparent');
	});
				

});


//show product detail modal, display add/remove button as appropriate
$(document).on('click', '.ti-eye', function(event){
	var searchCode = event.target.id.replace("mod", "");
	$.ajax({
		url:"searchProduct.php",    
		type: "post",   
		data: {searchCode: searchCode},
		dataType: 'text',
		success:function(result){
			$("#productmodal").html(result);
				//if(jQuery.inArray(searchCode, cartList) !== -1) {
				if (findInArray(searchCode, cartList) !== -1) {	
					$(".cart").hide(); $(".uncart").show();
				}
				else {
					$(".cart").show(); $(".uncart").hide();
				}
			$("#productmodal").modal('toggle');
		}
	});
});

//add item to cart	
$(document).on('click', '.cart, .fa-redo', function(event){
	var cartID = event.target.id;
	if(cartID.indexOf('scart') != -1){
		var scartID = cartID;
		cartID = scartID.replace("scart","cart");
	}
	else{	
		var scartID = cartID.replace("cart","scart");
	}	
	
	var scartID = cartID.replace("cart","scart");
	var suncartID = cartID.replace("cart","suncart");
	var uncartID = cartID.replace("cart", "uncart");
	var reorderID = cartID.replace("cart", "uncart");//
	var cartCode = cartID.replace("cart", "");
	var upriID = cartID.replace("cart","upri");
	var upri = $('#' + upriID).text().replace("€","");

	
	//if(jQuery.inArray(cartCode, cartList) !== -1) {
	if (findInArray(cartCode, cartList) !== -1) {
		$.notify("Item already in cart", "success");
	}
	else {
		//cartList.push(cartCode);
		cartList.push([cartCode,1,upri]); // what is the value of upri here now? need to bring the current price in and show in another colour when loading from history
		$(".total-count").html(cartList.length);
		$.notify(cartCode+" added to cart", "success");
		getCartTotal();
		$('#' + cartID).hide();$('#' + scartID).hide();$('#' + uncartID).show();$('#' + suncartID).show(); //check if this works for rebuy
	}	
});

//remove item from cart	
$(document).on('click', '.uncart', function(event){
	var uncartID = event.target.id;
	
	if(uncartID.indexOf('suncart') != -1){
		var cartID = uncartID.replace("suncart","cart");
		var suncartID = cartID.replace("cart","suncart");
		var scartID = cartID.replace("cart","scart");
		var cartCode = cartID.replace("cart","");
	}
	else{	
		var cartID = uncartID.replace("uncart","cart");
		var scartID = cartID.replace("cart","scart");
		var suncartID = cartID.replace("cart","suncart");
		var cartCode = cartID.replace("cart", "");
	}

	var imgID = uncartID.replace("uncart","img");
	var detID = uncartID.replace("uncart","det");
	var labID = uncartID.replace("uncart","lab");
	var quanID = uncartID.replace("uncart","quan");
	var priceID = uncartID.replace("uncart","price");
	
	//cartList.splice($.inArray(cartCode,cartList),1);
	removeFromArray(cartCode,cartList);
	$(".total-count").html(cartList.length);
	$.notify(cartCode+" removed from cart", "success");
	$('#' + cartID).show(); $('#' + scartID).show('');
	$('#' + uncartID).hide();$('#' + suncartID).hide();$('#' + imgID).remove();$('#' + detID).remove();$('#' + labID).remove();$('#' + quanID).remove();$('#' + priceID).remove();
	
	getCartTotal();
	var roundTotal = cartTotal.toFixed(2);
	$("#grandtotal").html(roundTotal+'<br>');
	if (cartList.length == 0) {
		$("#checkout").remove();$("#grandtotal").remove();$("#totallabel").remove();
		$("#cartdesc").text("Your cart is empty.");
		$("#cartheader").hide();
	}

});



//display cart modal 
$(document).on('click', '.fa-shopping-cart', function(event){
	if (cartList.length == 0) {
		$.notify("No products selected", "success");
	}
	else
	{	
		$.ajax({
			url:"searchProduct.php",    
			type: "post",    
			data: {cartList: cartList},
			dataType: 'text',
			success:function(result){
				if (result.trim() == 'OutOfStock') {
						var message = "<p>Sorry, some of these products are no longer available. please</p> return to the main page and refresh the list.</p>";
						$("#cartdetails").html(message);
						$("#cartmodal").modal('toggle');
						cartList.length = 0;
						$(".total-count").html(cartList.length);					
				}
				else{
					$("#cartheader").show();
					$("#cartdetails").html(result);
					$("#cartmodal").modal('toggle');
				}
			}
		});
	}
});

$(document).on('click', '.ti-user, .fa-user', function(event){

	var logOut = $("#loggedIn").text();
	$.ajax({
		url:"searchProduct.php",    
		type: "post",    
		data: {logOut: logOut},
		dataType: 'text',
		success:function(result){
			$("#loggedIn").text(result);
		}
	});
	window.location.href = 'eShop.php';

});




//display recap modal 
function displayRecap(){
	if (cartList.length == 0) {
		$.notify("No purchase history to display.", "success");
	}
	else
	{	
		$.ajax({
			url:"searchProduct.php",    
			type: "post",    
			data: {recapList: cartList},
			dataType: 'text',
			success:function(result){
				$("#recapheader").show();
				$("#recapdetails").html(result);
				$("#recapmodal").modal('toggle');
				//send mail
			}
		});
	}
}; 


//display history 
$(document).on('click', '#ordertab', function(event){

	ccode = $("#loggedIn").text().trim();

	if (ccode == '') {
		$.notify("No client selected", "success");
	}
	else
	{	
		$.ajax({
			url:"searchProduct.php",    
			type: "post",    
			data: {ccode: ccode},
			dataType: 'text',
			success:function(result){
				$("#historydetails").html(result);
			}
		});
	}
});




//update total when quantity changed in cart
$(document).on('change', '.quan', function(event){
  var items = event.target.value;
  var quanID = event.target.id;
  var bcode = quanID.replace("quan","");
  var upricode = quanID.replace("quan","unipri");
  var pricecode = quanID.replace("quan","price");
  
  var uprice = $("#"+upricode).val();
  var total = uprice * items;
  var tprice = total.toFixed(2); 
  
  updateCart(bcode,items);
  
  $("#"+pricecode).html('<br>'+tprice);
  getCartTotal();
  var roundTotal = cartTotal.toFixed(2);
  $("#grandtotal").html(roundTotal+'<br>');
});



//calculate cart contents and total
function getCartTotal(){
	cartTotal = 0;
	for (i=0;i<cartList.length;i++) {
		cartTotal += parseFloat(cartList[i][2]*cartList[i][1]);
	}
}

//switch delivery options
$(document).on('click', '.delivery', function(event){
	var deltype = event.target.id;
	if (deltype == 'delhome'){
		$("#shipping").text('5.00');
		var gt = parseFloat($("#shipping").text()) + parseFloat($("#stotal").text());
		var rt = gt.toFixed(2);
		$("#gtotal").text(rt);
		$("#add1").show();$("#add2").show();$("#zip").show();$("#city").show();$("#province").show();$("#country").show();
	}
	else {
		$("#shipping").text('0.00');
		var gt = parseFloat($("#shipping").text()) + parseFloat($("#stotal").text());
		var rt = gt.toFixed(2);
		$("#gtotal").text(rt);
		$("#add1").hide();$("#add2").hide();$("#zip").hide();$("#city").hide();$("#province").hide();$("#country").hide();
	}

});


//switch payment options
$(document).on('click', '.payment', function(event){
	var paytype = event.target.id;
	if (paytype == 'paycard'){
		$("#carddetails").show();
		$("#cardicon").show();
		$("#paypalicon").hide();
		$("#paypaldetails").hide();
	}
	else if (paytype == 'paypal'){
		$("#carddetails").hide();
		$("#cardicon").hide();
		$("#paypalicon").show();
		$("#paypaldetails").show();
		$("#ppaccount").val($("#eMail").val());
	}
	else {
		$("#carddetails").hide();
		$("#cardicon").hide();
		$("#paypalicon").hide();
	}

});



$('#cartmodal').on('hidden.bs.modal', function () {

		getCartTotal();
		rt = cartTotal.toFixed(2);
		$("#stotal").text(rt)
		gt = parseFloat($("#shipping").text()) + parseFloat($("#stotal").text());
		gt = gt.toFixed(2);
		$("#gtotal").text(gt);
	
});


//process payment
$(document).on('click', '#processor', function(event){
	
	var visano = /^(?:4[0-9]{12}(?:[0-9]{3})?)$/;
	var mcno = /^(?:5[1-5][0-9]{14})$/;
	var exp = /^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$/;
	var phoneno = /^\+(?:[0-9] ?){6,25}[0-9]$/;
	var emailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
	var cn = $("#cardnum").val().replaceAll(" ","");
	var mobile = $("#mobile").val().replaceAll(" ","");
	var email = $("#eMail").val();
	var ed = $("#expiry").val();
	let nowDate = new Date();
	var thisYear = nowDate.getFullYear().toString().slice(-2);
	var thisMonth = nowDate.getMonth();thisMonth+=1;
	var cardYear = ed.slice(-2);
	var cardMonth = ed.substring(0, 2);
	var paymentType = $('input[name="payment"]:checked').val();	
	var deliveryType = $('input[name="delivery"]:checked').val();	
	var shipping = $("#shipping").text().replace(".","");  //convert to cents	
	var ct = $( "#countries option:selected" ).text();
	var ctcode = $( "#countries option:selected" ).val();
	var fname = $("#firstName").val();
	var lname = ($("#lastName").val());
	var add1 = ($("#address1").val());
	var add2 = ($("#address2").val());
	var town = ($("#town").val());
	var ccode = ($("#custnum").val()); // if not first purchase

		
	if (cartList.length == 0) {
		$.notify("No products selected", "success");
	}
	else if (!cn.match(visano) && !cn.match(mcno) && cn != ''){
		$.notify("Invalid card number entered! "+cn, "success");
	}
	else if (!ed.match(exp) && ed != ''){
		$.notify("Invalid expiry date! "+ed, "success");
	}
	else if ((cardYear < thisYear || (cardYear == thisYear && cardMonth < thisMonth)) && ed != ''){
		$.notify("Card expired!", "success");
	}
	else if (deliveryType == 'delhome' && ($("#address1").val() == '' || $("#postcode").val() == '' || $("#town").val() == '')){
		$.notify("Please provide an address for delivery!", "success");
	}
	else if (!mobile.match(phoneno) && mobile != ''){
		$.notify("Invalid phone number entered! "+mobile, "success");
	}
	else if (!email.match(emailformat) && email != ''){
		$.notify("Invalid email address entered! "+email, "success");
	}
	else if ((fname == '' || lname == ''  || email == ''  || mobile == '') && ccode == ''){
		$.notify("Please fill out all details.", "success");
	}
	else
	{	
		$.ajax({
			url:"processPayments.php",    
			type: "post",   
			data: {fname: fname,
				   lname: lname,
				   mobile: $("#mobile").val(),
				   email: $("#eMail").val(),
				   add1: add1,
				   add2: add2,
				   postcode: $("#postcode").val(),
				   town: town,
				   state: $("#state").val(),
				   country: ct,
				   countrycode: ctcode,
				   ccode: $("#custnum").val(),
				   cartList: cartList,
				   paymentType: paymentType,
				   deliveryType: deliveryType,
				   shipping: shipping
			},
			dataType: 'text',
			success:function(result){
				$('#checkoutForm').html(result);
				cartTotal = 0;
				cartList = [];
				cartList.length = 0;
				$("#loggedIn").text(ccode);
				$(".total-count").html(cartList.length);
				$(".total-count").hide();$("#cocart").hide();
				sessionStorage.setItem('cartStore', JSON.stringify(cartList));
				sessionStorage.setItem('cartTotal',cartTotal);
			}
		});
	}
});


$(document).on('click', '#updateClient', function(event){

	var phoneno = /^\+(?:[0-9] ?){6,25}[0-9]$/;
	var emailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
	var pn = $("#mobile").val().replaceAll(" ","");
	var em = $("#eMail").val();
	var ct = $( "#countries option:selected" ).text();
	var ctcode = $( "#countries option:selected" ).val();
	var fname = $("#firstName").val();
	var lname = ($("#lastName").val());
	var add1 = ($("#address1").val());
	var add2 = ($("#address2").val());
	var town = ($("#town").val());
		
	if (!pn.match(phoneno) && pn != ''){
		$.notify("Invalid phone number entered! "+pn, "success");
	}
	else if (!em.match(emailformat) && em != ''){
		$.notify("Invalid email address entered! "+em, "success");
	}
	else
	{	
		$.ajax({
			url:"updateClient.php",    
			type: "post",   
			data: {fname: fname,
				   lname: lname,
				   mobile: $("#mobile").val(),
				   email: $("#eMail").val(),
				   add1: add1,
				   add2: add2,
				   postcode: $("#postcode").val(),
				   town: town,
				   state: $("#state").val(),
				   country: ct,
				   countrycode: ctcode,
				   ccode: $("#loggedIn").text().trim()
			},
			dataType: 'text',
			success:function(result){
				
				$.notify(result, "success");
			}
		});
	}
});

$(document).on('click', '#loggedIn', function(event){

	var ccode = $("#loggedIn").text().trim();
	
	if (ccode == '' || ccode == 'Guest Account'){
		$.notify("You are not logged in! "+ccode, "success");
	}
	else
	{	
		var new_url = 'myAccount.php?data=' + ccode;
		window.location.href = new_url;
	}
});




$("#loginfield").on('keyup', function (e) {
	if (e.key === 'Enter' || e.keyCode === 13) {
		var searchClientCode = $('#loginfield').val();
		
		$.ajax({
			url:"searchProduct.php",    
			type: "post",   
			data: {searchClientCode: searchClientCode},
			dataType: 'text',
			success:function(result){
				if (result == 1 ){
					var new_url = 'myAccount.php?data=' + searchClientCode;
					window.location.href = new_url;
				}
				else
				{
					$.notify("No client found with code: "+searchClientCode, "success");
				}
			}
		});

	}
});



//search for product in 2D array


function findInArray (searchTerm,searchArray){
  if (searchArray.length < 1) {return -1}
  var index = -1
  for (i=0;i<searchArray.length;i++){
    if (searchArray[i][0] === searchTerm){
      index = i;
    }
  }
  return index;
}

//remove product from 2D array
function removeFromArray(searchTerm,searchArray){
  for (i=0;i<searchArray.length;i++){
    if (cartList[i][0] === searchTerm){
      cartList.splice(i,1);
    }
  }
}

function updateCart(searchTerm,items){
	 for (i=0;i<cartList.length;i++){
		if (cartList[i][0] === searchTerm){
			cartList[i][1] = items;
		}
	 }	
}



