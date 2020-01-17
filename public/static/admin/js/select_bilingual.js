layui.use('form', function() {
	var form = layui.form;

	var countryList=gj.Location.CountryRegion;

	var country = $("#country"),
		province = $("#province"),
		city = $("#city");

	//初始将省份数据赋予
	for (var i = 0; i < countryList.length; i++) {
		addEle(country, countryList[i]['-Name'],i);
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

	var a;
	
	//选定省份后 将该省份的数据读取追加上
	form.on('select(country)', function(data) {
		removeEle(province);
		removeEle(city);

		a = data.elem[data.elem.selectedIndex].title;
	
		//选了国家后，省市有可能为空的
		if(_country = countryList[a]['State']){
			var _country = countryList[a]['State'];
			var country = '';
			for (var b in _country) {
				var objcountry = _country[b]['-Name'];
				if (objcountry) {
					addEle(province, objcountry,b);
				}
			}
		}else{
			// 没有选项
			addEle(province,'');
		}
		
		//选市 //城市有可能是空的
		if(countryList[a]['State']){
			if(countryList[a]['State'][0]){
				var _city = countryList[a]['State'][0]['City'];
				for (var c in _city) {
					var objCity = _city[c]['-Name'];
					if (objCity) {
						addEle(city, objCity);
					}
				}
			}else{
				var _city = countryList[a]['State']['City'];
				for (var c in _city) {
					var objCity = _city[c]['-Name'];
					if (objCity) {
						addEle(city, objCity);
					}
				}
			}
		}else{
			// 没有选项
			addEle(city,'');
		}
		

		//重新渲染select 
		form.render('select');

	})

	////选定市或直辖县后 将对应的数据读取追加上
	form.on('select(province)', function(data) {
		removeEle(city);

		var b = data.elem[data.elem.selectedIndex].title;
		//选市 //城市有可能是空的
		if(countryList[a]['State'][b]){
			var _city = countryList[a]['State'][b]['City'];
			for (var c in _city) {
				var objCity = _city[c]['-Name'];
				if (objCity) {
					addEle(city, objCity);
				}
			}
		}else{
			var _city = countryList[a]['State']['City'];
			for (var c in _city) {
				var objCity = _city[c]['-Name'];
				if (objCity) {
					addEle(city, objCity);
				}
			}
		}

		//重新渲染select 
		form.render('select');
	})

	



})