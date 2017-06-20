<div class="row">
    <div class="col s12">
        <ul class="tabs">
            <li class="tab col s3"><a href="#city">Города</a></li>
            <li class="tab col s3"><a href="#products">Товары</a></li>
            <li class="tab col s3"><a href="#test3">Disabled Tab</a></li>
            <li class="tab col s3"><a href="#test4">Test 4</a></li>
        </ul>
    </div>
    <div id="city" class="col s12">
        <table id="city-content">
            <thead>
            <th>ID</th>
            <th>Name</th>
            <th>Districts</th>
            <th>Actions</th>
            </thead>
        </table>
        <br>
        <br>
        <div class="row">
            <h4>Добавить новый город:</h4>
            <div class="row">
                <div class="input-field col s12">
                    <input id="new-city" type="text" class="validate">
                    <label for="new-city">Название города</label>
                </div>
                <button class="btn waves-effect waves-light btn-large" onclick="createCity()">Добавить</button>
            </div>
        </div>
    </div>
    <div id="products" class="col s12">
        <table id="products-content">
            <thead>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Actions</th>
            </thead>
        </table>
        <br>
        <br>
        <div class="row">
            <h4>Добавить новый продукт:</h4>
            <div class="row">
                <div class="input-field col s12">
                    <input id="new-product-name" type="text" class="validate">
                    <label for="new-product-name">Название</label>
                </div>
                <div class="input-field col s12">
                    <input id="new-product-price" type="text" class="validate">
                    <label for="new-product-price">Цена</label>
                </div>
                <button class="btn waves-effect waves-light btn-large" onclick="createProduct()">Добавить</button>
            </div>
        </div>
    </div>
    <div id="test3" class="col s12">Test 3</div>
    <div id="test4" class="col s12">Test 4</div>
</div>

<!-- Модальное окно для районов -->
<div id="districts-modal" class="modal">
    <div id="districts-preloader" class="center center-align">
        <div class="preloader-wrapper big active ">
            <div class="spinner-layer spinner-blue-only">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
    </div>

    <h4 id="districts-city" class="center-align"></h4>

    <table id="districts-content">
        <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>
        </thead>
    </table>
    <br>
    <br>
    <div class="row center">
        <h4>Добавить новый район:</h4>
        <input id="new-district-city-id" name="new-district-city-id" type="hidden" value="">

        <div class="row s10 offset-l">
            <div class="input-field">
                <input id="new-district" type="text" class="validate">
                <label for="new-district">Название района</label>
            </div>
        </div>
            <button class="btn waves-effect waves-light btn-large" onclick="createDistrict()">Добавить</button>
    </div>
</div>

<script>
	$(document).ready(getCity);
	$(document).ready(getProducts);
	$("#districts-modal").modal({
		complete: function() {
			$("#districts-preloader").show();
			$("#districts-city").innerHTML = "";
			$("#districts-content").hide();

			var data  = $('[id^="district-"]');
			for (var i = 0; i < data.length; i++) {
				data[i].remove();
            }
        }
    });
</script>