
	<div class="question">
		<div class="container">
			<p class="q_text">
				下表是聖誕節到跨年夜這段時間的每日平均溫度統計表，請依據統計表完成下面的折線圖。
			</p>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="dragArea" id="dragArea">
					<p></p>
					<div class="image1_wrapper">
						<img src="ck_cr/math_01/title_02.png">
					</div>
					<p></p>
					<p style="font-size: 19px;">繪圖區</p>

					<div class="dragWrapper">
						<div class="dragArea0" id="dragArea0">
							<div class="dragObject" id="dragObject" style="position: relative;">
							
								<img id="img" src="ck_cr/math_01/title_03.png" style="z-index:99;">
						
							</div>
						</div>
						<input type="button" value="重新作答" name="submit" alt="Submit" class="btn btn-default" style="position: absolute; margin: 184px 388px; font-size: 19px;" onclick="resetEvent()">
						<p style="position: absolute;margin: -266px 514px;width: 469px; height:105px;font-size: 19px;">操作說明：於題目下方的繪圖區繪製折線圖<br>1、在固定的位置【單擊滑鼠左鍵】即可繪出黑點。<br>2、點選繪出下一個黑點即可自動連線繪出實線。</p>
						<!-- <p style="position: absolute;margin: -248px 514px;width: 469px; height:581px;">步驟1：在日期上【單擊滑鼠左鍵】以點標記溫度。</p>
						<p style="position: absolute;margin: -220px 514px;width: 469px; height:581px;">步驟2：依日期上標記溫度之點，完成折線圖。</p> -->
            <div>
						<input type="button" value="回上一步" name="submit" alt="Submit" class="btn btn-default" style="position: absolute; margin: -162px 512px; font-size: 19px;" onclick="preEvent()">
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">			
			
			</div>
		</div>

	</div>


<script type="text/javascript">    

	//===================線的div tag=====================
	// var body = document.getElementsByTagName('body');
	// var el = document.getElementById('dragObject');
	let HtmlStr = '';
	for (i = 1; i < 10; i++){
		for(j=1; j < 7; j++){
			for(k=0; k<9 ; k++){
		HtmlStr += '<div id="line'+i+'-'+j+'-'+k+'" ></div>';
		// HtmlStr += '<div id="line'+i+'-'+j+'-'+k+'" '+'style="border: 2px solid black; display: block;"></div>';

			}
		}
	}
	// document.body.innerHTML += HtmlStr;
	// document.el.innerHTML += HtmlStr;
	$("#dragObject").append(HtmlStr);

	//====================================================

	

</script>
	
