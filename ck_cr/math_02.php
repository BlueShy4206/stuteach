 	<div class="question">
		<div class="container">
			<p class="q_text">
				有一個厚度1公分的無蓋透明圓柱容器，外部的直徑是12公分，高度是10公分，原本容器內已經有高度5公分的水，婷婷放進一顆彩繪石頭做裝飾，水面刻度升高為7公分，石頭的體積是多少立方公分?請在算式記錄區列出算式與答案。
			</p>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="dragArea" id="dragArea">
					<p style="font-size: 19px;">算式記錄區</p>

					<div class="dragWrapper">
						<div class="dragArea0" id="dragArea0">
						<textarea id="math_textarea" rows="4" cols="50"></textarea>
							
						</div>
						<input type="button" value="重新作答" name="renew_submit" alt="renew_submit" class="btn btn-default" style="position: absolute; margin: -2px 378px; font-size: 19px;" onclick="resetEvent()">
						<div style=" background-color: #dbdbdb;width: 450px;height: 169px;position: absolute; margin: -150px 3px; border: 3px solid #b7b7b7;border-radius: 10px;"><b style="font-family:標楷體;font-size:18px;">算式輸入器</b>
							
                              <table border="0" bgcolor="#ededed" style="border-radius: 10px;">
								<tr >
									<td width="50"><!-- javascript:inputNumber('1'); -->
										<input id="input_button_number_1" onclick="javascript:inputNumber(1,'1');" value="1" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;" >
									</td>
									<td width="50">
										<input id="input_button_number_4" onclick="javascript:inputNumber(1,'4');" value="4" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									<td width="50">
										<input id="input_button_number_7" onclick="javascript:inputNumber(1,'7');" value="7" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									<td width="50">
										<input id="input_button_add" onclick="javascript:inputNumber(1,'+');" value="+" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									<td>	
										<input type="button"  onclick="javascript:inputNumber(1,'(')" value="(" style="border-radius: 10px;width:60px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;" >																			
									</td>																		
									<td>
										<input type="button"  onclick="javascript:inputNumber(1,')')" value=")" style="border-radius: 10px;width:60px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;" >																			
									</td>
								</tr >
								<tr>
									<td width="50">
										<input id="input_button_number_2" onclick="javascript:inputNumber(1,'2');" value="2" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									<td width="50">
										<input id="input_button_number_5" onclick="javascript:inputNumber(1,'5');" value="5" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									
									
									<td width="50">
										<input id="input_button_number_8" onclick="javascript:inputNumber(1,'8');" value="8" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									<td width="50">                                                                                       
										<input id="input_button_sub" onclick="javascript:inputNumber(1,'-');" value="-" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									<td width="50">
										<input id="input_button_threepoint" onclick="javascript:inputNumber(1,'公分');" value="公分" name="alex" type="button" style="background-color: #b5adad;border-radius: 10px;width: 60px;font-family:標楷體;font-weight:bold;font-size:20px;">
									</td>
									
								</tr>	
								
								 <tr >
								 	<td width="50">
										<input id="input_button_number_3" onclick="javascript:inputNumber(1,'3');" value="3" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									<td width="50">
										<input id="input_button_number_6" onclick="javascript:inputNumber(1,'6');" value="6" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									<td width="50">
										<input id="input_button_number_9" onclick="javascript:inputNumber(1,'9');" value="9" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									
									
									<td width="50">
										<input id="input_button_times" onclick="javascript:inputNumber(1,'×');" value="×" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									<td width="100" colspan="2">
										<input id="input_button_threepoint" onclick="javascript:inputNumber(1,'平方公分');" value="平方公分" name="alex" type="button" style="background-color: #b5adad; border-radius: 10px;width:120px;font-family:標楷體;font-weight:bold;font-size:20px;">
									</td>
									<td width="100" colspan="2">
										<input id="input_button_threepoint" onclick="javascript:inputNumber(1,'立方公分');" value="立方公分" name="alex" type="button" style="background-color: #b5adad;border-radius: 10px;width:120px;font-family:標楷體;font-weight:bold;font-size:20px;">
									</td>
									
									<!-- <td width="50" style="width:35px;display:none;" >
										<input id="input_button_null" onclick="javascript:inputNumber('?');" value="?" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;background: #FFFFFF;">
									</td> -->
								</tr>
								<tr >
									<td width="50">
										<input id="input_button_point" onclick="javascript:inputNumber(1,'.');" value="." name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									<!-- <td width="50" style="display:none;">
										<input id="input_button_zero2" onclick="javascript:inputNumber(1,'O');" value="O" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td> -->
									<!-- <td width="50">
										<input id="input_button_threepoint" onclick="javascript:inputNumber('、、、');" value="餘" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td> -->
									<td width="50">
										<input id="input_button_number_0" onclick="javascript:inputNumber(1,'0');" value="0" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									<td width="50">
										<input id="input_button_equal" onclick="javascript:inputNumber(1,'=');" value="=" name="alex" type="button" style="border-radius: 10px;width:50px;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									
									<td width="50">
										<input id="input_button_division" onclick="javascript:inputNumber(1,'÷');" value="÷" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									<td width="100" colspan="2">
										<input id="input_button_threepoint" onclick="javascript:inputNumber(0,'←');" value="← 刪除" name="alex" type="button" style="background-color: #b5adad;border-radius: 10px;width:120px;font-family:標楷體;font-weight:bold;font-size:20px;">
									</td>
								 
									<td width="100" colspan="2">
										<input type="button"  onclick="javascript:inputNumber(1,'\n')" value="換行" style="background-color: #b5adad;border-radius: 10px;width:120px;font-family:標楷體;font-weight:bold;font-size:20px;" >																			
									</td>
									<td width="50" style="width:35px;display:none;">
										<input id="input_button_delete" onclick="javascript:delNumber();" value="←" name="alex" type="button" style="border-radius: 10px;width:50px;font-family:標楷體;font-weight:bold;font-size:20px;background: #FFFFFF;">
									</td>
									<td width="50" style="display:none;">
										&nbsp;
									</td>
								</tr>
								
								  
							</table>
						</div>
						<p style="position: absolute;margin: 90px -1px;width: 836px; height:65px;font-size: 19px;">操作說明：<br>算式輸入器處可利用滑鼠點選數字和符號的按鈕，算式將會出現並存入於算式記錄區。<br>◎若要刪除前一步輸入的數字或符號，可按算式輸入器上<input id="input_button_threepoint" value="← 刪除" name="alex" type="button" style="background-color: #b5adad;border-radius: 10px;width:80px;font-family:標楷體;font-weight:bold;font-size:14px;">；<br>&nbsp;&nbsp;若要將算式全部清除，可點選右方<input id="input_button_threepoint" type="button" value="重新作答" name="alex"  style="border-radius: 8px;width:80px;font-family:標楷體;font-weight:bold;font-size:14px;background: #FFFFFF;">。<br>
						◎完成本題計算後點選”確認”按鈕，即可繼續作答下一題。</p>
						<!-- <p style="position: absolute;margin: -248px 514px;width: 469px; height:581px;">步驟1：在日期上【單擊滑鼠左鍵】以點標記溫度。</p>
						<p style="position: absolute;margin: -220px 514px;width: 469px; height:581px;">步驟2：依日期上標記溫度之點，完成折線圖。</p> -->
					</div>
				</div>
			</div>

		</div>

	</div>


