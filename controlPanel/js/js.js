function getCity () {
	$.ajax({
		url: "/TelegramShopBot/controlPanel/get.php",
		type: "POST",
		dataType: "json",
		data: {entity: "city", method: "getAll"},

		success: function (data) {
			for (var i = 0; i < data.length; i++) {
				addCity(data[i]["id"], data[i]["name"]);
			}
		}

	});
}

function addCity(id, name) {
	$("#city-content").append(
		"<tr id='city-" + id + "'>" +
		"<td>" + id + "</td>" +
		"<td>" + name + "</td>" +
		"<td><a onclick='showDistricts(" + id + ")'>Show districts</a></td>" +
		"<td>" +
			"<a onclick='deleteCity(" + id + ", \"" + name + "\")'><i class='material-icons'>delete</i></a>" +
			"<a onclick='editCity(" + id + ")'><i class='material-icons'>mode_edit</i></a>" +
		"</td>" +
		"</tr>"
	);
}

function editCity(id) {
	var element = $("tr#city-"+id+" td");

	element[1].innerHTML = "<input type='text' value='"+element[1].innerText +"'>";
	element[3].innerHTML = element[3].innerHTML.replace("mode_edit", "done").replace("editCity", "saveCity");
}

function saveCity(id) {
	var element = $("tr#city-"+id+" td");

	var name = element[1].getElementsByTagName("input")[0].value;

	$.ajax({
		url: "/TelegramShopBot/controlPanel/get.php",
		type: "POST",
		dataType: "json",
		data: {entity: "city", method: "update", id: id, name: name},
		success: function () {
			element[1].innerHTML = name;
			element[3].innerHTML = element[3].innerHTML.replace("done", "mode_edit").replace("saveCity", "editCity");
		}
	});
}

function createCity() {
	var element = $("input#new-city")[0];

	$.ajax({
		url: "/TelegramShopBot/controlPanel/get.php",
		type: "POST",
		dataType: "json",
		data: {entity: "city", method: "create", name: element.value},
		success: function (data) {
			element.value = '';
			addCity(data['id'], data['name'])
		}
	});
}

function deleteCity(id, name) {
	if (confirm("Вы точно хотите удалить город \"" + name + "\"?")) {
		$.ajax({
			url: "/TelegramShopBot/controlPanel/get.php",
			type: "POST",
			dataType: "json",
			data: {entity: "City", method: "delete", id: id},
			success: function () {
				$("tr#city-"+id).remove();
			}
		});
	}
}


/**********************************/
/**************PRODUCTS************/
/**********************************/

function showDistricts(cityId) {
	$('#districts-modal').modal('open');

	$.ajax({
		url: "/TelegramShopBot/controlPanel/get.php",
		type: "POST",
		dataType: "json",
		data: {entity: "district", method: "getAll", city_id: cityId},

		success: function (data) {
			var city = data["city_name"];
			delete data["city_name"];

			for (var i in data) {
				addDistrict(data[i]["id"], data[i]["name"]);
			}

			$("#districts-preloader").hide();
			$("#districts-city").text("Районы города " + city);
			$("#districts-content").show();
			$("#new-district-city-id")[0].value = cityId;
		}
	});
}

function addDistrict(id, name) {
	$("#districts-content").append(
		"<tr id='district-" + id + "'>" +
			"<td>" + id + "</td>" +
			"<td>" + name + "</td>" +
			"<td>" +
				"<a onclick='deleteDistrict(" + id + ", \"" + name + "\")'><i class='material-icons'>delete</i></a>" +
				"<a onclick='editDistrict(" + id + ")'><i class='material-icons'>mode_edit</i></a>" +
			"</td>" +
		"</tr>"
	);
}

function editDistrict(id) {
	var element = $("tr#district-"+id+" td");

	element[1].innerHTML = "<input type='text' value='"+element[1].innerText +"'>";
	element[2].innerHTML = element[2].innerHTML.replace("mode_edit", "done").replace("editDistrict", "saveDistrict");
}