<style type="text/css">
 div[id^='line'] {
        display: none;
 	}


	/*
 	#line9-1-0{
		position: absolute;
	    left: 142px;
	    top: 56px;
	    height: 342px;
	    transform: rotate(173deg);
	    border: 2px solid black;
	}
 	#line9-1-1{
		position: absolute;
	    left: 144px;
	    top: 56px;
	    height: 300px;
	    transform: rotate(173deg);
	    border: 2px solid black;
	}
 	#line9-1-2{
		position: absolute;
	    left: 144px;
	    top: 57px;
	    height: 258px;
	    transform: rotate(171deg);
	    border: 2px solid black;
	}
 	#line9-1-3{
		position: absolute;
	    left: 144px;
	    top: 56px;
	    height: 217px;
	    transform: rotate(169deg);
	    border: 2px solid black;
	}
	#line9-1-4{
		position: absolute;
	    left: 144px;
	    top: 56px;
	    height: 177px;
	    transform: rotate(166deg);
	    border: 2px solid black;
	}
    #line9-1-5{
		position: absolute;
	    left: 144px;
	    top: 58px;
	    height: 135px;
	    transform: rotate(162deg);
	    border: 2px solid black;
	}
    #line9-1-6{
		position: absolute;
	    left: 144px;
	    top: 54px;
	    height: 99px;
	    transform: rotate(153deg);
	    border: 2px solid black;
	}
 	#line9-1-7{
		position: absolute;
	    left: 144px;
	    top: 50px;
	    height: 62px;
	    transform: rotate(136deg);
	    border: 2px solid black;
	}	
	#line9-1-8{
		position: absolute;
	    left: 144px;
	    top: 37px;
	    height: 46px;
	    transform: rotate(90deg);
	    border: 2px solid black;
	}
 	#line8-1-0{
		position: absolute;
	    left: 144px;
	    top: 98px;
	    height: 300px;
	    transform: rotate(173deg);
	    border: 2px solid black;
	}
 	#line8-1-1{
		position: absolute;
	    left: 144px;
	    top: 99px;
	    height: 258px;
	    transform: rotate(171deg);
	    border: 2px solid black;
	}
 	#line8-1-2{
		position: absolute;
	    left: 144px;
	    top: 98px;
	    height: 217px;
	    transform: rotate(169deg);
	    border: 2px solid black;
	}
	#line8-1-3{
		position: absolute;
	    left: 144px;
	    top: 98px;
	    height: 177px;
	    transform: rotate(166deg);
	    border: 2px solid black;
	}
    #line8-1-4{
		position: absolute;
	    left: 144px;
	    top: 100px;
	    height: 135px;
	    transform: rotate(162deg);
	    border: 2px solid black;
	}
    #line8-1-5{
		position: absolute;
	    left: 144px;
	    top: 96px;
	    height: 99px;
	    transform: rotate(153deg);
	    border: 2px solid black;
	}
 	#line8-1-6{
		position: absolute;
	    left: 144px;
	    top: 91px;
	    height: 66px;
	    transform: rotate(136deg);
	    border: 2px solid black;
	}
	#line8-1-7{
		position: absolute;
	    left: 144px;
	    top: 78px;
	    height: 46px;
	    transform: rotate(90deg);
	    border: 2px solid black;
	}
	#line8-1-8{
		position: absolute;
	    left: 145px;
	    top: 51px;
	    height: 58px;
	    transform: rotate(45deg);
	    border: 2px solid black;
	}
 	#line7-1-0{
		position: absolute;
	    left: 144px;
	    top: 140px;
	    height: 258px;
	    transform: rotate(171deg);
	    border: 2px solid black;
	}
 	#line7-1-1{
		position: absolute;
	    left: 144px;
	    top: 141px;
	    height: 217px;
	    transform: rotate(169deg);
	    border: 2px solid black;
	}
	#line7-1-2{
		position: absolute;
	    left: 144px;
	    top: 140px;
	    height: 177px;
	    transform: rotate(166deg);
	    border: 2px solid black;
	}
    #line7-1-3{
		position: absolute;
	    left: 144px;
	    top: 142px;
	    height: 135px;
	    transform: rotate(162deg);
	    border: 2px solid black;
	}
    #line7-1-4{
		position: absolute;
	    left: 144px;
	    top: 138px;
	    height: 99px;
	    transform: rotate(153deg);
	    border: 2px solid black;
	}
 	#line7-1-5{
		position: absolute;
	    left: 144px;
	    top: 133px;
	    height: 66px;
	    transform: rotate(136deg);
	    border: 2px solid black;
	}
	#line7-1-6{
		position: absolute;
	    left: 144px;
	    top: 120px;
	    height: 46px;
	    transform: rotate(90deg);
	    border: 2px solid black;
	}
	#line7-1-7{
		position: absolute;
	    left: 145px;
	    top: 93px;
	    height: 58px;
	    transform: rotate(45deg);
	    border: 2px solid black;
	}	
	#line7-1-8{
		position: absolute;
	    left: 144px;
	    top: 56px;
	    height: 94px;
	    transform: rotate(27deg);
	    border: 2px solid black;
	}
 	
    #line6-1-0{
		position: absolute;
	    left: 144px;
	    top: 181px;
	    height: 217px;
	    transform: rotate(169deg);
	    border: 2px solid black;
	}
	#line6-1-1{
		position: absolute;
	    left: 144px;
	    top: 180px;
	    height: 177px;
	    transform: rotate(166deg);
	    border: 2px solid black;
	}
    #line6-1-2{
		position: absolute;
	    left: 144px;
	    top: 182px;
	    height: 135px;
	    transform: rotate(162deg);
	    border: 2px solid black;
	}
    #line6-1-3{
		position: absolute;
	    left: 144px;
	    top: 178px;
	    height: 99px;
	    transform: rotate(153deg);
	    border: 2px solid black;
	}
 	#line6-1-4{
		position: absolute;
	    left: 144px;
	    top: 173px;
	    height: 66px;
	    transform: rotate(136deg);
	    border: 2px solid black;
	}
	#line6-1-5{
		position: absolute;
	    left: 144px;
	    top: 160px;
	    height: 46px;
	    transform: rotate(90deg);
	    border: 2px solid black;
	}
	#line6-1-6{
		position: absolute;
	    left: 145px;
	    top: 133px;
	    height: 58px;
	    transform: rotate(45deg);
	    border: 2px solid black;
	}	
	#line6-1-7{
		position: absolute;
	    left: 144px;
	    top: 96px;
	    height: 94px;
	    transform: rotate(27deg);
	    border: 2px solid black;
	}
	#line6-1-8{
		position: absolute;
	    left: 144px;
	    top: 58px;
	    height: 131px;
	    transform: rotate(17deg);
	    border: 2px solid black;
	}
	 #line5-1-0{
		position: absolute;
	    left: 144px;
	    top: 221px;
	    height: 180px;
	    transform: rotate(166deg);
	    border: 2px solid black;
	}
    #line5-1-1{
		position: absolute;
	    left: 144px;
	    top: 224px;
	    height: 135px;
	    transform: rotate(162deg);
	    border: 2px solid black;
	}
    #line5-1-2{
		position: absolute;
	    left: 144px;
	    top: 220px;
	    height: 99px;
	    transform: rotate(153deg);
	    border: 2px solid black;
	}
 	#line5-1-3{
		position: absolute;
	    left: 144px;
	    top: 215px;
	    height: 66px;
	    transform: rotate(136deg);
	    border: 2px solid black;
	}
	#line5-1-4{
		position: absolute;
	    left: 144px;
	    top: 202px;
	    height: 46px;
	    transform: rotate(90deg);
	    border: 2px solid black;
	}
	#line5-1-5{
		position: absolute;
	    left: 145px;
	    top: 175px;
	    height: 58px;
	    transform: rotate(45deg);
	    border: 2px solid black;
	}	
	#line5-1-6{
		position: absolute;
	    left: 144px;
	    top: 138px;
	    height: 94px;
	    transform: rotate(27deg);
	    border: 2px solid black;
	}
	#line5-1-7{
		position: absolute;
	    left: 144px;
	    top: 99px;
	    height: 131px;
	    transform: rotate(17deg);
	    border: 2px solid black;
	}
	#line5-1-8{    
		position: absolute;
	    left: 144px;
	    top: 58px;
	    height: 170px;
	    transform: rotate(13deg);
	    border: 2px solid black;
	}
    #line4-1-0{
		position: absolute;
	    left: 144px;
	    top: 264px;
	    height: 135px;
	    transform: rotate(162deg);
	}
    #line4-1-1{
		position: absolute;
	    left: 144px;
	    top: 262px;
	    height: 99px;
	    transform: rotate(153deg);
	}
 	#line4-1-2{
		position: absolute;
	    left: 144px;
	    top: 257px;
	    height: 66px;
	    transform: rotate(136deg);
	}
	#line4-1-3{
		position: absolute;
	    left: 144px;
	    top: 246px;
	    height: 46px;
	    transform: rotate(90deg);
	}
	#line4-1-4{
		position: absolute;
	    left: 145px;
	    top: 217px;
	    height: 58px;
	    transform: rotate(45deg);
	}	
	#line4-1-5{
		position: absolute;
	    left: 144px;
	    top: 180px;
	    height: 94px;
	    transform: rotate(27deg);
	}
	#line4-1-6{
		position: absolute;
	    left: 144px;
	    top: 141px;
	    height: 131px;
	    transform: rotate(17deg);
	}
	#line4-1-7{    
		position: absolute;
	    left: 144px;
	    top: 100px;
	    height: 170px;
	    transform: rotate(13deg);
	}
	#line4-1-8{
		position: absolute;
	    left: 143px;
	    top: 58px;
	    height: 212px;
	    transform: rotate(11deg);
	}*/
    /*$("#line3-"+i+"-0").css({ "position": "absolute",	    "left": left0_px+"px",	    "top": "303px",	    "height": "99px",	    "transform": "rotate(153deg)" });
 	#line3-1-0{
		position: absolute;
	    left: 144px;
	    top: 303px;
	    height: 99px;
	    transform: rotate(153deg);
	}
 	#line3-2-0{
		position: absolute;
	    left: 186px;
	    top: 303px;
	    height: 99px;
	    transform: rotate(153deg);
	}
 	#line3-1-1{
		position: absolute;
	    left: 144px;
	    top: 299px;
	    height: 66px;
	    transform: rotate(136deg);
	}
 	#line3-2-1{
		position: absolute;
	    left: 186px;
	    top: 299px;
	    height: 66px;
	    transform: rotate(136deg);
	}
	#line3-1-2{
		position: absolute;
	    left: 144px;
	    top: 288px;
	    height: 46px;
	    transform: rotate(90deg);
	}
	#line3-1-3{
		position: absolute;
	    left: 145px;
	    top: 259px;
	    height: 58px;
	    transform: rotate(45deg);
	}	
	#line3-1-4{
		position: absolute;
	    left: 144px;
	    top: 222px;
	    height: 94px;
	    transform: rotate(27deg);
	}
	#line3-1-5{
		position: absolute;
	    left: 144px;
	    top: 183px;
	    height: 131px;
	    transform: rotate(17deg);
	}
	#line3-1-6{    
		position: absolute;
	    left: 144px;
	    top: 142px;
	    height: 170px;
	    transform: rotate(13deg);
	}
	#line3-1-7{
		position: absolute;
	    left: 143px;
	    top: 100px;
	    height: 212px;
	    transform: rotate(11deg);
	}
	#line3-1-8{
		position: absolute;
	    left: 143px;
	    top: 58px;
	    height: 253px;
	    transform: rotate(9deg);
	}*/	

 	/*
    $("#line2-"+i+"-0").css({ "position": "absolute",	    "left": left0_px+"px",	    "top": "341px",	    "height": "66px",	    "transform": "rotate(136deg)" });
 	#line2-1-0{
		position: absolute;
	    left: 144px;
	    top: 341px;
	    height: 66px;
	    transform: rotate(136deg);
	}
	#line2-2-0{
		position: absolute;
	    left: 184px;
	    top: 341px;
	    height: 66px;
	    transform: rotate(136deg);
	}
	#line2-1-1{
		position: absolute;
	    left: 144px;
	    top: 330px;
	    height: 46px;
	    transform: rotate(90deg);
	}
	#line2-2-1{
		position: absolute;
	    left: 184px;
	    top: 330px;
	    height: 46px;
	    transform: rotate(90deg);
	}
	#line2-1-2{
		position: absolute;
	    left: 145px;
	    top: 302px;
	    height: 58px;
	    transform: rotate(45deg);
	}	
	#line2-1-3{
		position: absolute;
	    left: 144px;
	    top: 264px;
	    height: 94px;
	    transform: rotate(27deg);
	}
	#line2-1-4{
		position: absolute;
	    left: 144px;
	    top: 225px;
	    height: 131px;
	    transform: rotate(17deg);
	}
	#line2-1-5{    
		position: absolute;
	    left: 144px;
	    top: 184px;
	    height: 170px;
	    transform: rotate(13deg);
	}
	#line2-1-6{
		position: absolute;
	    left: 143px;
	    top: 142px;
	    height: 212px;
	    transform: rotate(11deg);
	}
	#line2-1-7{
		position: absolute;
	    left: 143px;
	    top: 100px;
	    height: 253px;
	    transform: rotate(9deg);
	}
	#line2-1-8{
		position: absolute;
	    left: 143px;
	    top: 59px;
	    height: 294px;
	    transform: rotate(8deg);
	}*/
	/*$("#line-1-0").css({ "position": "absolute",	    "left": "144px",	    "top": "372px",	    "height": "46px",	    "transform": "rotate(90deg)" });*/
	/*#line-1-0{
		position: absolute;
	    left: 144px;
	    top: 372px;
	    height: 46px;
	    transform: rotate(90deg);
	}
	#line-2-0{
		position: absolute;
	    left: 174px;
	    top: 372px;
	    height: 46px;
	    transform: rotate(90deg);
	}
	#line-1-1{
		position: absolute;
	    left: 145px;
	    top: 344px;
	    height: 58px;
	    transform: rotate(45deg);
	}
	#line-2-1{
		position: absolute;
	    left: 185px;
	    top: 344px;
	    height: 58px;
	    transform: rotate(45deg);
	}
	#line-1-2{
		position: absolute;
	    left: 144px;
	    top: 306px;
	    height: 94px;
	    transform: rotate(27deg);
	}
	#line-2-2{
		position: absolute;
	    left: 184px;
	    top: 306px;
	    height: 94px;
	    transform: rotate(27deg);
	}
	#line-1-3{
		position: absolute;
	    left: 144px;
	    top: 267px;
	    height: 131px;
	    transform: rotate(17deg);
	}
	#line-2-3{
		position: absolute;
	    left: 184px;
	    top: 267px;
	    height: 131px;
	    transform: rotate(17deg);
	}
	#line-1-4{    
		position: absolute;
	    left: 144px;
	    top: 226px;
	    height: 170px;
	    transform: rotate(13deg);
	}
	#line-1-5{
		position: absolute;
	    left: 143px;
	    top: 184px;
	    height: 212px;
	    transform: rotate(11deg);
	}
	#line-1-6{
		position: absolute;
	    left: 143px;
	    top: 142px;
	    height: 253px;
	    transform: rotate(9deg);
	}
	#line-1-7{
		position: absolute;
	    left: 143px;
	    top: 101px;
	    height: 294px;
	    transform: rotate(8deg);
	}
	#line-1-8{
		position: absolute;
	    left: 143px;
	    top: 59px;
	    height: 337px;
	    transform: rotate(7deg);
	}*/
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
		height: 748px;
		position: relative;
		border-bottom: 2px solid #9DD5FD;
	}

	.dragWrapper {
		width: 500px;
		border: 1px solid red;
		height: 600px;
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
	//===================線的div tag=====================
	// var body = document.getElementsByTagName('body');
	// let HtmlStr = '';
	// for (i = 2; i < 9; i++){
	// 	for(j=1; j < 7; j++){
	// 		for(k=0; k<9 ; k++){
	// 	HtmlStr += '<div id="line'+i+'-'+j+'-'+k+'" ></div>';
	// 	// HtmlStr += '<div id="line'+i+'-'+j+'-'+k+'" '+'style="border: 2px solid black; display: block;"></div>';

	// 		}
	// 	}
	// }
	// document.body.innerHTML += HtmlStr;
	//====================================================

	let userData;
	let count = 0;
	let score=0;
	let ansA_cnt=0;
	let ansB_cnt=0;
	let ansC_cnt=0;
	let ansD_cnt=0;
	//點event
	//12/25,點
	let cnt1_1=0,cnt2_1=0, cnt3_1=0, cnt4_1=0, cnt5_1=0, cnt6_1=0, cnt7_1=0, cnt8_1=0, cnt9_1=0, cntAll_1=0;
	//12/26,點
	let cnt1_2=0,cnt2_2=0, cnt3_2=0, cnt4_2=0, cnt5_2=0, cnt6_2=0, cnt7_2=0, cnt8_2=0, cnt9_2=0, cntAll_2=0;
	//12/27,點
	let cnt1_3=0,cnt2_3=0, cnt3_3=0, cnt4_3=0, cnt5_3=0, cnt6_3=0, cnt7_3=0, cnt8_3=0, cnt9_3=0, cntAll_3=0;
	//12/28,點
	let cnt1_4=0,cnt2_4=0, cnt3_4=0, cnt4_4=0, cnt5_4=0, cnt6_4=0, cnt7_4=0, cnt8_4=0, cnt9_4=0, cntAll_4=0;
	//12/29,點
	let cnt1_5=0,cnt2_5=0, cnt3_5=0, cnt4_5=0, cnt5_5=0, cnt6_5=0, cnt7_5=0, cnt8_5=0, cnt9_5=0, cntAll_5=0;
	//12/30,點
	let cnt1_6=0,cnt2_6=0, cnt3_6=0, cnt4_6=0, cnt5_6=0, cnt6_6=0, cnt7_6=0, cnt8_6=0, cnt9_6=0, cntAll_6=0;
	//12/31,點
	let cnt1_7=0,cnt2_7=0, cnt3_7=0, cnt4_7=0, cnt5_7=0, cnt6_7=0, cnt7_7=0, cnt8_7=0, cnt9_7=0, cntAll_7=0;



	$("#dragObject").click(function (e) {

		//==========================================start-畫線(disable)=============================================
		let left0_px=145 , left1_px=145 , left2_px=145 , left3_px=145 , left4_px=145 , left5_px=145 , left6_px=145 , left7_px=145 , left8_px=145;
		for (i = 1; i < 8; i++) { 

			
    	$("#line1-"+i+"-0").css({ "position": "absolute", "left": left0_px+"px", "top": "372px", "height": "46px",	 "transform": "rotate(90deg)" , "border": "2px solid black"});
    	$("#line1-"+i+"-1").css({ "position": "absolute", "left": left1_px+"px", "top": "344px", "height": "58px",	 "transform": "rotate(45deg)" , "border": "2px solid black"});
    	$("#line1-"+i+"-2").css({ "position": "absolute", "left": left2_px+"px", "top": "306px", "height": "94px",	 "transform": "rotate(27deg)" , "border": "2px solid black"});
    	$("#line1-"+i+"-3").css({ "position": "absolute", "left": left3_px+"px", "top": "267px", "height": "131px",	 "transform": "rotate(17deg)" , "border": "2px solid black"});
    	$("#line1-"+i+"-4").css({ "position": "absolute", "left": left4_px+"px", "top": "226px", "height": "170px",	 "transform": "rotate(13deg)" , "border": "2px solid black"});
    	$("#line1-"+i+"-5").css({ "position": "absolute", "left": left5_px+"px", "top": "184px", "height": "212px",	 "transform": "rotate(11deg)" , "border": "2px solid black"});
    	$("#line1-"+i+"-6").css({ "position": "absolute", "left": left6_px+"px", "top": "142px", "height": "253px",	 "transform": "rotate(9deg)" , "border": "2px solid black"});
    	$("#line1-"+i+"-7").css({ "position": "absolute", "left": left7_px+"px", "top": "101px", "height": "294px",	 "transform": "rotate(8deg)" , "border": "2px solid black"});
    	$("#line1-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "59px",	 "height": "337px",	 "transform": "rotate(7deg)" , "border": "2px solid black"});

    	$("#line2-"+i+"-0").css({ "position": "absolute", "left": left0_px+"px", "top": "341px", "height": "66px",	 "transform": "rotate(136deg)" , "border": "2px solid black"});
 		$("#line2-"+i+"-1").css({ "position": "absolute", "left": left1_px+"px", "top": "330px", "height": "46px",	 "transform": "rotate(90deg)" , "border": "2px solid black"});
    	$("#line2-"+i+"-2").css({ "position": "absolute", "left": left2_px+"px", "top": "302px", "height": "58px",	 "transform": "rotate(45deg)" , "border": "2px solid black"});
    	$("#line2-"+i+"-3").css({ "position": "absolute", "left": left3_px+"px", "top": "264px", "height": "94px",	 "transform": "rotate(27deg)" , "border": "2px solid black"});
    	$("#line2-"+i+"-4").css({ "position": "absolute", "left": left4_px+"px", "top": "225px", "height": "131px",	 "transform": "rotate(17deg)" , "border": "2px solid black"});
    	$("#line2-"+i+"-5").css({ "position": "absolute", "left": left5_px+"px", "top": "184px", "height": "170px",	 "transform": "rotate(13deg)" , "border": "2px solid black"});
    	$("#line2-"+i+"-6").css({ "position": "absolute", "left": left6_px+"px", "top": "142px", "height": "212px",	 "transform": "rotate(11deg)" , "border": "2px solid black"});
    	$("#line2-"+i+"-7").css({ "position": "absolute", "left": left7_px+"px", "top": "100px", "height": "253px",	 "transform": "rotate(9deg)" , "border": "2px solid black"});
    	$("#line2-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "59px",	 "height": "294px",	 "transform": "rotate(8deg)" , "border": "2px solid black"});
    	/*$("#line2-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "59px", "height": "337px",	"transform": "rotate(7deg)" , "border": "2px solid black"});*/

    	$("#line3-"+i+"-0").css({ "position": "absolute", "left": left0_px+"px", "top": "303px", "height": "99px",	 "transform": "rotate(153deg)" , "border": "2px solid black"});
    	$("#line3-"+i+"-1").css({ "position": "absolute", "left": left1_px+"px", "top": "299px", "height": "66px",	 "transform": "rotate(136deg)" , "border": "2px solid black"});
 		$("#line3-"+i+"-2").css({ "position": "absolute", "left": left2_px+"px", "top": "288px", "height": "46px",	 "transform": "rotate(90deg)" , "border": "2px solid black"});
    	$("#line3-"+i+"-3").css({ "position": "absolute", "left": left3_px+"px", "top": "259px", "height": "58px",	 "transform": "rotate(45deg)" , "border": "2px solid black"});
    	$("#line3-"+i+"-4").css({ "position": "absolute", "left": left4_px+"px", "top": "222px", "height": "94px",	 "transform": "rotate(27deg)" , "border": "2px solid black"});
    	$("#line3-"+i+"-5").css({ "position": "absolute", "left": left5_px+"px", "top": "183px", "height": "131px",	 "transform": "rotate(17deg)" , "border": "2px solid black"});
    	$("#line3-"+i+"-6").css({ "position": "absolute", "left": left6_px+"px", "top": "142px", "height": "170px",	 "transform": "rotate(13deg)" , "border": "2px solid black"});
    	$("#line3-"+i+"-7").css({ "position": "absolute", "left": left7_px+"px", "top": "100px", "height": "212px",	 "transform": "rotate(11deg)" , "border": "2px solid black"});
    	$("#line3-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "58px",	 "height": "253px",	 "transform": "rotate(9deg)" , "border": "2px solid black"});
    	// $("#line3-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px",	 "top": "59px",	 "height": "294px",	 "transform": "rotate(8deg)" , "border": "2px solid black"});

    	$("#line4-"+i+"-0").css({ "position": "absolute", "left": left0_px+"px", "top": "264px", "height": "135px",	 "transform": "rotate(162deg)", "border": "2px solid black"});
		$("#line4-"+i+"-1").css({ "position": "absolute", "left": left1_px+"px", "top": "262px", "height": "99px",	 "transform": "rotate(153deg)", "border": "2px solid black" });
    	$("#line4-"+i+"-2").css({ "position": "absolute", "left": left2_px+"px", "top": "257px", "height": "66px",	 "transform": "rotate(136deg)", "border": "2px solid black" });
 		$("#line4-"+i+"-3").css({ "position": "absolute", "left": left3_px+"px", "top": "246px", "height": "46px",	 "transform": "rotate(90deg)", "border": "2px solid black" });
    	$("#line4-"+i+"-4").css({ "position": "absolute", "left": left4_px+"px", "top": "217px", "height": "58px",	 "transform": "rotate(45deg)", "border": "2px solid black" });
    	$("#line4-"+i+"-5").css({ "position": "absolute", "left": left5_px+"px", "top": "180px", "height": "94px",	 "transform": "rotate(27deg)", "border": "2px solid black" });
    	$("#line4-"+i+"-6").css({ "position": "absolute", "left": left6_px+"px", "top": "141px", "height": "131px",	 "transform": "rotate(17deg)", "border": "2px solid black" });
    	$("#line4-"+i+"-7").css({ "position": "absolute", "left": left7_px+"px", "top": "100px", "height": "170px",	 "transform": "rotate(13deg)", "border": "2px solid black" });
    	$("#line4-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "58px",	 "height": "212px",	 "transform": "rotate(11deg)", "border": "2px solid black" });
    	// $("#line4-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px",	"top": "58px", "height": "253px", "transform": "rotate(9deg)" , "border": "2px solid black" }); 

		$("#line5-"+i+"-0").css({ "position": "absolute", "left": left0_px+"px", "top": "221px", "height": "180px",	 "transform": "rotate(166deg)", "border": "2px solid black"});
		$("#line5-"+i+"-1").css({ "position": "absolute", "left": left1_px+"px", "top": "224px", "height": "135px",	 "transform": "rotate(162deg)", "border": "2px solid black"});
		$("#line5-"+i+"-2").css({ "position": "absolute", "left": left2_px+"px", "top": "220px", "height": "99px",	 "transform": "rotate(153deg)", "border": "2px solid black" });
    	$("#line5-"+i+"-3").css({ "position": "absolute", "left": left3_px+"px", "top": "215px", "height": "66px",	 "transform": "rotate(136deg)", "border": "2px solid black" });
 		$("#line5-"+i+"-4").css({ "position": "absolute", "left": left4_px+"px", "top": "202px", "height": "46px",	 "transform": "rotate(90deg)", "border": "2px solid black" });
    	$("#line5-"+i+"-5").css({ "position": "absolute", "left": left5_px+"px", "top": "175px", "height": "58px",	 "transform": "rotate(45deg)", "border": "2px solid black" });
    	$("#line5-"+i+"-6").css({ "position": "absolute", "left": left6_px+"px", "top": "138px", "height": "94px",	 "transform": "rotate(27deg)", "border": "2px solid black" });
    	$("#line5-"+i+"-7").css({ "position": "absolute", "left": left7_px+"px", "top": "99px", "height": "131px",	 "transform": "rotate(17deg)", "border": "2px solid black" });
    	$("#line5-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "58px", "height": "170px",	 "transform": "rotate(13deg)", "border": "2px solid black" });
    	// $("#line5-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "58px",	 "height": "212px",	 "transform": "rotate(11deg)", "border": "2px solid black" });

		$("#line6-"+i+"-0").css({ "position": "absolute", "left": left0_px+"px", "top": "181px", "height": "217px",	 "transform": "rotate(169deg)", "border": "2px solid black"});
		$("#line6-"+i+"-1").css({ "position": "absolute", "left": left1_px+"px", "top": "180px", "height": "180px",	 "transform": "rotate(166deg)", "border": "2px solid black"});
		$("#line6-"+i+"-2").css({ "position": "absolute", "left": left2_px+"px", "top": "182px", "height": "135px",	 "transform": "rotate(162deg)", "border": "2px solid black"});
		$("#line6-"+i+"-3").css({ "position": "absolute", "left": left3_px+"px", "top": "178px", "height": "99px",	 "transform": "rotate(153deg)", "border": "2px solid black" });
    	$("#line6-"+i+"-4").css({ "position": "absolute", "left": left4_px+"px", "top": "173px", "height": "66px",	 "transform": "rotate(136deg)", "border": "2px solid black" });
 		$("#line6-"+i+"-5").css({ "position": "absolute", "left": left5_px+"px", "top": "160px", "height": "46px",	 "transform": "rotate(90deg)", "border": "2px solid black" });
    	$("#line6-"+i+"-6").css({ "position": "absolute", "left": left6_px+"px", "top": "133px", "height": "58px",	 "transform": "rotate(45deg)", "border": "2px solid black" });
    	$("#line6-"+i+"-7").css({ "position": "absolute", "left": left7_px+"px", "top": "96px", "height": "94px",	 "transform": "rotate(27deg)", "border": "2px solid black" });
    	$("#line6-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "58px", "height": "131px",	 "transform": "rotate(17deg)", "border": "2px solid black" });
    	// $("#line6-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "58px", "height": "170px",	 "transform": "rotate(13deg)", "border": "2px solid black" }); 

		$("#line7-"+i+"-0").css({ "position": "absolute", "left": left0_px+"px", "top": "140px", "height": "258px",	 "transform": "rotate(171deg)", "border": "2px solid black"});
		$("#line7-"+i+"-1").css({ "position": "absolute", "left": left1_px+"px", "top": "141px", "height": "217px",	 "transform": "rotate(169deg)", "border": "2px solid black"});
		$("#line7-"+i+"-2").css({ "position": "absolute", "left": left2_px+"px", "top": "140px", "height": "180px",	 "transform": "rotate(166deg)", "border": "2px solid black"});
		$("#line7-"+i+"-3").css({ "position": "absolute", "left": left3_px+"px", "top": "142px", "height": "135px",	 "transform": "rotate(162deg)", "border": "2px solid black"});
		$("#line7-"+i+"-4").css({ "position": "absolute", "left": left4_px+"px", "top": "138px", "height": "99px",	 "transform": "rotate(153deg)", "border": "2px solid black" });
    	$("#line7-"+i+"-5").css({ "position": "absolute", "left": left5_px+"px", "top": "133px", "height": "66px",	 "transform": "rotate(136deg)", "border": "2px solid black" });
 		$("#line7-"+i+"-6").css({ "position": "absolute", "left": left6_px+"px", "top": "120px", "height": "46px",	 "transform": "rotate(90deg)", "border": "2px solid black" });
    	$("#line7-"+i+"-7").css({ "position": "absolute", "left": left7_px+"px", "top": "93px", "height": "58px",	 "transform": "rotate(45deg)", "border": "2px solid black" });
    	$("#line7-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "58px", "height": "94px",	 "transform": "rotate(27deg)", "border": "2px solid black" });
    	// $("#line7-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "58px", "height": "131px",	 "transform": "rotate(17deg)", "border": "2px solid black" });

    	$("#line8-"+i+"-0").css({ "position": "absolute", "left": left0_px+"px", "top": "98px", "height": "300px",	 "transform": "rotate(173deg)", "border": "2px solid black"});
		$("#line8-"+i+"-1").css({ "position": "absolute", "left": left1_px+"px", "top": "99px", "height": "258px",	 "transform": "rotate(171deg)", "border": "2px solid black"});
		$("#line8-"+i+"-2").css({ "position": "absolute", "left": left2_px+"px", "top": "98px", "height": "217px",	 "transform": "rotate(169deg)", "border": "2px solid black"});
		$("#line8-"+i+"-3").css({ "position": "absolute", "left": left3_px+"px", "top": "98px", "height": "180px",	 "transform": "rotate(166deg)", "border": "2px solid black"});
		$("#line8-"+i+"-4").css({ "position": "absolute", "left": left4_px+"px", "top": "100px", "height": "135px",	 "transform": "rotate(162deg)", "border": "2px solid black"});
		$("#line8-"+i+"-5").css({ "position": "absolute", "left": left5_px+"px", "top": "96px", "height": "99px",	 "transform": "rotate(153deg)", "border": "2px solid black" });
    	$("#line8-"+i+"-6").css({ "position": "absolute", "left": left6_px+"px", "top": "91px", "height": "66px",	 "transform": "rotate(136deg)", "border": "2px solid black" });
 		$("#line8-"+i+"-7").css({ "position": "absolute", "left": left7_px+"px", "top": "78px", "height": "46px",	 "transform": "rotate(90deg)", "border": "2px solid black" });
    	$("#line8-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "51px", "height": "58px",	 "transform": "rotate(45deg)", "border": "2px solid black" });
    	// $("#line8-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "58px", "height": "94px",	 "transform": "rotate(27deg)", "border": "2px solid black" });	

 		
    	$("#line9-"+i+"-0").css({ "position": "absolute", "left": left0_px+"px", "top": "56px", "height": "342px",	 "transform": "rotate(173deg)", "border": "2px solid black"});
    	$("#line9-"+i+"-1").css({ "position": "absolute", "left": left1_px+"px", "top": "56px", "height": "300px",	 "transform": "rotate(173deg)", "border": "2px solid black"});
		$("#line9-"+i+"-2").css({ "position": "absolute", "left": left2_px+"px", "top": "57px", "height": "258px",	 "transform": "rotate(171deg)", "border": "2px solid black"});
		$("#line9-"+i+"-3").css({ "position": "absolute", "left": left3_px+"px", "top": "56px", "height": "217px",	 "transform": "rotate(169deg)", "border": "2px solid black"});
		$("#line9-"+i+"-4").css({ "position": "absolute", "left": left4_px+"px", "top": "56px", "height": "180px",	 "transform": "rotate(166deg)", "border": "2px solid black"});
		$("#line9-"+i+"-5").css({ "position": "absolute", "left": left5_px+"px", "top": "58px", "height": "135px",	 "transform": "rotate(162deg)", "border": "2px solid black"});
		$("#line9-"+i+"-6").css({ "position": "absolute", "left": left6_px+"px", "top": "54px", "height": "99px",	 "transform": "rotate(153deg)", "border": "2px solid black" });
    	$("#line9-"+i+"-7").css({ "position": "absolute", "left": left7_px+"px", "top": "50px", "height": "66px",	 "transform": "rotate(136deg)", "border": "2px solid black" });
 		$("#line9-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "37px", "height": "46px",	 "transform": "rotate(90deg)", "border": "2px solid black" });
    	// $("#line9-"+i+"-8").css({ "position": "absolute", "left": left8_px+"px", "top": "51px", "height": "58px",	 "transform": "rotate(45deg)", "border": "2px solid black" });

	    	left0_px = left0_px +40+1.5 ;
	    	left1_px = left1_px +40+1.5 ;
	    	left2_px = left2_px +40+1.5 ;
	    	left3_px = left3_px +40+1.5 ;
	    	left4_px = left4_px +40+1.5 ;
	    	left5_px = left5_px +40+1.5 ;
	    	left6_px = left6_px +40+1.5 ;
	    	left7_px = left7_px +40+1.5 ;
	    	left8_px = left8_px +40+1.5 ;
		}
		//=======================================================end-畫線(disable)========================================================================================
	
		//==============================================================start-畫點========================================================================================
		var parentOffset = $(this).parent().offset();
        // console.log(parentOffset);
        var relX = e.pageX - parentOffset.left ;
        var relY = e.pageY - parentOffset.top;
        //  var relX2 = e.pageX ;//- parentOffset.left;// - 5;
        // var relY2 = e.pageY;// - parentOffset.top;


        console.log(relX, relY);
        // console.log(relX2, relY2);


            sx = e.pageX + document.documentElement.scrollTop;
            sy = e.pageY + document.documentElement.scrollLeft;
            let zIndex ="z-index:99; ";
            // console.log(sx, sy);  
            // console.log(document.documentElement.scrollTop, document.documentElement.scrollLeft);  
            let pCount;
			let cnt = cntAll_1+cntAll_2+cntAll_3+cntAll_4+cntAll_5+cntAll_6+cntAll_7;
			console.log(cnt);

            //12/25
            if(cntAll_1 == 0)
            {
        		console.log(relX, relY);
	            if (relX >= 118 && relX <= 128) {
	            // if (sx >= 510 && sx <= 517) {
	                if (relY >= 390 && relY <= 398) {
	                // if (sy >= 648 && sy <= 656) {
	                	// console.log("test");
	            var sx = sx-5;	
	             var sy = sy-5;
	            if(cnt1_1 == 0){
	            // console.log(relX, relY);
	            relY = relY - 5;
	            relX = relX - 5;
	            // $("body").append("<p id='step11' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;"  + zIndex +  " top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step11' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:387px;left:116.5px;'></p>");
	            // $("#dragObject").append("<p id='step11' style='background-color: red; width:15px; height:15px; border-radius:10px; position:absolute;"  + zIndex +  " top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt1_1=1; 
	            cntAll_1 += 1;
	            // line1();
		        		}
		    		}
				}
	            if (relX >= 118 && relX <= 128) {
	            // if (sx >= 510 && sx <= 517) {
	                if (relY >= 349 && relY <= 355) {
	                // if (sy >= 608 && sy <= 615) {
	                    // if (sy >= 610 && sy <= 615) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt2_1 == 0){
	            // $("body").append("<p id='step12' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step12' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:347px;left:116.5px;'></p>");
	            // $("#dragObject").append("<p id='step12' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt2_1=1; 
	            cntAll_1 += 1;
	            // line1();
		        		}
		    		}
				}
	            if (relX >= 118 && relX <= 128) {
				// if (sx >= 510 && sx <= 517) {
	                if (relY >= 306 && relY <= 313) {
	                // if (sy >= 569 && sy <= 573) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt3_1 == 0){
	            // $("body").append("<p id='step13' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step13' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:303px;left:117.5px;'></p>");
	            // $("#dragObject").append("<p id='step13' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt3_1=1; 
	            cntAll_1 += 1;
	            // line1();
		        		}
		    		}
				}
	            if (relX >= 118 && relX <= 128) {
				// if (sx >= 510 && sx <= 517) {
	                if (relY >= 264 && relY <= 272) {
	                // if (sy >= 527 && sy <= 532) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt4_1 == 0){
	            // $("body").append("<p id='step14' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step14' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:263px;left:117.5px;'></p>");
	            // $("#dragObject").append("<p id='step14' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt4_1=1; 
	            cntAll_1 += 1;
	            // line1();
		        		}
		    		}
				}
	            if (relX >= 118 && relX <= 128) {
				// if (sx >= 510 && sx <= 517) {
	                if (relY >= 222 && relY <= 230) {
	                // if (sy >= 485 && sy <= 490) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt5_1 == 0){
	            // $("body").append("<p id='step15' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step15' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:220px;left:117.5px;'></p>");
	            // $("#dragObject").append("<p id='step15' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt5_1=1; 
	            cntAll_1 += 1;
	            // line1();
		        		}
		    		}
				}
	            if (relX >= 118 && relX <= 128) {
				// if (sx >= 510 && sx <= 517) {
	                if (relY >= 180 && relY <= 187) {
	                // if (sy >= 443 && sy <= 448) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt6_1 == 0){
	            // $("body").append("<p id='step16' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step16' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:179px;left:117.5px;'></p>");
	            // $("#dragObject").append("<p id='step16' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt6_1=1; 
	            cntAll_1 += 1;
	            // line1();
		        		}
		    		}
				}
	            if (relX >= 118 && relX <= 128) {
				// if (sx >= 510 && sx <= 517) {
	                if (relY >= 139 && relY <= 146) {
	                // if (sy >= 400 && sy <= 407) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt7_1 == 0){
	            // $("body").append("<p id='step17' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step17' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:136px;left:116.5px;'></p>");
	            // $("#dragObject").append("<p id='step17' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt7_1=1; 
	            cntAll_1 += 1;
	            // line1();
		        		}
		    		}
				}
	            if (relX >= 118 && relX <= 128) {
				// if (sx >= 510 && sx <= 517) {
	                if (relY >= 97 && relY <= 106) {
	                // if (sy >= 359 && sy <= 365) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt8_1 == 0){
	            // $("body").append("<p id='step18' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step18' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:95px;left:117.5px;'></p>");
	            // $("#dragObject").append("<p id='step18' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt8_1=1; 
	            cntAll_1 += 1;
	            // line1();
		        		}
		    		}
				}
	            if (relX >= 118 && relX <= 128) {
				// if (sx >= 510 && sx <= 517) {
	                if (relY >= 55 && relY <= 62) {
	                // if (sy >= 316 && sy <= 322) {
	            var sx = sx-5;
	             var sy = sy-5;;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt9_1 == 0){
	            // $("body").append("<p id='step19' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step19' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:54px;left:116.5px;'></p>");
	            // $("#dragObject").append("<p id='step19' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt9_1=1; 
	            cntAll_1 += 1;
	            // line1();
		        		}
		    		}
				}
			}

				// if (relX >= 118 && relX <= 128) {
	   //          // if (sx >= 510 && sx <= 517) {

	   //              if (relY >= 390 && relY <= 398) {
	   //              // if (sy >= 648 && sy <= 656) {
	   //              	// console.log("test");
	   //          var sx = sx-5;	
	   //           var sy = sy-5;
	   //          if(cnt1_1 == 1){
	   //          // console.log(relX, relY);
	   //          relY = relY - 5;
	   //          relX = relX - 5;
	   //          // $("#dragObject").append("<p id='step11' style='background-color: green; width:15px; height:15px; border-radius:10px; position:absolute;"  + zIndex +  " top:" + relY + "px;left:" + relX + "px;'></p>");
	   //          $("#dragObject").append("<p id='step11-1' class='pGreen' style='background-color: green; width:15px; height:15px; border-radius:10px; position:absolute;top:387px;left:116.5px;'></p>");
	   //          cntAll_G_1=1;
	   //          // $("p").click(function() {
	   //          // 	let stepValue = $(this).attr("id");
	   //          // alert(stepValue);

	   //          // })
	   //          var bgColor = $("#step11-1").css('background-color');
	   //          alert(bgColor);
	   //          // cnt8_1=1; 
	   //          // cntAll_1 += 1;
	   //          // line1();
		  //       		}
		  //   		}
				// }

			//12/26

            if(cntAll_2 == 0)
            {
        		console.log(relX, relY);
	            if (relX >= 161 && relX <= 169) {
	            // if (sx >= 552 && sx <= 558) {
	                if (relY >= 389 && relY <= 397) {
	                // if (sy >= 651 && sy <= 657) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt1_2 == 0){
	            // $("body").append("<p id='step21' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step21' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:388px;left:159.5px;'></p>");
	            // $("#dragObject").append("<p id='step21' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt1_2=1; 
	            cntAll_2 += 1;
	            // line2();
		        		}
		    		}
				} 
	            if (relX >= 161 && relX <= 169) {
	            // if (sx >= 552 && sx <= 558) {
	                if (relY >= 349 && relY <= 354) {
	                // if (sy >= 608 && sy <= 615) {
	                    // if (sy >= 610 && sy <= 615) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt2_2 == 0){
	            // $("body").append("<p id='step22' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step22' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:346px;left:160.5px;'></p>");
	            // $("#dragObject").append("<p id='step22' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt2_2=1; 
	            cntAll_2 += 1;
	            // line2();
		        		}
		    		}
				}
	            if (relX >= 161 && relX <= 169) {
	            // if (sx >= 552 && sx <= 558) {
	                if (relY >= 307 && relY <= 314) {
	                // if (sy >= 569 && sy <= 573) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt3_2 == 0){
	            // $("body").append("<p id='step23' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step23' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:305px;left:160.5px;'></p>");
	            // $("#dragObject").append("<p id='step23' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt3_2=1; 
	            cntAll_2 += 1;
	            // line2();
		        		}
		    		}
				}
	            if (relX >= 161 && relX <= 169) {
	            // if (sx >= 552 && sx <= 558) {
	                if (relY >= 266 && relY <= 270) {
	                // if (sy >= 527 && sy <= 532) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt4_2 == 0){
	            // $("body").append("<p id='step24' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step24' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:263px;left:160.5px;'></p>");
	            // $("#dragObject").append("<p id='step24' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt4_2=1; 
	            cntAll_2 += 1;
	            // line2();
		        		}
		    		}
				}
	            if (relX >= 161 && relX <= 169) {
	            // if (sx >= 552 && sx <= 558) {
	                if (relY >= 225 && relY <= 230) {
	                // if (sy >= 485 && sy <= 490) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt5_2 == 0){
	            // $("body").append("<p id='step25' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step25' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:222px;left:159.5px;'></p>");
	            // $("#dragObject").append("<p id='step25' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt5_2=1; 
	            cntAll_2 += 1;
	            // line2();
		        		}
		    		}
				}
	            if (relX >= 161 && relX <= 169) {
	            // if (sx >= 552 && sx <= 558) {
	                if (relY >= 180 && relY <= 188) {
	                // if (sy >= 443 && sy <= 448) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt6_2 == 0){
	            // $("body").append("<p id='step26' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step26' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:179px;left:159.5px;'></p>");
	            // $("#dragObject").append("<p id='step26' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt6_2=1; 
	            cntAll_2 += 1;
	            // line2();
		        		}
		    		}
				}
	            if (relX >= 161 && relX <= 169) {
	            // if (sx >= 552 && sx <= 558) {
	                if (relY >= 138 && relY <= 144) {
	                // if (sy >= 400 && sy <= 407) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt7_2 == 0){
	            // $("body").append("<p id='step27' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step27' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:138px;left:160.5px;'></p>");
	            // $("#dragObject").append("<p id='step27' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt7_2=1; 
	            cntAll_2 += 1;
	            // line2();
		        		}
		    		}
				}
	            if (relX >= 161 && relX <= 169) {
	            // if (sx >= 552 && sx <= 558) {
	                if (relY >= 97 && relY <= 104) {
	                // if (sy >= 359 && sy <= 365) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt8_2 == 0){
	            // $("body").append("<p id='step28' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step28' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:96px;left:159.5px;'></p>");
	            // $("#dragObject").append("<p id='step28' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt8_2=1; 
	            cntAll_2 += 1;
	            // line2();
		        		}
		    		}
				}
	            if (relX >= 161 && relX <= 169) {
	            // if (sx >= 552 && sx <= 558) {
	                if (relY >= 54 && relY <= 63) {
	                // if (sy >= 316 && sy <= 322) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt9_2 == 0){
	            // $("body").append("<p id='step29' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step29' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:54px;left:161.5px;'></p>");
	            // $("#dragObject").append("<p id='step29' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt9_2=1; 
	            cntAll_2 += 1;
	            // line2();
		        		}
		    		}
				}
			}
			//12/27

            if(cntAll_3 == 0)
            {
	            if (relX >= 203 && relX <= 212) {
	              // if (sx >= 593 && sx <= 602) {
	                if (relY >= 391 && relY <= 397) {
	                    // if (sy >= 651 && sy <= 657) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt1_3 == 0){
	            // $("body").append("<p id='step31' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step31' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:386px;left:203.5px;'></p>");
	            // $("#dragObject").append("<p id='step31' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt1_3=1; 
	            cntAll_3 += 1;
	            // line3();
		        		}
		    		}
				}  
	            if (relX >= 203 && relX <= 212) {
	            // if (sx >= 593 && sx <= 602) {
	                if (relY >= 347 && relY <= 355) {
	                    // if (sy >= 608 && sy <= 615) {
	                    // if (sy >= 610 && sy <= 615) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt2_3 == 0){
	            // $("body").append("<p id='step32' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step32' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:347px;left:201.5px;'></p>");
	            // $("#dragObject").append("<p id='step32' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt2_3=1; 
	            cntAll_3 += 1;
	            // line3();
		        		}
		    		}
				}  
	            if (relX >= 203 && relX <= 212) {
	            // if (sx >= 593 && sx <= 602) {
	                if (relY >= 306 && relY <= 313) {
	                    // if (sy >= 569 && sy <= 573) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt3_3 == 0){
	            // $("body").append("<p id='step33' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step33' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:304px;left:201.5px;'></p>");
	            // $("#dragObject").append("<p id='step33' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt3_3=1; 
	            cntAll_3 += 1;
	            // line3();
		        		}
		    		}
				}  
	            if (relX >= 203 && relX <= 212) {
	            // if (sx >= 593 && sx <= 602) {
	                if (relY >= 263 && relY <= 270) {
	                    // if (sy >= 527 && sy <= 532) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt4_3 == 0){
	            // $("body").append("<p id='step34' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step34' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:262px;left:203.5px;'></p>");
	            // $("#dragObject").append("<p id='step34' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt4_3=1; 
	            cntAll_3 += 1;
	            // line3();
		        		}
		    		}
				}  
	            if (relX >= 203 && relX <= 212) {
	            // if (sx >= 593 && sx <= 602) {
	                if (relY >= 223 && relY <= 230) {
	                    // if (sy >= 485 && sy <= 490) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt5_3 == 0){
	            // $("body").append("<p id='step35' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step35' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:221px;left:201.5px;'></p>");
	            // $("#dragObject").append("<p id='step35' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt5_3=1; 
	            cntAll_3 += 1;
	            // line3();
		        		}
		    		}
				}  
	            if (relX >= 203 && relX <= 212) {
	            // if (sx >= 593 && sx <= 602) {
	                if (relY >= 180 && relY <= 187) {
	                    // if (sy >= 443 && sy <= 448) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt6_3 == 0){
	            // $("body").append("<p id='step36' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step36' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:179px;left:201.5px;'></p>");
	            // $("#dragObject").append("<p id='step36' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt6_3=1; 
	            cntAll_3 += 1;
	            // line3();
		        		}
		    		}
				}  
	            if (relX >= 203 && relX <= 212) {
	            // if (sx >= 593 && sx <= 602) {
	                if (relY >= 139 && relY <= 146) {
	                    // if (sy >= 400 && sy <= 407) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt7_3 == 0){
	            // $("body").append("<p id='step37' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step37' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:139px;left:202.5px;'></p>");
	            // $("#dragObject").append("<p id='step37' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt7_3=1; 
	            cntAll_3 += 1;
	            // line3();
		        		}
		    		}
				}  
	            if (relX >= 203 && relX <= 212) {
	            // if (sx >= 593 && sx <= 602) {
	                if (relY >= 96 && relY <= 103) {
	                    // if (sy >= 359 && sy <= 365) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt8_3 == 0){
	            // $("body").append("<p id='step38' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step38' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:93px;left:201.5px;'></p>");
	            // $("#dragObject").append("<p id='step38' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt8_3=1; 
	            cntAll_3 += 1;
	            // line3();
		        		}
		    		}
				}  
	            if (relX >= 203 && relX <= 212) {
	            // if (sx >= 593 && sx <= 602) {
	                if (relY >= 54 && relY <= 61) {
	                    // if (sy >= 316 && sy <= 322) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt9_3 == 0){
	            // $("body").append("<p id='step39' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step39' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:54px;left:201.5px;'></p>");
	            // $("#dragObject").append("<p id='step39' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt9_3=1; 
	            cntAll_3 += 1;
	            // line3();
		        		}
		    		}
				}
			}

			//12/28

            if(cntAll_4 == 0)
            {
	            if (relX >= 245 && relX <= 254) {
	              // if (sx >= 636 && sx <= 643) {
	                if (relY >= 389 && relY <= 396) {
	                    // if (sy >= 651 && sy <= 657) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt1_4 == 0){
	            // $("body").append("<p id='step41' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step41' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:387px;left:244.5px;'></p>");
	            // $("#dragObject").append("<p id='step41' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt1_4=1; 
	            cntAll_4 += 1;
	            // line4();
		        		}
		    		}
				}   
	            if (relX >= 245 && relX <= 254) {
	            // if (sx >= 636 && sx <= 643) {
	                if (relY >= 349 && relY <= 356) {
	                    // if (sy >= 608 && sy <= 615) {
	                    // if (sy >= 610 && sy <= 615) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt2_4 == 0){
	            // $("body").append("<p id='step42' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step42' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:347px;left:244.5px;'></p>");
	            // $("#dragObject").append("<p id='step42' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt2_4=1; 
	            cntAll_4 += 1;
	            // line4();
		        		}
		    		}
				}   
	            if (relX >= 245 && relX <= 254) {
	            // if (sx >= 636 && sx <= 643) {
	                if (relY >= 306 && relY <= 312) {
	                    // if (sy >= 569 && sy <= 573) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt3_4 == 0){
	            // $("body").append("<p id='step43' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step43' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:304px;left:244.5px;'></p>");
	            // $("#dragObject").append("<p id='step43' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt3_4=1; 
	            cntAll_4 += 1;
	            // line4();
		        		}
		    		}
				}   
	            if (relX >= 245 && relX <= 254) {
	            // if (sx >= 636 && sx <= 643) {
	                if (relY >= 265 && relY <= 272) {
	                    // if (sy >= 527 && sy <= 532) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt4_4 == 0){
	            // $("body").append("<p id='step44' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step44' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:263px;left:244.5px;'></p>");
	            // $("#dragObject").append("<p id='step44' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt4_4=1; 
	            cntAll_4 += 1;
	            // line4();
		        		}
		    		}
				}   
	            if (relX >= 245 && relX <= 254) {
	            // if (sx >= 636 && sx <= 643) {
	                if (relY >= 222 && relY <= 229) {
	                    // if (sy >= 485 && sy <= 490) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt5_4 == 0){
	            // $("body").append("<p id='step45' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step45' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:222px;left:244.5px;'></p>");
	            // $("#dragObject").append("<p id='step45' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt5_4=1; 
	            cntAll_4 += 1;
	            // line4();
		        		}
		    		}
				}   
	            if (relX >= 245 && relX <= 254) {
	            // if (sx >= 636 && sx <= 643) {
	                if (relY >= 179 && relY <= 186) {
	                    // if (sy >= 443 && sy <= 448) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt6_4 == 0){
	            // $("body").append("<p id='step46' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step46' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:178px;left:244.5px;'></p>");
	            // $("#dragObject").append("<p id='step46' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt6_4=1; 
	            cntAll_4 += 1;
	            // line4();
		        		}
		    		}
				}   
	            if (relX >= 245 && relX <= 254) {
	            // if (sx >= 636 && sx <= 643) {
	                if (relY >= 138 && relY <= 145) {
	                    // if (sy >= 400 && sy <= 407) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt7_4 == 0){
	            // $("body").append("<p id='step47' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step47' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:137px;left:243.5px;'></p>");
	            // $("#dragObject").append("<p id='step47' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt7_4=1; 
	            cntAll_4 += 1;
	            // line4();
		        		}
		    		}
				}   
	            if (relX >= 245 && relX <= 254) {
	            // if (sx >= 636 && sx <= 643) {
	                if (relY >= 97 && relY <= 104) {
	                    // if (sy >= 359 && sy <= 365) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt8_4 == 0){
	            // $("body").append("<p id='step48' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step48' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:96px;left:244.5px;'></p>");
	            // $("#dragObject").append("<p id='step48' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt8_4=1; 
	            cntAll_4 += 1;
	            // line4();
		        		}
		    		}
				}   
	            if (relX >= 245 && relX <= 254) {
	            // if (sx >= 636 && sx <= 643) {
	                if (relY >= 55 && relY <= 63) {
	                    // if (sy >= 316 && sy <= 322) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt9_4 == 0){
	            // $("body").append("<p id='step49' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step49' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:54px;left:244.5px;'></p>");
	            // $("#dragObject").append("<p id='step49' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt9_4=1; 
	            cntAll_4 += 1;
	            // line4();
		        		}
		    		}
				}
			}

			//12/29

            if(cntAll_5 == 0)
            {
	            if (relX >= 288 && relX <= 295) {
	              // if (sx >= 678 && sx <= 686) {
	                if (relY >= 391 && relY <= 397) {
	                    // if (sy >= 651 && sy <= 657) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt1_5 == 0){
	            // $("body").append("<p id='step51' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step51' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:389px;left:285.5px;'></p>");
	            // $("#dragObject").append("<p id='step51' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt_5=1; 
	            cntAll_5 += 1;
	            // line5();
		        		}
		    		}
				}   
	            if (relX >= 288 && relX <= 295) {
	            // if (sx >= 678 && sx <= 686) {
	                if (relY >= 350 && relY <= 355) {
	                    // if (sy >= 608 && sy <= 615) {
	                    // if (sy >= 610 && sy <= 615) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt2_5 == 0){
	            // $("body").append("<p id='step52' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step52' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:346px;left:285.5px;'></p>");
	            // $("#dragObject").append("<p id='step52' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt2_5=1; 
	            cntAll_5 += 1;
	            // line5();
		        		}
		    		}
				}   
	            if (relX >= 288 && relX <= 295) {
	            // if (sx >= 678 && sx <= 686) {
	                if (relY >= 306 && relY <= 313) {
	                    // if (sy >= 569 && sy <= 573) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt3_5 == 0){
	            // $("body").append("<p id='step53' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step53' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:305px;left:283.5px;'></p>");
	            // $("#dragObject").append("<p id='step53' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt3_5=1; 
	            cntAll_5 += 1;
	            // line5();
		        		}
		    		}
				}   
	            if (relX >= 288 && relX <= 295) {
	            // if (sx >= 678 && sx <= 686) {
	                if (relY >= 264 && relY <= 271) {
	                    // if (sy >= 527 && sy <= 532) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt4_5 == 0){
	            // $("body").append("<p id='step54' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step54' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:263px;left:284.5px;'></p>");
	            // $("#dragObject").append("<p id='step54' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt4_5=1; 
	            cntAll_5 += 1;
	            // line5();
		        		}
		    		}
				}   
	            if (relX >= 288 && relX <= 295) {
	            // if (sx >= 678 && sx <= 686) {
	                if (relY >= 223 && relY <= 229) {
	                    // if (sy >= 485 && sy <= 490) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt5_5 == 0){
	            // $("body").append("<p id='step55' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step55' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:221px;left:285.5px;'></p>");
	            // $("#dragObject").append("<p id='step55' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt5_5=1; 
	            cntAll_5 += 1;
	            // line5();
		        		}
		    		}
				}   
	            if (relX >= 288 && relX <= 295) {
	            // if (sx >= 678 && sx <= 686) {
	                if (relY >= 181 && relY <= 187) {
	                    // if (sy >= 443 && sy <= 448) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt6_5 == 0){
	            // $("body").append("<p id='step56' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step56' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:177px;left:285.5px;'></p>");
	            // $("#dragObject").append("<p id='step56' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt6_5=1; 
	            cntAll_5 += 1;
	            // line5();
		        		}
		    		}
				}   
	            if (relX >= 288 && relX <= 295) {
	            // if (sx >= 678 && sx <= 686) {
	                if (relY >= 139 && relY <= 146) {
	                    // if (sy >= 400 && sy <= 407) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt7_5 == 0){
	            // $("body").append("<p id='step57' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step57' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:138px;left:286.5px;'></p>");
	            // $("#dragObject").append("<p id='step57' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt7_5=1; 
	            cntAll_5 += 1;
	            // line5();
		        		}
		    		}
				}   
	            if (relX >= 288 && relX <= 295) {
	            // if (sx >= 678 && sx <= 686) {
	                if (relY >= 97 && relY <= 104) {
	                    // if (sy >= 359 && sy <= 365) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt8_5 == 0){
	            // $("body").append("<p id='step58' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step58' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:95px;left:285.5px;'></p>");
	            // $("#dragObject").append("<p id='step58' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt8_5=1; 
	            cntAll_5 += 1;
	            // line5();
		        		}
		    		}
				}   
	            if (relX >= 288 && relX <= 295) {
	            // if (sx >= 678 && sx <= 686) {
	                if (relY >= 55 && relY <= 62) {
	                    // if (sy >= 316 && sy <= 322) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt9_5 == 0){
	            // $("body").append("<p id='step59' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step59' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:52px;left:285.5px;'></p>");
	            // $("#dragObject").append("<p id='step59' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt9_5=1; 
	            cntAll_5 += 1;
	            // line5();
		        		}
		    		}
				}
			}

			//12/30

            if(cntAll_6 == 0)
            {
	            if (relX >= 330 && relX <= 337) {
	              // if (sx >= 721 && sx <= 726) {
	                if (relY >= 390 && relY <= 396) {
	                    // if (sy >= 651 && sy <= 657) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt1_6 == 0){
	            // $("body").append("<p id='step61' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step61' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:388px;left:327.5px;'></p>");
	            // $("#dragObject").append("<p id='step61' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt1_6=1; 
	            cntAll_6 += 1;
	            // line6();
		        		}
		    		}
				}   
	            if (relX >= 330 && relX <= 337) {
	            // if (sx >= 721 && sx <= 726) {
	                if (relY >= 347 && relY <= 354) {
	                    // if (sy >= 608 && sy <= 615) {
	                    // if (sy >= 610 && sy <= 615) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt2_6 == 0){
	            // $("body").append("<p id='step62' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step62' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:347px;left:327.5px;'></p>");
	            // $("#dragObject").append("<p id='step62' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt2_6=1; 
	            cntAll_6 += 1;
	            // line6();
		        		}
		    		}
				}   
	            if (relX >= 330 && relX <= 337) {
	            // if (sx >= 721 && sx <= 726) {
	                if (relY >= 307 && relY <= 313) {
	                    // if (sy >= 569 && sy <= 573) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt3_6 == 0){
	            // $("body").append("<p id='step63' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step63' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:304px;left:326.5px;'></p>");
	            // $("#dragObject").append("<p id='step63' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt3_6=1; 
	            cntAll_6 += 1;
	            // line6();
		        		}
		    		}
				}   
	            if (relX >= 330 && relX <= 337) {
	            // if (sx >= 721 && sx <= 726) {
	                if (relY >= 264 && relY <= 271) {
	                    // if (sy >= 527 && sy <= 532) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt4_6 == 0){
	            // $("body").append("<p id='step64' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step64' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:264px;left:327.5px;'></p>");
	            // $("#dragObject").append("<p id='step64' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt4_6=1; 
	            cntAll_6 += 1;
	            // line6();
		        		}
		    		}
				}   
	            if (relX >= 330 && relX <= 337) {
	            // if (sx >= 721 && sx <= 726) {
	                if (relY >= 224 && relY <= 231) {
	                    // if (sy >= 485 && sy <= 490) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt5_6 == 0){
	            // $("body").append("<p id='step65' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step65' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:222px;left:328.5px;'></p>");
	            // $("#dragObject").append("<p id='step65' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt5_6=1; 
	            cntAll_6 += 1;
	            // line6();
		        		}
		    		}
				}   
	            if (relX >= 330 && relX <= 337) {
	            // if (sx >= 721 && sx <= 726) {
	                if (relY >= 181 && relY <= 188) {
	                    // if (sy >= 443 && sy <= 448) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt6_6 == 0){
	            // $("body").append("<p id='step66' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step66' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:179px;left:327.5px;'></p>");
	            // $("#dragObject").append("<p id='step66' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt6_6=1; 
	            cntAll_6 += 1;
	            // line6();
		        		}
		    		}
				}   
	            if (relX >= 330 && relX <= 337) {
	            // if (sx >= 721 && sx <= 726) {
	                if (relY >= 140 && relY <= 147) {
	                    // if (sy >= 400 && sy <= 407) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt7_6 == 0){
	            // $("body").append("<p id='step67' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step67' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:137px;left:328.5px;'></p>");
	            // $("#dragObject").append("<p id='step67' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt7_6=1; 
	            cntAll_6 += 1;
	            // line6();
		        		}
		    		}
				}   
	            if (relX >= 330 && relX <= 337) {
	            // if (sx >= 721 && sx <= 726) {
	                if (relY >= 97 && relY <= 104) {
	                    // if (sy >= 359 && sy <= 365) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt8_6 == 0){
	            // $("body").append("<p id='step68' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step68' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:94px;left:326.5px;'></p>");
	            // $("#dragObject").append("<p id='step68' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt8_6=1; 
	            cntAll_6 += 1;
	            // line6();
		        		}
		    		}
				}   
	            if (relX >= 330 && relX <= 337) {
	            // if (sx >= 721 && sx <= 726) {
	                if (relY >= 56 && relY <= 62) {
	                    // if (sy >= 316 && sy <= 322) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt9_6 == 0){
	            // $("body").append("<p id='step69' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step69' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:52px;left:327.5px;'></p>");
	            // $("#dragObject").append("<p id='step69' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt9_6=1; 
	            cntAll_6 += 1;
	            // line6();
		        		}
		    		}
				}
			}

			//12/31

            if(cntAll_7 == 0)
            {
	            if (relX >= 372 && relX <= 378) {
	              // if (sx >= 763 && sx <= 768) {
	                if (relY >= 391 && relY <= 397) {
	                    // if (sy >= 651 && sy <= 657) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt1_7 == 0){
	            // $("body").append("<p id='step71' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step71' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:389px;left:368.5px;'></p>");
	            // $("#dragObject").append("<p id='step71' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt1_7=1; 
	            cntAll_7 += 1;
	            // line7();
		        		}
		    		}
				}   
	            if (relX >= 372 && relX <= 378) {
	            // if (sx >= 763 && sx <= 768) {
	                if (relY >= 349 && relY <= 355) {
	                    // if (sy >= 608 && sy <= 615) {
	                    // if (sy >= 610 && sy <= 615) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt2_7 == 0){
	            // $("body").append("<p id='step72' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step72' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:345px;left:369.5px;'></p>");
	            // $("#dragObject").append("<p id='step72' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt2_7=1; 
	            cntAll_7 += 1;
	            // line7();
		        		}
		    		}
				}   
	            if (relX >= 372 && relX <= 378) {
	            // if (sx >= 763 && sx <= 768) {
	                if (relY >= 306 && relY <= 314) {
	                    // if (sy >= 569 && sy <= 573) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt3_7 == 0){
	            // $("body").append("<p id='step73' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step73' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:303px;left:369.5px;'></p>");
	            // $("#dragObject").append("<p id='step73' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt3_7=1; 
	            cntAll_7 += 1;
	            // line7();
		        		}
		    		}
				}   
	            if (relX >= 372 && relX <= 378) {
	            // if (sx >= 763 && sx <= 768) {
	                if (relY >= 266 && relY <= 273) {
	                    // if (sy >= 527 && sy <= 532) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt4_7 == 0){
	            // $("body").append("<p id='step74' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step74' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:263px;left:369.5px;'></p>");
	            // $("#dragObject").append("<p id='step74' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt4_7=1; 
	            cntAll_7 += 1;
	            // line7();
		        		}
		    		}
				}   
	            if (relX >= 372 && relX <= 378) {
	            // if (sx >= 763 && sx <= 768) {
	                if (relY >= 223 && relY <= 230) {
	                    // if (sy >= 485 && sy <= 490) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt5_7 == 0){
	            // $("body").append("<p id='step75' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step75' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:222px;left:369.5px;'></p>");
	            // $("#dragObject").append("<p id='step75' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt5_7=1; 
	            cntAll_7 += 1;
	            // line7();
		        		}
		    		}
				}   
	            if (relX >= 372 && relX <= 378) {
	            // if (sx >= 763 && sx <= 768) {
	                if (relY >= 181 && relY <= 188) {
	                    // if (sy >= 443 && sy <= 448) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt6_7 == 0){
	            // $("body").append("<p id='step76' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step76' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:180px;left:368.5px;'></p>");
	            // $("#dragObject").append("<p id='step76' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt6_7=1; 
	            cntAll_7 += 1;
	            // line7();
		        		}
		    		}
				}   
	            if (relX >= 372 && relX <= 378) {
	            // if (sx >= 763 && sx <= 768) {
	                if (relY >= 139 && relY <= 145) {
	                    // if (sy >= 400 && sy <= 407) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt7_7 == 0){
	            // $("body").append("<p id='step77' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step77' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:136px;left:369.5px;'></p>");
	            // $("#dragObject").append("<p id='step77' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt7_7=1; 
	            cntAll_7 += 1;
	            // line7();
		        		}
		    		}
				}   
	            if (relX >= 372 && relX <= 378) {
	            // if (sx >= 763 && sx <= 768) {
	                if (relY >= 97 && relY <= 103) {
	                    // if (sy >= 359 && sy <= 365) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt8_7 == 0){
	            // $("body").append("<p id='step78' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step78' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;top:95px;left:369.5px;'></p>");
	            // $("#dragObject").append("<p id='step78' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute;" + zIndex + "  top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt8_7=1; 
	            cntAll_7 += 1;
	            // line7();
		        		}
		    		}
				}   
	            if (relX >= 372 && relX <= 378) {
	            // if (sx >= 763 && sx <= 768) {
	                if (relY >= 54 && relY <= 63) {
	                    // if (sy >= 316 && sy <= 322) {
	            var sx = sx-5;
	             var sy = sy-5;
	            relY = relY - 5;
	            relX = relX - 5;
	            if(cnt9_7 == 0){
	            // $("body").append("<p id='step79' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute; " + zIndex + " top:" + sy + "px;left:" + sx + "px;'></p>");
	            $("#dragObject").append("<p id='step79' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute; top:53px;left:370.5px;'></p>");
	            // $("#dragObject").append("<p id='step79' style='background-color: black; width:15px; height:15px; border-radius:10px; position:absolute; " + zIndex + " top:" + relY + "px;left:" + relX + "px;'></p>");
	            cnt9_7=1; 
	            cntAll_7 += 1;
	            // line7();
		        		}
		    		}
				}
			}
			// else
			// { 
			// 	if(pCount == 7 ){
			// 		$("p").click(function() {
			// 		let stepID = $(this).attr("id");
		 //            $("#"+stepID).css("background-color", "green");
		 //            // alert($(this).attr("id"));
		 //        	})
		 //        	drawLines();//show line
		 //        }
	  //       }
	  //================D3====================circle
			// var d3Sel = d3.selectAll('p');
			// var d3Sel = $(".dragObject").find('p');
			// console.log(d3Sel);

			// d3.select('body')
			//     .append('svg')
			//     .attr({
			//       'width':200,
			//       'height':200
			//     });

			  // d3.select('svg')
			  //   .append('circle')
			  //   .attr({
			  //   'cx':50,
			  //   'cy':50,
			  //   'r':30,
			  //   'fill':'#f90',
			  //   'stroke':'#c00',
			  //   'stroke-width':'5px'
			  //   });
