//Json数据处理函数

/*
 * 获取Json中的数据
 * 将不重复的字段值以 "key":value 的方式存入js对象中，并将对象存入数组中
 * */
function fJsonNoRepeat(fpJsonData,fpField){
	let vArray = [];
	let vMember,vJudgeBo;
	for(let i in fpJsonData){
		vMember = fpJsonData[i][fpField];
		vJudgeBo = false;
		for(let c in vArray){
			if(vArray[c]["key"]==vMember){
				vJudgeBo = true;
				break;
			}
		}
		if(!vJudgeBo){
			vArray.push({"key":vMember,"data":[]});
		}
	}
	return vArray;
}

/*
 * 获取Json中的数据
 * 将不重复的字段值以 "key":value 的方式存入js对象中，并将对象存入数组中
 * */
function fJsonNoRepeatArray(fpJsonObj,fpJsonData,fpJudgeField,fpFieldArray){
	let vMember,vJudgeField,vValueObj;
	for(let i in fpJsonData){
		vMember = fpJsonData[i];
		vJudgeField = vMember[fpJudgeField];
		for(let c in fpJsonObj){
			if(fpJsonObj[c]["key"] == vJudgeField){
				vValueObj = {};
				for(let k in fpFieldArray){
					vValueObj[fpFieldArray[k]] = fpJsonData[i][fpFieldArray[k]];
				}
				fpJsonObj[c]["data"].push(vValueObj);
			}
		}
	}
	return fpJsonObj;
}