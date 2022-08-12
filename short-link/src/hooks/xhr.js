async function useXhrGet(api, data = {}) {
	api = data ? (api + object2queryParams(data)) : api;
	return await useXhr('GET', api);
}
async function useXhrPost(api, data) {
	return await useXhr('POST', api, data);
}
async function useXhrPut(api, data) {
	return await useXhr('PUT', api, data);
}
async function useXhrDelete(api, data) {
	return await useXhr('DELETE', api, data);
}
async function useXhr(methods = 'POST', url, data = '') {
	return new Promise((resolve, reject) => {
		// NProgress.start();
		if (methods != 'GET') {
			data = JSON.stringify(data);
		} else {
			data = null;
		}
		var xhr = new XMLHttpRequest();
		xhr.withCredentials = true;
		xhr.onreadystatechange = function(){
			if (this.readyState == 4) {
				// 请求完成
				// NProgress.done();
				if (isJSON(this.responseText)) {
					// 解析json
					const json = JSON.parse(this.responseText);
					if (json.code == 200) {
						resolve(json.data);
					} else {
						reject(json);
					}
				} else if (this.responseText) {
					console.error('xhr failed: response is\'n JSON');
				} else {
					// 断网等情况
					console.error('$ajax network failed', {response: this});
				}
			}
		};
		xhr.open(methods, url, true);
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.send(data);
	});
}

function isJSON(str){
	if(typeof str=='string'){
		try{
			var obj=JSON.parse(str);
			if(typeof obj=='object'&&obj){
				return true;
			}else{return false;}
		}catch(e){return false;}
	}
}

function object2queryParams(data = {}, isPrefix = true, arrayFormat = 'brackets') {
    if (!data) return;
	let prefix = isPrefix ? '?' : ''
    let _result = []
    if (['indices', 'brackets', 'repeat', 'comma'].indexOf(arrayFormat) == -1) arrayFormat = 'brackets';
    for (let key in data) {
        let value = encodeURI(data[key]);
        // 去掉为空的参数
        if (['', undefined, null].indexOf(value) >= 0) {
            continue;
        }
        // 如果值为数组，另行处理
        if (value.constructor === Array) {
            // e.g. {ids: [1, 2, 3]}
            switch (arrayFormat) {
                case 'indices':
                    // 结果: ids[0]=1&ids[1]=2&ids[2]=3
                    for (let i = 0; i < value.length; i++) {
                        _result.push(key + '[' + i + ']=' + value[i]);
                    }
                    break;
                case 'brackets':
                    // 结果: ids[]=1&ids[]=2&ids[]=3
                    value.forEach(_value => {
                        _result.push(key + '[]=' + _value);
                    })
                    break;
                case 'repeat':
                    // 结果: ids=1&ids=2&ids=3
                    value.forEach(_value => {
                        _result.push(key + '=' + _value);
                    })
                    break;
                case 'comma':
                    // 结果: ids=1,2,3
                    let commaStr = "";
                    value.forEach(_value => {
                        commaStr += (commaStr ? "," : "") + _value;
                    });
                    _result.push(key + '=' + commaStr);
                    break;
                default:
                    value.forEach(_value => {
                        _result.push(key + '[]=' + _value);
                    })
            }
        } else {
            _result.push(key + '=' + value);
        }
    }
    return _result.length ? prefix + _result.join('&') : '';
}

export {
	useXhrGet,
	useXhrPost,
	useXhrPut,
	useXhrDelete,
	useXhr
}