//=========================================

	  //====================================================================start-點變綠色===========================================
			// pCount= $(".dragObject").find('p').length;//取得點的數量

			
	  		// if(pCount == 7 ){//7個黑色點已完成才能click 點變綠色
					// $("p").click(function() {
					// let stepID = $(this).attr("id");//.substr(4,2);
		   //          $("#"+stepID).css("background-color", "green");
		   //          // alert($(this).attr("id"));
		   //      	})
		        	// drawLines();//show line------old
		        	drawD3Lines();//show line
				// }
		//===================================================================end-點變綠色===========================================
      });
		//==============================================================end-畫點========================================================================================
	
	function drawLines(){//show line

		let pCntStr = "";
		let pIdAry = new Array();
		let pCount= $(".dragObject").find('p').length;

		// if(pCount == 7){//七個點都標記完才能畫線

		// $("#line1-1-1").show();
		// $("#line2-2-2").show();
		// $("#line1-6-1").show();

			for(j=0 ; j < pCount ; j++){
				let pId = $(".dragObject").find('p')[j].id;
				
				// alert('pid-color='+$("#"+pId).css('background-color'));
				if($("#"+pId).css('background-color') == "rgb(0, 128, 0)"){//取得綠色點的ID
					pCntStr +=  pId +",";
					pIdAry.push(pId);
					pIdAry.sort();
				// alert('pIdAry_pre='+pIdAry);
				// alert('pIdAry_last='+pIdAry.sort());

				}
			}
			if(pIdAry.length > 1){
				// alert('pIdAry.length='+pIdAry.length);
				for( ii=0 ; ii < pIdAry.length-1 ; ii++){
					let p_Id = pIdAry[ii];
					let p_Id_2 = pIdAry[ii+1];
					let p_Id_2_1 = parseInt(p_Id_2.substr(5,1))-1;
					let line_Id = "line"+p_Id.substr(5,1)+"-"+p_Id.substr(4,1)+"-"+p_Id_2_1;
					// alert("line_Id="+line_Id);
					// $("#line1-6-1").show();
					let draw_line_neigh = parseInt(p_Id_2.substr(4,1))-parseInt(p_Id.substr(4,1));
					if(draw_line_neigh == 1){//判斷是否為相鄰之點
						$("#"+line_Id).show();
					}
				}
			}
			// alert('pCntStr='+pCntStr);
		// }
	}

	function line1() {
        // $("#line-1-0,#line-1-1, #line-1-2, #line-1-3, #line-1-4, #line-1-5, #line-1-6, #line-1-7, #line-1-8").css("border", "2px solid black");
        // $("#line2-1-0,#line2-1-1, #line2-1-2, #line2-1-3, #line2-1-4, #line2-1-5, #line2-1-6, #line2-1-7, #line2-1-8").css("border", "2px solid black");
        // $("#line3-1-0,#line3-1-1, #line3-1-2, #line3-1-3, #line3-1-4, #line3-1-5, #line3-1-6, #line3-1-7, #line3-1-8").css("border", "2px solid black");
        // $("#line4-1-0,#line4-1-1, #line4-1-2, #line4-1-3, #line4-1-4, #line4-1-5, #line4-1-6, #line4-1-7, #line4-1-8").css("border", "2px solid black");
        $("p").click(function() {
            // console.log($(this).attr("id"));            
            let stepValue = $(this).attr("id").substr(5,1);
        alert('id='+stepValue);
            // alert('test='+stepValue);
        switch(parseInt(stepValue)){
        	// case 1:
	        // $("#line-1-0").show();
	        // $("#line-1-1").show();
	        // $("#line-1-2").show();
	        // $("#line-1-3").show();
	        // $("#line-1-4").show();
	        // $("#line-1-5").show();
	        // $("#line-1-6").show();
	        // $("#line-1-7").show();
	        // $("#line-1-8").show();
	        // break;
	        case parseInt(stepValue):
        	$("#line"+stepValue+"-1-0").show();
        	$("#line"+stepValue+"-1-1").show();
        	$("#line"+stepValue+"-1-2").show();
        	$("#line"+stepValue+"-1-3").show();
        	$("#line"+stepValue+"-1-4").show();
        	$("#line"+stepValue+"-1-5").show();
        	$("#line"+stepValue+"-1-6").show();
        	$("#line"+stepValue+"-1-7").show();
        	$("#line"+stepValue+"-1-8").show();
        	break;
	        // case 3:
        	// $("#line3-1-0").show();
        	// $("#line3-1-1").show();
        	// $("#line3-1-2").show();
        	// $("#line3-1-3").show();
        	// $("#line3-1-4").show();
        	// $("#line3-1-5").show();
        	// $("#line3-1-6").show();
        	// $("#line3-1-7").show();
        	// $("#line3-1-8").show();
        	// break;
	        // case 4:
        	// $("#line4-1-0").show();
        	// $("#line4-1-1").show();
        	// $("#line4-1-2").show();
        	// $("#line4-1-3").show();
        	// $("#line4-1-4").show();
        	// $("#line4-1-5").show();
        	// $("#line4-1-6").show();
        	// $("#line4-1-7").show();
        	// $("#line4-1-8").show();
        	// break;
	        // case 5:
        	// $("#line5-1-0").show();
        	// $("#line5-1-1").show();
        	// $("#line5-1-2").show();
        	// $("#line5-1-3").show();
        	// $("#line5-1-4").show();
        	// $("#line5-1-5").show();
        	// $("#line5-1-6").show();
        	// $("#line5-1-7").show();
        	// $("#line5-1-8").show();
        	// break;
	        // case 6:
        	// $("#line6-1-0").show();
        	// $("#line6-1-1").show();
        	// $("#line6-1-2").show();
        	// $("#line6-1-3").show();
        	// $("#line6-1-4").show();
        	// $("#line6-1-5").show();
        	// $("#line6-1-6").show();
        	// $("#line6-1-7").show();
        	// $("#line6-1-8").show();
        	// break;
	        }
    	})
	}
	function line2() {
        // $("#line-2-0,#line-2-1, #line-2-2, #line-2-3, #line-2-4, #line-2-5, #line-2-6, #line-2-7, #line-2-8").css("border", "2px solid black");
        // $("#line2-2-0,#line2-2-1, #line2-2-2, #line2-2-3, #line2-2-4, #line2-2-5, #line2-2-6, #line2-2-7, #line2-2-8").css("border", "2px solid black");
        // $("#line3-2-0,#line3-2-1, #line3-2-2, #line3-2-3, #line3-2-4, #line3-2-5, #line3-2-6, #line3-2-7, #line3-2-8").css("border", "2px solid black");
        // $("#line4-2-0,#line4-2-1, #line4-2-2, #line4-2-3, #line4-2-4, #line4-2-5, #line4-2-6, #line4-2-7, #line4-2-8").css("border", "2px solid black");
        $("p").click(function() {
            // console.log($(this).attr("id"));            
            //let stepValue = $(this).attr("id");
            //alert('test');
		let stepValue = $(this).attr("id").substr(5,1);
            // alert('test='+stepValue);
        switch(parseInt(stepValue)){
        	// case 1:
	        // $("#line-2-0").show();
	        // $("#line-2-1").show();
	        // $("#line-2-2").show();
	        // $("#line-2-3").show();
	        // $("#line-2-4").show();
	        // $("#line-2-5").show();
	        // $("#line-2-6").show();
	        // $("#line-2-7").show();
	        // $("#line-2-8").show();
	        // break;
	        case parseInt(stepValue):
        	$("#line"+stepValue+"-2-0").show();
        	$("#line"+stepValue+"-2-1").show();
        	$("#line"+stepValue+"-2-2").show();
        	$("#line"+stepValue+"-2-3").show();
        	$("#line"+stepValue+"-2-4").show();
        	$("#line"+stepValue+"-2-5").show();
        	$("#line"+stepValue+"-2-6").show();
        	$("#line"+stepValue+"-2-7").show();
        	$("#line"+stepValue+"-2-8").show();
        	break;
	        // case 3:
        	// $("#line3-2-0").show();
        	// $("#line3-2-1").show();
        	// $("#line3-2-2").show();
        	// $("#line3-2-3").show();
        	// $("#line3-2-4").show();
        	// $("#line3-2-5").show();
        	// $("#line3-2-6").show();
        	// $("#line3-2-7").show();
        	// $("#line3-2-8").show();
        	// break;
	        // case 4:
        	// $("#line4-2-0").show();
        	// $("#line4-2-1").show();
        	// $("#line4-2-2").show();
        	// $("#line4-2-3").show();
        	// $("#line4-2-4").show();
        	// $("#line4-2-5").show();
        	// $("#line4-2-6").show();
        	// $("#line4-2-7").show();
        	// $("#line4-2-8").show();
        	// break;
	        // case 5:
        	// $("#line5-2-0").show();
        	// $("#line5-2-1").show();
        	// $("#line5-2-2").show();
        	// $("#line5-2-3").show();
        	// $("#line5-2-4").show();
        	// $("#line5-2-5").show();
        	// $("#line5-2-6").show();
        	// $("#line5-2-7").show();
        	// $("#line5-2-8").show();
        	// break;
	        // case 6:
        	// $("#line6-2-0").show();
        	// $("#line6-2-1").show();
        	// $("#line6-2-2").show();
        	// $("#line6-2-3").show();
        	// $("#line6-2-4").show();
        	// $("#line6-2-5").show();
        	// $("#line6-2-6").show();
        	// $("#line6-2-7").show();
        	// $("#line6-2-8").show();
        	// break;
	        }
    	})
	}
	function line3() {
        // $("#line-3-0,#line-3-1, #line-3-2, #line-3-3, #line-3-4, #line-3-5, #line-3-6, #line-3-7, #line-3-8").css("border", "2px solid black");
        // $("#line2-3-0,#line2-3-1, #line2-3-2, #line2-3-3, #line2-3-4, #line2-3-5, #line2-3-6, #line2-3-7, #line2-3-8").css("border", "2px solid black");
        // $("#line3-3-0,#line3-3-1, #line3-3-2, #line3-3-3, #line3-3-4, #line3-3-5, #line3-3-6, #line3-3-7, #line3-3-8").css("border", "2px solid black");
        $("p").click(function() {
            // console.log($(this).attr("id"));            
            //let stepValue = $(this).attr("id");
            //alert('test');
		let stepValue = $(this).attr("id").substr(5,1);
            // alert('test='+stepValue);
        switch(parseInt(stepValue)){
        	// case 1:
	        // $("#line-3-0").show();
	        // $("#line-3-1").show();
	        // $("#line-3-2").show();
	        // $("#line-3-3").show();
	        // $("#line-3-4").show();
	        // $("#line-3-5").show();
	        // $("#line-3-6").show();
	        // $("#line-3-7").show();
	        // $("#line-3-8").show();
	        // break;
	        case parseInt(stepValue):
	        $("#line"+stepValue+"-3-0").show();
	        $("#line"+stepValue+"-3-1").show();
	        $("#line"+stepValue+"-3-2").show();
	        $("#line"+stepValue+"-3-3").show();
	        $("#line"+stepValue+"-3-4").show();
	        $("#line"+stepValue+"-3-5").show();
	        $("#line"+stepValue+"-3-6").show();
	        $("#line"+stepValue+"-3-7").show();
	        $("#line"+stepValue+"-3-8").show();
        	break;
	        // case 3:
	        // $("#line3-3-0").show();
	        // $("#line3-3-1").show();
	        // $("#line3-3-2").show();
	        // $("#line3-3-3").show();
	        // $("#line3-3-4").show();
	        // $("#line3-3-5").show();
	        // $("#line3-3-6").show();
	        // $("#line3-3-7").show();
	        // $("#line3-3-8").show();
        	// break;
	        // case 4:
	        // $("#line4-3-0").show();
	        // $("#line4-3-1").show();
	        // $("#line4-3-2").show();
	        // $("#line4-3-3").show();
	        // $("#line4-3-4").show();
	        // $("#line4-3-5").show();
	        // $("#line4-3-6").show();
	        // $("#line4-3-7").show();
	        // $("#line4-3-8").show();
        	// break;
	        // case 5:
	        // $("#line5-3-0").show();
	        // $("#line5-3-1").show();
	        // $("#line5-3-2").show();
	        // $("#line5-3-3").show();
	        // $("#line5-3-4").show();
	        // $("#line5-3-5").show();
	        // $("#line5-3-6").show();
	        // $("#line5-3-7").show();
	        // $("#line5-3-8").show();
        	// break;
	        // case 6:
	        // $("#line6-3-0").show();
	        // $("#line6-3-1").show();
	        // $("#line6-3-2").show();
	        // $("#line6-3-3").show();
	        // $("#line6-3-4").show();
	        // $("#line6-3-5").show();
	        // $("#line6-3-6").show();
	        // $("#line6-3-7").show();
	        // $("#line6-3-8").show();
        	// break;
	        }
    	})
	}
	function line4() {
        // $("#line-4-0,#line-4-1, #line-4-2, #line-4-3, #line-4-4, #line-4-5, #line-4-6, #line-4-7, #line-4-8").css("border", "2px solid black");
        // $("#line2-4-0,#line2-4-1, #line2-4-2, #line2-4-3, #line2-4-4, #line2-4-5, #line2-4-6, #line2-4-7, #line2-4-8").css("border", "2px solid black");
        // $("#line3-4-0,#line3-4-1, #line3-4-2, #line3-4-3, #line3-4-4, #line3-4-5, #line3-4-6, #line3-4-7, #line3-4-8").css("border", "2px solid black");
        $("p").click(function() {
            // console.log($(this).attr("id"));            
            //let stepValue = $(this).attr("id");
            //alert('test');
		let stepValue = $(this).attr("id").substr(5,1);
            // alert('test='+stepValue);
        switch(parseInt(stepValue)){
        	// case 1:
	        // $("#line-4-0").show();
	        // $("#line-4-1").show();
	        // $("#line-4-2").show();
	        // $("#line-4-3").show();
	        // $("#line-4-4").show();
	        // $("#line-4-5").show();
	        // $("#line-4-6").show();
	        // $("#line-4-7").show();
	        // $("#line-4-8").show();
	        // break;
	        case parseInt(stepValue):
	        $("#line"+stepValue+"-4-0").show();
	        $("#line"+stepValue+"-4-1").show();
	        $("#line"+stepValue+"-4-2").show();
	        $("#line"+stepValue+"-4-3").show();
	        $("#line"+stepValue+"-4-4").show();
	        $("#line"+stepValue+"-4-5").show();
	        $("#line"+stepValue+"-4-6").show();
	        $("#line"+stepValue+"-4-7").show();
	        $("#line"+stepValue+"-4-8").show();
        	break;
	        // case 3:
	        // $("#line3-4-0").show();
	        // $("#line3-4-1").show();
	        // $("#line3-4-2").show();
	        // $("#line3-4-3").show();
	        // $("#line3-4-4").show();
	        // $("#line3-4-5").show();
	        // $("#line3-4-6").show();
	        // $("#line3-4-7").show();
	        // $("#line3-4-8").show();
        	// break;
	        // case 4:
	        // $("#line4-4-0").show();
	        // $("#line4-4-1").show();
	        // $("#line4-4-2").show();
	        // $("#line4-4-3").show();
	        // $("#line4-4-4").show();
	        // $("#line4-4-5").show();
	        // $("#line4-4-6").show();
	        // $("#line4-4-7").show();
	        // $("#line4-4-8").show();
        	// break;
	        // case 5:
	        // $("#line5-4-0").show();
	        // $("#line5-4-1").show();
	        // $("#line5-4-2").show();
	        // $("#line5-4-3").show();
	        // $("#line5-4-4").show();
	        // $("#line5-4-5").show();
	        // $("#line5-4-6").show();
	        // $("#line5-4-7").show();
	        // $("#line5-4-8").show();
        	// break;
	        // case 6:
	        // $("#line6-4-0").show();
	        // $("#line6-4-1").show();
	        // $("#line6-4-2").show();
	        // $("#line6-4-3").show();
	        // $("#line6-4-4").show();
	        // $("#line6-4-5").show();
	        // $("#line6-4-6").show();
	        // $("#line6-4-7").show();
	        // $("#line6-4-8").show();
        	// break;
	        }
    	})
	}
	function line5() {
        // $("#line-5-0,#line-5-1, #line-5-2, #line-5-3, #line-5-4, #line-5-5, #line-5-6, #line-5-7, #line-5-8").css("border", "2px solid black");
        // $("#line2-5-0,#line2-5-1, #line2-5-2, #line2-5-3, #line2-5-4, #line2-5-5, #line2-5-6, #line2-5-7, #line2-5-8").css("border", "2px solid black");
        // $("#line3-5-0,#line3-5-1, #line3-5-2, #line3-5-3, #line3-5-4, #line3-5-5, #line3-5-6, #line3-5-7, #line3-5-8").css("border", "2px solid black");
        $("p").click(function() {
            // console.log($(this).attr("id"));            
            //let stepValue = $(this).attr("id");
            //alert('test');
		let stepValue = $(this).attr("id").substr(5,1);
            // alert('test='+stepValue);
        switch(parseInt(stepValue)){
        	// case 1:
	        // $("#line-5-0").show();
	        // $("#line-5-1").show();
	        // $("#line-5-2").show();
	        // $("#line-5-3").show();
	        // $("#line-5-4").show();
	        // $("#line-5-5").show();
	        // $("#line-5-6").show();
	        // $("#line-5-7").show();
	        // $("#line-5-8").show();
	        // break;
	        case parseInt(stepValue):
	        $("#line"+stepValue+"-5-0").show();
	        $("#line"+stepValue+"-5-1").show();
	        $("#line"+stepValue+"-5-2").show();
	        $("#line"+stepValue+"-5-3").show();
	        $("#line"+stepValue+"-5-4").show();
	        $("#line"+stepValue+"-5-5").show();
	        $("#line"+stepValue+"-5-6").show();
	        $("#line"+stepValue+"-5-7").show();
	        $("#line"+stepValue+"-5-8").show();
        	break;
	        // case 3:
	        // $("#line3-5-0").show();
	        // $("#line3-5-1").show();
	        // $("#line3-5-2").show();
	        // $("#line3-5-3").show();
	        // $("#line3-5-4").show();
	        // $("#line3-5-5").show();
	        // $("#line3-5-6").show();
	        // $("#line3-5-7").show();
	        // $("#line3-5-8").show();
        	// break;
	        // case 4:
	        // $("#line4-5-0").show();
	        // $("#line4-5-1").show();
	        // $("#line4-5-2").show();
	        // $("#line4-5-3").show();
	        // $("#line4-5-4").show();
	        // $("#line4-5-5").show();
	        // $("#line4-5-6").show();
	        // $("#line4-5-7").show();
	        // $("#line4-5-8").show();
        	// break;
	        // case 5:
	        // $("#line5-5-0").show();
	        // $("#line5-5-1").show();
	        // $("#line5-5-2").show();
	        // $("#line5-5-3").show();
	        // $("#line5-5-4").show();
	        // $("#line5-5-5").show();
	        // $("#line5-5-6").show();
	        // $("#line5-5-7").show();
	        // $("#line5-5-8").show();
        	// break;
	        // case 6:
	        // $("#line6-5-0").show();
	        // $("#line6-5-1").show();
	        // $("#line6-5-2").show();
	        // $("#line6-5-3").show();
	        // $("#line6-5-4").show();
	        // $("#line6-5-5").show();
	        // $("#line6-5-6").show();
	        // $("#line6-5-7").show();
	        // $("#line6-5-8").show();
        	// break;
	        }
    	})
	}
	function line6() {
        // $("#line-6-0,#line-6-1, #line-6-2, #line-6-3, #line-6-4, #line-6-5, #line-6-6, #line-6-7, #line-6-8").css("border", "2px solid black");
        // $("#line2-6-0,#line2-6-1, #line2-6-2, #line2-6-3, #line2-6-4, #line2-6-5, #line2-6-6, #line2-6-7, #line2-6-8").css("border", "2px solid black");
        // $("#line3-6-0,#line3-6-1, #line3-6-2, #line3-6-3, #line3-6-4, #line3-6-5, #line3-6-6, #line3-6-7, #line3-6-8").css("border", "2px solid black");
        $("p").click(function() {
            // console.log($(this).attr("id"));            
            //let stepValue = $(this).attr("id");
            //alert('test');
		let stepValue = $(this).attr("id").substr(5,1);
            // alert('test='+stepValue);
        switch(parseInt(stepValue)){
        	// case 1:
	        // $("#line-6-0").show();
	        // $("#line-6-1").show();
	        // $("#line-6-2").show();
	        // $("#line-6-3").show();
	        // $("#line-6-4").show();
	        // $("#line-6-5").show();
	        // $("#line-6-6").show();
	        // $("#line-6-7").show();
	        // $("#line-6-8").show();
	        // break;
	        case parseInt(stepValue):
	        $("#line"+stepValue+"-6-0").show();
	        $("#line"+stepValue+"-6-1").show();
	        $("#line"+stepValue+"-6-2").show();
	        $("#line"+stepValue+"-6-3").show();
	        $("#line"+stepValue+"-6-4").show();
	        $("#line"+stepValue+"-6-5").show();
	        $("#line"+stepValue+"-6-6").show();
	        $("#line"+stepValue+"-6-7").show();
	        $("#line"+stepValue+"-6-8").show();
        	break;
	        // case 3:
	        // $("#line3-6-0").show();
	        // $("#line3-6-1").show();
	        // $("#line3-6-2").show();
	        // $("#line3-6-3").show();
	        // $("#line3-6-4").show();
	        // $("#line3-6-5").show();
	        // $("#line3-6-6").show();
	        // $("#line3-6-7").show();
	        // $("#line3-6-8").show();
        	// break;
	        // case 4:
	        // $("#line4-6-0").show();
	        // $("#line4-6-1").show();
	        // $("#line4-6-2").show();
	        // $("#line4-6-3").show();
	        // $("#line4-6-4").show();
	        // $("#line4-6-5").show();
	        // $("#line4-6-6").show();
	        // $("#line4-6-7").show();
	        // $("#line4-6-8").show();
        	// break;
	        // case 5:
	        // $("#line5-6-0").show();
	        // $("#line5-6-1").show();
	        // $("#line5-6-2").show();
	        // $("#line5-6-3").show();
	        // $("#line5-6-4").show();
	        // $("#line5-6-5").show();
	        // $("#line5-6-6").show();
	        // $("#line5-6-7").show();
	        // $("#line5-6-8").show();
        	// break;
	        // case 6:
	        // $("#line6-6-0").show();
	        // $("#line6-6-1").show();
	        // $("#line6-6-2").show();
	        // $("#line6-6-3").show();
	        // $("#line6-6-4").show();
	        // $("#line6-6-5").show();
	        // $("#line6-6-6").show();
	        // $("#line6-6-7").show();
	        // $("#line6-6-8").show();
        	// break;
	        }
    	})
	}
	function line7() {
        // $("#line-7-0,#line-7-1, #line-7-2, #line-7-3, #line-7-4, #line-7-5, #line-7-6, #line-7-7, #line-7-8").css("border", "2px solid black");
        // $("#line2-7-0,#line2-7-1, #line2-7-2, #line2-7-3, #line2-7-4, #line2-7-5, #line2-7-6, #line2-7-7, #line2-7-8").css("border", "2px solid black");
        // $("#line3-7-0,#line3-7-1, #line3-7-2, #line3-7-3, #line3-7-4, #line3-7-5, #line3-7-6, #line3-7-7, #line3-7-8").css("border", "2px solid black");
        $("p").click(function() {
            // console.log($(this).attr("id"));            
            //let stepValue = $(this).attr("id");
            //alert('test');
		let stepValue = $(this).attr("id").substr(5,1);
            // alert('test='+stepValue);
        switch(parseInt(stepValue)){
        	case parseInt(stepValue):
	        $("#line"+stepValue+"-6-0").show();
	        $("#line"+stepValue+"-6-1").show();
	        $("#line"+stepValue+"-6-2").show();
	        $("#line"+stepValue+"-6-3").show();
	        $("#line"+stepValue+"-6-4").show();
	        $("#line"+stepValue+"-6-5").show();
	        $("#line"+stepValue+"-6-6").show();
	        $("#line"+stepValue+"-6-7").show();
	        $("#line"+stepValue+"-6-8").show();
	        break;
        	// case parseInt(stepValue):
	        // $("#line"+stepValue+"-7-0").show();
	        // $("#line"+stepValue+"-7-1").show();
	        // $("#line"+stepValue+"-7-2").show();
	        // $("#line"+stepValue+"-7-3").show();
	        // $("#line"+stepValue+"-7-4").show();
	        // $("#line"+stepValue+"-7-5").show();
	        // $("#line"+stepValue+"-7-6").show();
	        // $("#line"+stepValue+"-7-7").show();
	        // $("#line"+stepValue+"-7-8").show();
	        // break;
	    }
        // switch(parseInt(stepValue)){
        // 	case 1:
	       //  $("#line-7-0").show();
	       //  $("#line-7-1").show();
	       //  $("#line-7-2").show();
	       //  $("#line-7-3").show();
	       //  $("#line-7-4").show();
	       //  $("#line-7-5").show();
	       //  $("#line-7-6").show();
	       //  $("#line-7-7").show();
	       //  $("#line-7-8").show();
	       //  break;
	       //  case 2:
	       //  $("#line2-7-0").show();
	       //  $("#line2-7-1").show();
	       //  $("#line2-7-2").show();
	       //  $("#line2-7-3").show();
	       //  $("#line2-7-4").show();
	       //  $("#line2-7-5").show();
	       //  $("#line2-7-6").show();
	       //  $("#line2-7-7").show();
	       //  $("#line2-7-8").show();
        // 	break;
	       //  case 3:
	       //  $("#line3-7-0").show();
	       //  $("#line3-7-1").show();
	       //  $("#line3-7-2").show();
	       //  $("#line3-7-3").show();
	       //  $("#line3-7-4").show();
	       //  $("#line3-7-5").show();
	       //  $("#line3-7-6").show();
	       //  $("#line3-7-7").show();
	       //  $("#line3-7-8").show();
        // 	break;
	       //  case 4:
	       //  $("#line4-7-0").show();
	       //  $("#line4-7-1").show();
	       //  $("#line4-7-2").show();
	       //  $("#line4-7-3").show();
	       //  $("#line4-7-4").show();
	       //  $("#line4-7-5").show();
	       //  $("#line4-7-6").show();
	       //  $("#line4-7-7").show();
	       //  $("#line4-7-8").show();
        // 	break;
	       //  }
    	})
	}
	   
  var dLineAry = [];
	//============D3 START drawline==================
	function drawD3Lines(){
		let pIdAry = new Array();
		let pCount= $(".dragObject").find('p').length;
	//(x,y) -> (left,top)
	// function dataFun(){
	var x1=160.5+7, y1=138+55; 
	var x2=201.5+7, y2=54+55;
	var x3=244.5+7, y3=96+55;
	var x4=286.5+7, y4=138+55;
	var x5=326.5+7, y5=94+55;
	var x6=368.5+7, y6=180+55;
	var x7=117.5+7, y7=220+55;
	var x8=160.5+7, y8=138+55; 



	var dataAry = [];
	var xyStr;
	let leftInt=0 ,topInt=0 ;
	if(pCount >= 2){
		for(i=0 ; i < pCount ; i++){
			let pId = $(".dragObject").find('p')[i].id;
			pIdAry.push(pId);
			leftInt = $("#"+pId).css('left');//$(".dragAll")[i].offsetLeft;
		  	leftInt = leftInt.substring(0,leftInt.length - 2); //去除px 取得數值
		  	topInt = $("#"+pId).css('top');//$(".dragAll")[i].offsetTop;
		  	topInt = topInt.substring(0,topInt.length - 2); 
		  	// idName = $(".dragAll")[i].id;
		  	// console.log( pId+"= left: " + eval(parseInt(leftInt)+7) + ", top: " + eval(parseInt(topInt)+55) );

			// dataAry.push("{x:"+eval("x"+i)+", y:"+eval("y"+i)+"}");
			dataAry.push("{x:"+ eval(parseInt(leftInt)+7)+", y:"+ eval(parseInt(topInt)+55)+"}");
			// console.log(dataAry);
		}
		xyStr = "["+dataAry.toString()+"]" ;

		    

		//然後使用 line().x() 以及 line().y()，讓座標由 data 長出來
		  var line = d3.svg.line()
		    .x(function(d) {
		      return d.x;
		    })
		    .y(function(d) {
		      return d.y;
		    });
		//最後就是利用 append 的方式在 svg 裏頭放入一個 path，d 是用line(data)將 data 餵給剛剛的 line，如此各個點的座標就會依序長出
		if($("#path01").length == 0){
		 var svg = d3.select('.image1_wrapper')
		    .append('svg')
			.attr({
			      'width': 469,
			      'height': 580//,
			    });
		svg.append('path')
		    .attr({
		      'd': line(eval(xyStr)),
		      'y': 0,
		      'stroke': '#000',
		      'stroke-width': '5px',
		      'fill': 'none',
		      'id':'path01'
		    });
		}else{
		    var path = document.getElementById('path01');
  			path.setAttribute('d',line(eval(xyStr)));
  		}     
  		var index = dLineAry.indexOf(line(eval(xyStr)));
      if(index == -1)
  		{
  			dLineAry.push(line(eval(xyStr)));
  		}
	}
}
//============D3 END drawline==================

	
	function resetEvent() {
		//--clear 點--
		
		let pCnt = $("p[id^='step']").length;
		let lineCnt = $("div[id^='line']").length;
		for(i=0 ; i < pCnt ; i++){//remove circle
			let p_Id = $("p[id^='step']")[0].id;
			$("#"+p_Id).remove();
		// alert('step_ID='+p_Id);
		}

		
		for(k=1 ; k < 8 ; k++)
		{//將點count數歸零
			for(kk=1 ; kk < 10 ; kk++)
			{
				let varStr = "cnt"+kk+"_"+k;
				eval("cnt"+kk+"_"+k +"= 0");
				// alert('varStr='+varStr);
				// "'"+varStr+"'"=0;
			}
			 	let varAll = "cntAll_"+k;
				eval("cntAll_"+k +"= 0");
				// "'"+varAll="'"=0;
		}

			//--clear D3 line--
		$("svg").remove();
    dLineAry=[];

	}
  function preEvent(){
		let xyStr2="" ;
		let path2 = document.getElementById('path01');

		if(dLineAry.length > 0)
		{
    
		xyStr2 = dLineAry[dLineAry.length-2] ;
		// alert("dLineAry.length="+dLineAry.length);
            if( xyStr2 != undefined)
            {
  				path2.setAttribute('d',xyStr2);
  			}else{
  				path2.setAttribute('d','');
  			}
			// syStr = dLineAry[dLineAry.length-1];
			dLineAry.splice(dLineAry.length-1, 1);

		let pCnt = $("p[id^='step']").length;
		let p_Id = $("p[id^='step']")[pCnt-1].id;
		$("#"+p_Id).remove();        		

		eval("cnt"+p_Id.substr(5,1)+"_"+p_Id.substr(4,1) +"= 0");

		eval("cntAll_"+p_Id.substr(4,1) +"= 0");     
	
		}

	}


	function submit() {
		let pIdAry = new Array();
		let pIdArySrt = new Array();
		let lineIdAry = new Array();
		let p_str ,p_str_r , line_str;

		let pCnt = $("p[id^='step']").length;
		let lineCnt = $("div[id^='line']").length;

		for(i=0 ; i < pCnt ; i++){
			let p_Id = $("p[id^='step']")[i].id;
			// p_str += p_Id + ",";
			pIdAry.push(p_Id);
			pIdArySrt.push(p_Id);
			pIdArySrt.sort();
			p_str="step18,step25,step37,step45,step52,step62,step71";//正確點之ID
			p_str_r="step71,step62,step52,step45,step37,step25,step18";//正確點之ID:從右往左點
		}
		if(pIdArySrt == p_str){//3分：點全部標記對
			ansA_cnt = 3;
			for(ii=0 ; ii < lineCnt ; ii++){//remove circle
			let line_Id = $("div[id^='line']")[ii].id;
			// p_str += p_Id + ",";
				if($("#"+line_Id).css("display") != "none")
				{
					lineIdAry.push(line_Id);				
				}
				lineIdAry.sort();
				line_str="line2-5-1,line2-6-0,line5-2-6,line5-4-1,line7-3-4,line8-1-4";//正確線之ID
			}
			if((pIdAry == p_str) || (pIdAry == p_str_r) ){//2分：線全部畫對
				ansB_cnt = 2;
			}
			else{ansB_cnt = 0;}
			// if(lineIdAry == line_str){//2分：線全部畫對(前提:點全部標記對)
			// 	ansB_cnt = 2;
			// }
			// else{ansB_cnt = 0;}

		}else{
			ansA_cnt = 0;
			ansB_cnt = 0;
		}
      
		score = ansA_cnt + ansB_cnt;
    let level = 0;
    if(score > 0){
      if(score > 3){level = 2;}
      else{level = 1;}
    }
    console.log('分數：'+score+' 分,level:' +level);
    document.keypad.user_answer.value=score;
    document.keypad.cr_bin_res.value=level; 
    //document.keypad.cr_org_res.value=level;
		//alert ('分數：'+score+' 分');

	}


</script>

