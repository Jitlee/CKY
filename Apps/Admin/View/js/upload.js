
//上傳文件

function getUploadFilename(sfilename,srcpath,thumbpath,fname){
	if(srcpath!="fail"){
		$("#s_"+sfilename).val(srcpath);
		$("#"+fname).val(srcpath);
		if(fname=="goodsImg" || fname=="logo" || fname=="mlogo" || fname=="adFile"){
			$("#goodsThumbs").val(thumbpath);
		}
		else if(fname=="adpath")
		{
			$("#malladThumbs").val(thumbpath);
		}
		//显示图片
        if(fname=="logo")
        {
        	$("#preview_"+sfilename).html("<img  height='70' src='"+ThinkPHP.ROOT+"/"+thumbpath+"'/>");	
        }
        else if(fname=="mlogo") //商场活动-分类
        {
        	$("#preview_"+sfilename).html("<img   height='70' src='"+ThinkPHP.ROOT+"/"+thumbpath+"'/>");	
        }
        else
        {
        	$("#preview_"+sfilename).html("<img  height='152' src='"+ThinkPHP.ROOT+"/"+thumbpath+"'/>");	
        }
	}else{
		$("#s_"+sfilename).val("");
		$("#"+fname).val("");
	}
}

function updfile(filename){
	
	var filepath = jQuery("#"+filename).val();
	var patharr = filepath.split("\\");
	var fnames = patharr[patharr.length-1].split(".");
	var ext = (fnames[fnames.length-1]);
		ext = ext.toLocaleLowerCase();	
	var flag = false;
	for(var i=0;i<filetypes.length;i++){
		if(filetypes[i]==ext){
			flag = true;
			break;
		}
	}
	
	if(flag){	
		jQuery("#uploadform_"+filename).submit();
	}else{		
		alert("上传文件类型错误 (文档支持格式："+filetypes.join(",")+")");		
		jQuery('#uploadform_'+filename)[0].reset();
		return;
	}	
}