<script type="text/javascript">
<!--
function mm()
{
    var s = document.getElementById("txt").value;
    var r = s.match(/[^+\-*/()]+/g);
    if(r)
    {
        var mm = {};
        for(var i=0; i<r.length; i++)
        {
            if("undefined"==typeof(mm[r[i]]))
            {
                mm[r[i]] = r[i];
            }
        }        for(var i in mm)
        {
            s = s.replace(new RegExp(i, "g"), "1");
        }
        try
        {
        	if(!isNaN(eval(s)))
            {
                alert("right");
                return;
            }
        }
        catch (ex)
        {
        }
    }
    alert("error!");
}
//-->

$("#math_textarea").keydown(false);//lock keyboard input
document.oncontextmenu = new Function("return false");//lock mouse right click
// alert("test");
let obj_text="";
function inputNumber(blg,num){
	if(blg == 1){
		if(obj_text != "")
		{
			obj_text = obj_text+num;
		}else{obj_text = num;}

		InsertContent("math_textarea",num,obj_text);
		
	}else{
		var myArea = document.getElementById("math_textarea");
		let org_text = myArea.value;
		if(org_text != "")
		{
			myArea.value=org_text.substr(0,org_text.length-1);
		}
	}
}

function InsertContent(AreaID,Content,obj_text)
{
    var myArea = document.getElementById(AreaID);
 
    //IE
    if (document.selection)  
   {
      myArea.focus();
      var mySelection =document.selection.createRange();
      mySelection.text = Content;
      // $("#math_textarea").text(obj_text);
   }
   //FireFox
   else  
  {
     var myPrefix = myArea.value.substring(0, myArea.selectionStart);
     var mySuffix = myArea.value.substring(myArea.selectionEnd);
     myArea.value = myPrefix + Content + mySuffix;
      $("#math_textarea").text(myArea.value);
   }
   // alert(myArea.value);
}