function saveDistrict(id) {
	var element = $("tr#district-"+id+" td");

	var name = element[1].getElementsByTagName("input")[0].value;

	$.ajax({
		url: "/TelegramShopBot/controlPanel/get.php",
		type: "POST",
		dataType: "json",
		data: {entity: "district", method: "update", id: id, name: name},
		success: function () {
			element[1].innerHTML = name;
			element[2].innerHTML = element[2].innerHTML.replace("done", "mode_edit").replace("saveDistrict", "editDistrict");
		}
	});
}

function deleteDistrict(id, name) {
	if (confirm("Вы точно хотите район \"" + name + "\"?")) {
		$.ajax({
			url: "/TelegramShopBot/controlPanel/get.php",
			type: "POST",
			dataType: "json",
			data: {entity: "district", method: "delete", id: id},
			success: function () {
				$("tr#district-"+id).remove();
			}
		});
	}
}

function createDistrict() {
	var element = $("input#new-district")[0],
		cityId = $("#new-district-city-id")[0].value;

	$.ajax({
		url: "/TelegramShopBot/controlPanel/get.php",
		type: "POST",
		dataType: "json",
		data: {entity: "district", method: "create", city_id: cityId, name: element.value},
		success: function (data) {
			element.value = '';
			addDistrict(data['id'], data['name'])
		}
	});
}

/**********************************/
/**************PRODUCTS************/
/**********************************/

function getProducts() {
	$.ajax({
		url: "/TelegramShopBot/controlPanel/get.php",
		type: "POST",
		dataType: "json",
		data: {entity:"product", method: "getAll"},

		success: function (data) {
			for (var i = 0; i < data.length; i++) {
				addProduct(data[i]["id"], data[i]["name"], data[i]["price"]);
			}
		}
	});
}

function addProduct(id, name, price) {
	$("#products-content").append(
		"<tr id='product-" + id + "'>" +
			"<td>" + id + "</td>" +
			"<td>" + name + "</td>" +
			"<td>" + price + "</td>" +
			"<td>" +
				"<a onclick='deleteProduct(" + id + ", \"" + name + "\")'><i class='material-icons'>delete</i></a>" +
				"<a onclick='editProduct(" + id + ")'><i class='material-icons'>mode_edit</i></a>" +
			"</td>" +
		"</tr>"
	);
}

function editProduct(id) {
	var element = $("tr#product-"+id+" td");

	element[1].innerHTML = "<input type='text' value='"+element[1].innerText +"'>";
	element[2].innerHTML = "<input type='text' value='"+element[2].innerText +"'>";
	element[3].innerHTML = element[3].innerHTML.replace("mode_edit", "done").replace("editProduct", "saveProduct");
}

function saveProduct(id) {
	var element = $("tr#product-"+id+" td");

	var name = element[1].getElementsByTagName("input")[0].value,
		price = element[2].getElementsByTagName("input")[0].value;


	$.ajax({
		url: "/TelegramShopBot/controlPanel/get.php",
		type: "POST",
		dataType: "json",
		data: {entity:"product", method: "update", id: id, name: name, price: price},
		success: function () {
			element[1].innerHTML = name;
			element[2].innerHTML = price;
			element[3].innerHTML = element[3].innerHTML.replace("done", "mode_edit").replace("saveProduct", "editProduct");
		}
	});
}

function deleteProduct(id, name) {
	if (confirm("Вы дейтсвительно хотите удалить товар \"" + name + "\"?")) {
		$.ajax({
			url: "/TelegramShopBot/controlPanel/get.php",
			type: "POST",
			dataType: "json",
			data: {entity:"product", method: "delete", id: id},
			success: function () {
				$("tr#product-"+id).remove();
			}
		});
	}
}

function createProduct() {
	var name = $("input#new-product-name")[0];
	var price = $("input#new-product-price")[0];

	$.ajax({
		url: "/TelegramShopBot/controlPanel/get.php",
		type: "POST",
		dataType: "json",
		data: {entity: "product", method: "create", name: name.value, price: price.value},
		success: function (data) {
			name.value = '';
			price.value = '';
			addProduct(data['id'], data['name'], data['price'])
		}
	});
}