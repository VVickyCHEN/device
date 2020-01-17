layui.use('form', function() {
	var form = layui.form;
		
	var country = $("#country"),
		province = $("#province"),
		city = $("#city");

	//初始将省份数据赋予
	for (var i = 1; i < areaObj.length; i++) {
		addEle(country, areaObj[i].n,i);
	}
	
	//赋予完成 重新渲染select
	form.render('select');
	
	//向select中 追加内容
	function addEle(ele, value,index) {
		var optionStr = "";		
		optionStr = '<option value="'+value+'" title="'+(index)+'">'+value+'</option>';
		ele.append(optionStr);
	}

	//移除select中所有项 赋予初始值
	function removeEle(ele) {
		ele.find("option").remove();
	}

	var a = '';
	
	//选定省份后 将该省份的数据读取追加上
	form.on('select(country)', function(data) {
		removeEle(province);
		removeEle(city);

		a = data.elem[data.elem.selectedIndex].title;

		var _country = areaObj[a];
		var country = '';
		for (var b in _country) {
			var objcountry = _country[b];
		
			//选省
			if (objcountry.n) {
				addEle(province, objcountry.n,b);
			}
		}

		//选市
		var _city = areaObj[a][0];
		
		for (var c in _city) {
			var objCity = _city[c];
			if (objCity.n) {
				addEle(city, objCity.n);
			}
		}

		//重新渲染select 
		form.render('select');

	})

	////选定市或直辖县后 将对应的数据读取追加上
	form.on('select(province)', function(data) {
		removeEle(city);

		var b = data.elem[data.elem.selectedIndex].title;

		var _city = areaObj[a][b];
		var province = '';
		for (var c in _city) {
			var objCity = _city[c];
			if (objCity.n) {
				addEle(city, objCity.n);
			}
		}
		//重新渲染select 
		form.render('select');

		
	})

	



})