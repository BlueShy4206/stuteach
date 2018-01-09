
	<div class="question">
		<div class="container">
			<p></p>
			<p class="q_text">
				請依據考古學中的「文化層」觀念，將下列「<u>臺灣</u>史前文化遺址」的正確文化層層序位置排列出來。
			</p>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col col-md-12">
				<div class="dragArea" id="dragArea">
					<!-- <p>作答區</p> -->
					<div class="image1_wrapper" id="image1_wrapper">
						<img src="ck_cr/social_01/title_01.png">
					</div>
			<p style="position: absolute;margin: -248px 514px;width: 469px; height:581px;font-size: 19px;">操作說明：<br>請將各文化遺址框拉曳至作答區中，並按層序位置排列即可。</p>
					<p></p>
					<p style="font-size: 16;">各文化遺址</p>

					<div class="dragWrapper">
						<div class="dragArea0" id="dragArea0">
							<div class="dragObject1 dragAll" id="dragObject1" style="position: absolute; margin: 3px 92px;">
								<img src="ck_cr/social_01/n_opt_01.png">
							</div>
							<div class="dragObject2 dragAll" id="dragObject2" style="position: absolute; margin: 88px 92px;">
								<img src="ck_cr/social_01/n_opt_02.png">
							</div>
							<div class="dragObject3 dragAll" id="dragObject3" style="position: absolute; margin: 176px 92px;">
								<img src="ck_cr/social_01/n_opt_03.png">
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>