</script>

<style type="text/css">
 div[id^='line'] {
        display: none;
 	}


	body{
		font-family: "DFKai-sb", "Times New Roman";	
		font-size:18px;
	}
	.userInfo{
		font-size: 24pt;
		margin-right: 15px;
	}
	.userInfoTime{
		font-size: 22pt;
		float: right;
	}
	.clearfix:after {
		visibility: hidden;
		display: block;
		font-size: 0;
		content: " ";
		clear: both;
		height: 0;
	}
	#dragArea {
		height: 548px;
		position: relative;
		border-bottom: 2px solid #9DD5FD;
	}

	.dragWrapper {
		width: 423px;
		border: 1px solid red;
		height: 82px;
	}

	div[id=dragArea] {
		position: relative;
	}

	div[id^=dragObject] {
		z-index: 99;
	}

	div[id^=dragArea] div {
		margin-bottom: 10px;
	}

	#dragArea0 {
		display: inline-block;
		width: 124px;
		height: 255px;
	}

	/*#line{
		border: 1px solid red;
		height: 10px;
	}
*/
	.areapostition_absolut {
		position: absolute;
	}

	.image1_wrapper {
		max-width: 532px;
		max-height: 99px;
		margin: 0px;
	}

	.image1_wrapper img {
		max-width: 100%;
		max-height: 100%;
	}


    #dragObject:hover {
        cursor: pointer;
    }
	.dragObject {
		display: block;
		width: 469px;
		height: 581px;
	}

	.dragObject img {
		max-width: 100%;
		max-height: 100%;
	}

	.question {
		/*border-bottom: 2px solid green;
		border-top: 2px solid blue;*/
	}

	.q_text {
		font-size: 20pt;
		//border-top: 2px solid #1e79cf;
	}

	.btnWrapper {
		width: 110%;
		height: 140px;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.btnWrapper input {
		font-size: 18pt;
	}
</style>
<script type="text/javascript">
	
	function resetEvent() {//clear textarea
		$("#math_textarea").text("");
		// obj_text="";
		var myArea = document.getElementById("math_textarea");
		myArea.value="";

	}


	function submit() {
		let ansFlag , ansFlag2 = false;		
		let strAry =new Array();
		let strAry2 =new Array();
		let score = 0;
		let ansA_cnt = 0;
		let ansB_cnt = 0;
 		 $("#math_textarea").html($("#math_textarea").html().replace(/\n/g,"<br>"));
		let ansStr=$('#math_textarea')[0].defaultValue.split('<br>');
		let valStr="";
		// alert('num='+$('#math_textarea')[0].defaultValue.split('<br>').length);
		if(ansStr.length>0){
		for(ii=0 ; ii < ansStr.length; ii++){

		valStr=ansStr[ii].split('=');

		// strAry.push('5×5×3.14×2=157','5×5×3.14×2=157(立方公分)');
		strAry.push('5×5×3.14×2',
		'(12-2)÷(7-5)×5×3.14×2',
		'(12-2)÷2×5×3.14×2',
		'10÷(7-5)×5×3.14×2',
		'10÷2×5×3.14×2',
		'5×(12-2)÷(7-5)×3.14×2',
		'5×(12-2)÷2×3.14×2',
		'5×10÷(7-5)×3.14×2',
		'5×10÷2×3.14×2',
		'(12-2)÷(7-5)×(12-2)÷(7-5)×3.14×2',
		'(12-2)÷(7-5)×(12-2)÷2×3.14×2',
		'(12-2)÷(7-5)×10÷(7-5)×3.14×2',
		'(12-2)÷(7-5)×10÷2×3.14×2',
		'(12-2)÷2×(12-2)÷(7-5)×3.14×2',
		'(12-2)÷2×(12-2)÷2×3.14×2',
		'(12-2)÷2×10÷(7-5)×3.14×2',
		'(12-2)÷2×10÷2×3.14×2',
		'10÷(7-5)×(12-2)÷(7-5)×3.14×2',
		'10÷(7-5)×(12-2)÷2×3.14×2',
		'10÷(7-5)×10÷(7-5)×3.14×2',
		'10÷(7-5)×10÷2×3.14×2',
		'10÷2×(12-2)÷(7-5)×3.14×2',
		'10÷2×(12-2)÷2×3.14×2',
		'10÷2×10÷(7-5)×3.14×2',
		'10÷2×10÷2×3.14×2'
		);
		strAry.push('5×5×2×3.14',
		'(12-2)÷(7-5)×5×2×3.14',
		'(12-2)÷2×5×2×3.14',
		'10÷(7-5)×5×2×3.14',
		'10÷2×5×2×3.14',
		'5×(12-2)÷(7-5)×2×3.14',
		'5×(12-2)÷2×2×3.14',
		'5×10÷(7-5)×2×3.14',
		'5×10÷2×2×3.14',
		'(12-2)÷(7-5)×(12-2)÷(7-5)×2×3.14',
		'(12-2)÷(7-5)×(12-2)÷2×2×3.14',
		'(12-2)÷(7-5)×10÷(7-5)×2×3.14',
		'(12-2)÷(7-5)×10÷2×2×3.14',
		'(12-2)÷2×(12-2)÷(7-5)×2×3.14',
		'(12-2)÷2×(12-2)÷2×2×3.14',
		'(12-2)÷2×10÷(7-5)×2×3.14',
		'(12-2)÷2×10÷2×2×3.14',
		'10÷(7-5)×(12-2)÷(7-5)×2×3.14',
		'10÷(7-5)×(12-2)÷2×2×3.14',
		'10÷(7-5)×10÷(7-5)×2×3.14',
		'10÷(7-5)×10÷2×2×3.14',
		'10÷2×(12-2)÷(7-5)×2×3.14',
		'10÷2×(12-2)÷2×2×3.14',
		'10÷2×10÷(7-5)×2×3.14',
		'10÷2×10÷2×2×3.14'
			);
		strAry.push('3.14×5×5×2',
		'3.14×(12-2)÷(7-5)×5×2',
		'3.14×(12-2)÷2×5×2',
		'3.14×10÷(7-5)×5×2',
		'3.14×10÷2×5×2',
		'3.14×5×(12-2)÷(7-5)×2',
		'3.14×5×(12-2)÷2×2',
		'3.14×5×10÷(7-5)×2',
		'3.14×5×10÷2×2',
		'3.14×(12-2)÷(7-5)×(12-2)÷(7-5)×2',
		'3.14×(12-2)÷(7-5)×(12-2)÷2×2',
		'3.14×(12-2)÷(7-5)×10÷(7-5)×2',
		'3.14×(12-2)÷(7-5)×10÷2×2',
		'3.14×(12-2)÷2×(12-2)÷(7-5)×2',
		'3.14×(12-2)÷2×(12-2)÷2×2',
		'3.14×(12-2)÷2×10÷(7-5)×2',
		'3.14×(12-2)÷2×10÷2×2',
		'3.14×10÷(7-5)×(12-2)÷(7-5)×2',
		'3.14×10÷(7-5)×(12-2)÷2×2',
		'3.14×10÷(7-5)×10÷(7-5)×2',
		'3.14×10÷(7-5)×10÷2×2',
		'3.14×10÷2×(12-2)÷(7-5)×2',
		'3.14×10÷2×(12-2)÷2×2',
		'3.14×10÷2×10÷(7-5)×2',
		'3.14×10÷2×10÷2×2'
			);
		strAry.push('2×5×5×3.14',
		'2×(12-2)÷(7-5)×5×3.14',
		'2×(12-2)÷2×5×3.14',
		'2×10÷(7-5)×5×3.14',
		'2×10÷2×5×3.14',
		'2×5×(12-2)÷(7-5)×3.14',
		'2×5×(12-2)÷2×3.14',
		'2×5×10÷(7-5)×3.14',
		'2×5×10÷2×3.14',
		'2×(12-2)÷(7-5)×(12-2)÷(7-5)×3.14',
		'2×(12-2)÷(7-5)×(12-2)÷2×3.14',
		'2×(12-2)÷(7-5)×10÷(7-5)×3.14',
		'2×(12-2)÷(7-5)×10÷2×3.14',
		'2×(12-2)÷2×(12-2)÷(7-5)×3.14',
		'2×(12-2)÷2×(12-2)÷2×3.14',
		'2×(12-2)÷2×10÷(7-5)×3.14',
		'2×(12-2)÷2×10÷2×3.14',
		'2×10÷(7-5)×(12-2)÷(7-5)×3.14',
		'2×10÷(7-5)×(12-2)÷2×3.14',
		'2×10÷(7-5)×10÷(7-5)×3.14',
		'2×10÷(7-5)×10÷2×3.14',
		'2×10÷2×(12-2)÷(7-5)×3.14',
		'2×10÷2×(12-2)÷2×3.14',
		'2×10÷2×10÷(7-5)×3.14',
		'2×10÷2×10÷2×3.14'
			);
		strAry.push('2×3.14×5×5',
		'2×3.14×(12-2)÷(7-5)×5',
		'2×3.14×(12-2)÷2×5',
		'2×3.14×10÷(7-5)×5',
		'2×3.14×10÷2×5',
		'2×3.14×5×(12-2)÷(7-5)',
		'2×3.14×5×(12-2)÷2',
		'2×3.14×5×10÷(7-5)',
		'2×3.14×5×10÷2',
		'2×3.14×(12-2)÷(7-5)×(12-2)÷(7-5)',
		'2×3.14×(12-2)÷(7-5)×(12-2)÷2',
		'2×3.14×(12-2)÷(7-5)×10÷(7-5)',
		'2×3.14×(12-2)÷(7-5)×10÷2',
		'2×3.14×(12-2)÷2×(12-2)÷(7-5)',
		'2×3.14×(12-2)÷2×(12-2)÷2',
		'2×3.14×(12-2)÷2×10÷(7-5)',
		'2×3.14×(12-2)÷2×10÷2',
		'2×3.14×10÷(7-5)×(12-2)÷(7-5)',
		'2×3.14×10÷(7-5)×(12-2)÷2',
		'2×3.14×10÷(7-5)×10÷(7-5)',
		'3.14×2×10÷(7-5)×10÷2',
		'2×3.14×10÷2×(12-2)÷(7-5)',
		'2×3.14×10÷2×(12-2)÷2',
		'2×3.14×10÷2×10÷(7-5)',
		'2×3.14×10÷2×10÷2'
			);
		strAry.push('3.14×2×5×5',
		'3.14×2×(12-2)÷(7-5)×5',
		'3.14×2×(12-2)÷2×5',
		'3.14×2×10÷(7-5)×5',
		'3.14×2×10÷2×5',
		'3.14×2×5×(12-2)÷(7-5)',
		'3.14×2×5×(12-2)÷2',
		'3.14×2×5×10÷(7-5)',
		'3.14×2×5×10÷2',
		'3.14×2×(12-2)÷(7-5)×(12-2)÷(7-5)',
		'3.14×2×(12-2)÷(7-5)×(12-2)÷2',
		'3.14×2×(12-2)÷(7-5)×10÷(7-5)',
		'3.14×2×(12-2)÷(7-5)×10÷2',
		'3.14×2×(12-2)÷2×(12-2)÷(7-5)',
		'3.14×2×(12-2)÷2×(12-2)÷2',
		'3.14×2×(12-2)÷2×10÷(7-5)',
		'3.14×2×(12-2)÷2×10÷2',
		'3.14×2×10÷(7-5)×(12-2)÷(7-5)',
		'3.14×2×10÷(7-5)×(12-2)÷2',
		'3.14×2×10÷(7-5)×10÷(7-5)',
		'3.14×2×10÷(7-5)×10÷2',
		'3.14×2×10÷2×(12-2)÷(7-5)',
		'3.14×2×10÷2×(12-2)÷2',
		'3.14×2×10÷2×10÷(7-5)',
		'3.14×2×10÷2×10÷2',
		'25×3.14×2',
		'25×2×3.14',
		'3.14×25×2',
		'2×25×3.14',
		'2×3.14×25',
		'3.14×2×25'
			);
		//算式檢查
		for(i=0 ; i< strAry.length ; i++){
			let flag = valStr[0].trim().indexOf(strAry[i], 0) ;
		// alert('flag1:'+ flag );
			if(flag != -1){
				if( valStr[0].trim() == strAry[i])
				{ansFlag = true; ansA_cnt=3;}
			}

		}

			//答案檢查
		strAry2.push('157立方公分','157(立方公分)');
		if(valStr.length>1){
			for(j=0 ; j< strAry2.length ; j++){
				let flag2 = valStr[1].trim().indexOf(strAry2[j], 0) ;
			// alert('flag1:'+ flag );
				if(flag2 != -1){
					if( valStr[1].trim() == strAry2[j])
					{ansFlag2 = true; ansB_cnt=2;}
				}

			}
		}
	}

	}
		// alert('value='+ansStr[1] +'\n'+'flag2='+ ansFlag2 );

		if(!ansFlag){ansB_cnt=0;}//算式對答案對才給5分
		score = ansA_cnt + ansB_cnt;
    let level = 0;
    if(score > 0){
      if(score > 3){level = 2;}
      else{level = 1;}
    }
		
    console.log('分數：'+score+' 分');
    document.keypad.user_answer.value=score; 
    document.keypad.cr_bin_res.value=level;
		//alert(document.keypad.user_answer.value+'分'+textarea:'+$("#math_textarea").text());
    //alert ('分數：'+score+' 分');
	}


</script>