<style type="text/css">
	body{
		font-family: "DFKai-sb", "Times New Roman";	
		font-size: 17pt ;
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
		height: 745px;
		position: relative;
		border-bottom: 2px solid #9DD5FD;
	}

	.dragWrapper {
		width: 388px;
    	border: 1px solid red;
    	height: 255px;
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
		width: 170px;
    	height: 274px;
	}

	#dragArea1 {
		display: inline-block;
		width: 165px;
		height: 268px;
	}

	.dragArea2 {
		display: inline-block;
	    width: 126px;
	    height: 275px;
	}

	.areapostition_absolut {
		position: absolute;
	}

	.image1_wrapper {
		max-width: 3269px;
		max-height: 2775px;
		margin: 0px;
	}

	.image1_wrapper img {
		max-width: 40%;
		max-height: 90%;
	}

	.dragObject1 {
		display: block;
	    width: 200px;
	    height: 53px;
	}

	.dragObject1 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject2 {
		display: block;
	    width: 200px;
	    height: 53px;
	}

	.dragObject2 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject3 {
		display: block;
	    width: 200px;
	    height: 53px;
	}

	.dragObject3 img {
		max-width: 100%;
		max-height: 100%;
	}

	.question {
		/*border-bottom: 2px solid green;
		border-top: 2px solid green;*/
	}

	.q_text {
		font-size: 20pt;
		//border-top: 2px solid #1e79cf;
	}
	}

	.btnWrapper {
		width: 100%;
		height: 40px;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.btnWrapper input {
		font-size: 18pt;
	}
</style>
<script type="text/javascript">

	let userData;
	let count = 0;
	let score=0;
	let ansA_cnt=0;
	let ansB_cnt=0;
	let ansC_cnt=0;
	
	//拖拉event
	//網形文化
	$("#dragObject1").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (e, ui) {
			var parentOffset = $(this).parent().offset();
	        // console.log(parentOffset);
	        // var relX = e.pageX - parentOffset.left ;
	        // var relY = e.pageY - parentOffset.top;
        	// console.log(relX, relY);

			console.log(ui.position.left, ui.position.top);
			if (34 <= ui.position.left && ui.position.left <= 72) {
				console.log("right position!");
				if (  ui.position.top <= 331 && ui.position.top >= 318) {
					console.log("right postion!2");
					ansA_cnt = 1;

				}
				else {
					if(ansA_cnt != 0){

					ansA_cnt = ansA_cnt - 1;
					}

				}
			}
			else {
				if(ansA_cnt != 0){

				ansA_cnt = ansA_cnt - 1;
				}

			}
		}
	});
	//圓山文化
	$("#dragObject2").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (e,ui) {
			
		// var parentOffset = $("#image1_wrapper").parent().offset();
			var parentOffset = $(this).parent().offset();
        // console.log(parentOffset);
        // var relX = e.pageX - parentOffset.left ;
        // var relY = e.pageY - parentOffset.top;
        	// console.log(relX, relY);
		console.log(ui.position.left, ui.position.top);
		if (34 <= ui.position.left && ui.position.left <= 72) {
				console.log("right position!");
				if (  ui.position.top <= 159 && ui.position.top >= 147) {
					console.log("right postion!2");
					ansB_cnt = 1;

					//$(this).draggable("destroy");
				}
				else {
					if(ansB_cnt != 0){

					ansB_cnt = ansB_cnt - 1;
					}
				}
			}
			else {
				if(ansB_cnt != 0){

				ansB_cnt = ansB_cnt - 1;
				}

			}

		}
	});
	//十三行文化
	$("#dragObject3").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (e,ui) {
			
		var parentOffset = $("#image1_wrapper").parent().offset();
        // console.log(parentOffset);
        // var relX = e.pageX - parentOffset.left ;
        // var relY = e.pageY - parentOffset.top;
        	// console.log(relX, relY);
		console.log(ui.position.left, ui.position.top);
		if (34 <= ui.position.left && ui.position.left <= 72) {
				console.log("right position!");
				if (  ui.position.top <= -13 && ui.position.top >= -26) {
					console.log("right postion!2");
					ansC_cnt = 1;

				}
				else {
					if(ansC_cnt != 0){

					ansC_cnt = ansC_cnt - 1;
					}

				}
			}
			else {
				if(ansC_cnt != 0){

				ansC_cnt = ansC_cnt - 1;
				}

			}
		}
	});
	
	function submit() {
		let ABFlag=false, CDFlag=false, EFFlag=false;
		let leftInt=0 ,topInt=0 ;
		let idName="";

		 // var el = $("#element");
		 //  var position = el.position();
		 //  console.log( "left: " + position.left + ", top: " + position.top );
		// var el = $("#dragObject2");//document.getElementById('dragObject12');
		//   var position = el.position();
		//   console.log( "left: " + position.left + ", top: " + position.top );
			// console.log(ui.position.left, ui.position.top);
		  // left:725~785
		  // top :276~307
		  // console.log("len="+$(".dragAll").length);

		  // ansD_cnt=1;//步驟二,(2):正確答案"空白"
		  for( i=1 ; i<=$(".dragAll").length ; i++){
			console.log( "i: "+i);
		  	leftInt = $("#dragObject"+i).css('left');//$(".dragAll")[i].offsetLeft;
		  	leftInt = leftInt.substring(0,leftInt.length - 2); //去除px 取得數值
		  	topInt = $("#dragObject"+i).css('top');//$(".dragAll")[i].offsetTop;
		  	topInt = topInt.substring(0,topInt.length - 2); 
		  	// idName = $(".dragAll")[i].id;
		  	console.log( "left: " + leftInt + ", top: " + topInt );
		  	// alert("i="+i+"\n");

		  	// let stepValue = idName.substr(10,2);
		//   	// alert('idName='+idName+'\n'+'stepValue='+stepValue+'\n');
  switch(i){
  	 case 1://A
		  	if( leftInt >=34 && leftInt <=71){
		  		if( topInt >=148 && topInt <=159 ){
		  			ansC_cnt=0;//A欄,0分
		  		}
		  	}
		  	if( leftInt >=34 && leftInt <=71){
		  		if( topInt >=233 && topInt <=244 ){
		  			ansB_cnt=0;//B欄,0分
		  		}
		  	}
		  	// if( leftInt >=399 && leftInt <=420){
		  	// 	if( topInt >=95 && topInt <=118 ){
		  	// 		ansC_cnt=0;//C欄答案不對,0分
		  	// 	}
		  	// }
		  break;
  	 case 2://B
		  	if( leftInt >=34 && leftInt <=71){
		  		if( topInt >=61 && topInt <=73 ){
		  			ABFlag = true;//A欄,0分
		  			ansC_cnt=0;
		  		}
		  	}
		  	// if( leftInt >=130 && leftInt <=150){
		  	// 	if( topInt >=11 && topInt <=33 ){
		  	// 		ansB_cnt=0;//B欄,0分
		  	// 	}
		  	// }
		  	if( leftInt >=34 && leftInt <=71){
		  		if( topInt >=233 && topInt <=244 ){
		  			ansA_cnt=0;//C欄答案不對,0分
		  		}
		  	}
		  break;
  	 case 3://C
		  	// if( leftInt >=47 && leftInt <=71){
		  	// 	if( topInt >=-77 && topInt <=-55 ){
		  	// 		ABFlag = true;//A欄,0分
		  	// 		ansA_cnt=0;
		  	// 	}
		  	// }
		  	if( leftInt >=34 && leftInt <=71){
		  		if( topInt >=59 && topInt <=71 ){
		  			ansB_cnt=0;//B欄,0分
		  		}
		  	}
		  	if( leftInt >=34 && leftInt <=71){
		  		if( topInt >=145 && topInt <=158 ){
		  			ansA_cnt=0;//C欄答案不對,0分
		  		}
		  	}
		  break;
  	 
		  }
		}

		
		//TOTAL=A答對給1分+B答對給1分+E,F都答對才給1分
		// score = ansAB_cnt + ansCD_cnt + ansEF_cnt;
		score = ansA_cnt + ansB_cnt + ansC_cnt ;
    let level = 0;
	    if(score > 0){
	      if(score > 1){
	        if(score > 2){level = 3;}
	        else{level = 2;}
	      }
	      else{level = 1;}
	    }
    console.log('分數：'+score+' 分');
    document.keypad.user_answer.value=score;
    document.keypad.cr_bin_res.value=level;
		//alert ('分數：'+score+' 分');

	}

</script>
