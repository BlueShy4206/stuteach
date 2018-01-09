
	<div class="question">
		<div class="container">
			<p></p>
			<p class="q_text">
				請依照圖中的自然環境樣貌，將可能的地名拖曳至虛框處。
			</p>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col col-md-12">
				<div class="dragArea" id="dragArea">
					<!-- <p></p> -->
					<div class="image1_wrapper" id="image1_wrapper">
						<img src="ck_cr/social_02/title_01.png">
					</div>
					<p></p>
					<p style="font-size: 16;">操作選項</p>

					<div class="dragWrapper">
						<div class="dragArea0" id="dragArea0">
							<div class="dragObject1 dragAll" id="dragObject1" style="position: absolute; margin: 3px 6px;">
								<img src="ck_cr/social_02/n_opt_01.png">
							</div>
							<div class="dragObject2 dragAll" id="dragObject2" style="position: absolute; margin: 3px 91px;">
								<img src="ck_cr/social_02/n_opt_02.png">
							</div>
							<div class="dragObject3 dragAll" id="dragObject3" style="position: absolute; margin: 3px 180px;">
								<img src="ck_cr/social_02/n_opt_03.png">
							</div>
						</div>
						<div class="dragArea1" id="dragArea1">
							<div class="dragObject4 dragAll" id="dragObject4" style="position: absolute; margin: -1px 89px;">
								<img src="ck_cr/social_02/n_opt_04.png">
							</div>
							<div class="dragObject5 dragAll" id="dragObject5" style="position: absolute; margin: -1px 176px;">
								<img src="ck_cr/social_02/n_opt_05.png">
							</div>
							<div class="dragObject6 dragAll" id="dragObject6" style="position: absolute;margin: -1px 267px;">
								<img src="ck_cr/social_02/n_opt_06.png">
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
		width: 538px;
    	border: 1px solid red;
    	height: 98px;
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
	    /*border-left: 1px solid red;*/
		/*width: 114px;
		height: 245px;
    	border: 1px solid red;*/
	}

	.areapostition_absolut {
		position: absolute;
	}

	.image1_wrapper {
		max-width: 1140px;
		max-height: 569px;
		margin: 0px;
	}

	.image1_wrapper img {
		max-width: 62%;
		max-height: 90%;
	}

	.dragObject1 {
		display: block;
	    width: 70px;
   	 	height: 67px;
	}

	.dragObject1 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject2 {
		display: block;
	    width: 70px;
   	 	height: 67px;
	}

	.dragObject2 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject3 {
		display: block;
	    width: 70px;
   	 	height: 67px;
	}

	.dragObject3 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject4 {
		display: block;
	    width: 70px;
   	 	height: 67px;
	}

	.dragObject4 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject5 {
		display: block; 
	    width: 70px;
   	 	height: 67px;
	}

	.dragObject5 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject6 {
		display: block;
	    width: 70px;
   	 	height: 67px;
	}

	.dragObject6 img {
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
	let ansD_cnt=0;
	let ansE_cnt=0;
	let ansF_cnt=0;
	//拖拉event
	//墩
	$("#dragObject1").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (e, ui) {
			// var parentOffset = $(this).parent().offset();
	        // console.log(parentOffset);
	        // var relX = e.pageX - parentOffset.left ;
	        // var relY = e.pageY - parentOffset.top;
        	// console.log(relX, relY);

			console.log(ui.position.left, ui.position.top);
        	leftInt = ui.position.left;
        	topInt = ui.position.top;
			if( leftInt >=124 && leftInt <=136){
				console.log("right position!");
		  		if( topInt >=212 && topInt <=225 ){
					console.log("right postion!2");
					ansA_cnt = 1;
		  			// document.getElementById('dragObject1').style.cssText="position: absolute; margin: 3px 6px; left: 51px; top: 104px;";
		  			// document.getElementById('dragObject1').style.top="104px";
		  			// document.getElementById('dragObject1').style.left="51px";
		  			// CDFlag = true;
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
	//澳
	$("#dragObject2").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (e,ui) {
			
		// var parentOffset = $("#image1_wrapper").parent().offset();
			// var parentOffset = $(this).parent().offset();
        // console.log(parentOffset);
        // var relX = e.pageX - parentOffset.left ;
        // var relY = e.pageY - parentOffset.top;
        	// console.log(relX, relY);
		console.log(ui.position.left, ui.position.top);
		if (415 <= ui.position.left && ui.position.left <= 427) {
				console.log("right position!");
				if (  ui.position.top <= 172 && ui.position.top >= 161) {
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
	//角
	$("#dragObject3").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (e,ui) {
			
		// var parentOffset = $("#image1_wrapper").parent().offset();
        // console.log(parentOffset);
        // var relX = e.pageX - parentOffset.left ;
        // var relY = e.pageY - parentOffset.top;
        	// console.log(relX, relY);
		console.log(ui.position.left, ui.position.top);
		if (369 <= ui.position.left && ui.position.left <= 383) {
				console.log("right position!");
				if (  ui.position.top <= 271 && ui.position.top >= 258) {
					console.log("right postion!2");
					ansC_cnt = 1;

					//$(this).draggable("destroy");
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
	//汕
	$("#dragObject4").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (event, ui) {
			console.log(ui.position.left, ui.position.top);

		
			if (343 <= ui.position.left && ui.position.left <= 357) {
				console.log("variety right position!");
				if ( ui.position.top <= 381 && ui.position.top >= 369) {
					console.log("variety right postion!2");
					ansD_cnt = 1;

					//$(this).draggable("destroy");
				}
				else {
					if (ansD_cnt != 0) {
					ansD_cnt = ansD_cnt - 1;
					}

				}
			}
			else {
				if (ansD_cnt != 0) {
				ansD_cnt = ansD_cnt - 1;
				}

			}

		}
	});
	//溪
	$("#dragObject5").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (e,ui) {
			
		// var parentOffset = $("#image1_wrapper").parent().offset();
        // console.log(parentOffset);
        // var relX = e.pageX - parentOffset.left ;
        // var relY = e.pageY - parentOffset.top;
        	// console.log(relX, relY);
		console.log(ui.position.left, ui.position.top);
		if (212 <= ui.position.left && ui.position.left <= 226) {
				console.log("variety right position!");
				if ( ui.position.top <= 293 && ui.position.top >= 280) {
					console.log("variety right postion!2");
					ansE_cnt = 1;

				}
				else {
					if (ansE_cnt != 0) {
					ansE_cnt = ansE_cnt - 1;
					}

				}
			}
			else {
				if (ansE_cnt != 0) {
				ansE_cnt = ansE_cnt - 1;
				}

			}

		}
	});
	//山
	$("#dragObject6").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (event, ui) {
			console.log(ui.position.left, ui.position.top);
			if (-26 <= ui.position.left && ui.position.left <= -13) {
				console.log("variety right position!");
				if ( ui.position.top <= 82 && ui.position.top >= 70) {
					console.log("variety right postion!2");
					ansF_cnt = 1;

				}
				else {
					if (ansF_cnt != 0) {
					ansF_cnt = ansF_cnt - 1;
					}

				}
			}
			else {
				if (ansF_cnt != 0) {
				ansF_cnt = ansF_cnt - 1;
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
  	 case 1://墩
		  	// if( leftInt >=42 && leftInt <=62){
		  	// 	if( topInt >=95 && topInt <=118 ){
		  	// 		// CDFlag = true;
		  	// 		ansA_cnt=0;//A欄,0分
		  	// 	}
		  	// }
		  	if( leftInt >=500 && leftInt <=512){
		  		if( topInt >=160 && topInt <=172 ){
		  			// CDFlag = true;
		  			ansB_cnt=0;//B欄,0分
		  		}
		  	}
		  	if( leftInt >=544 && leftInt <=556){
		  		if( topInt >=258 && topInt <=271 ){
		  			ansC_cnt=0;//C欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=427 && leftInt <=439){
		  		if( topInt >=365 && topInt <=377 ){
		  			ansD_cnt=0;//D欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=382 && leftInt <=395){
		  		if( topInt >=277 && topInt <=290 ){
		  			ansE_cnt=0;//E欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=236 && leftInt <=247){
		  		if( topInt >=67 && topInt <=78 ){
		  			ansF_cnt=0;//F欄答案不對,0分
		  		}
		  	}
		  break;
  	 case 2://澳
		  	if( leftInt >=39 && leftInt <=51){
		  		if( topInt >=213 && topInt <=225 ){
		  			// ABFlag = true;//A欄,0分
		  			ansA_cnt=0;
		  		}
		  	}
		  	// if( leftInt >=130 && leftInt <=150){
		  	// 	if( topInt >=11 && topInt <=33 ){
		  	// 		ansB_cnt=0;//B欄,0分
		  	// 		// alert('ansD_cnt='+ansD_cnt);
		  	// 	}
		  	// }
		  	if( leftInt >=458 && leftInt <=471){
		  		if( topInt >=258 && topInt <=272 ){
		  			ansC_cnt=0;//C欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=342 && leftInt <=354){
		  		if( topInt >=365&& topInt <=377 ){
		  			ansD_cnt=0;//D欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=297 && leftInt <=310){
		  		if( topInt >=277 && topInt <=290 ){
		  			ansE_cnt=0;//E欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=151 && leftInt <=162){
		  		if( topInt >=67 && topInt <=78 ){
		  			ansF_cnt=0;//F欄答案不對,0分
		  		}
		  	}
		  break;
  	 case 3://角
		  	if( leftInt >=-51 && leftInt <=-37){
		  		if( topInt >=213 && topInt <=225 ){
		  			// ABFlag = true;//A欄,0分
		  			ansA_cnt=0;
		  		}
		  	}
		  	if( leftInt >=326 && leftInt <=338){
		  		if( topInt >=160 && topInt <=172 ){
		  			ansB_cnt=0;//B欄,0分
		  		}
		  	}
		  	// if( leftInt >=458 && leftInt <=471){
		  	// 	if( topInt >=258 && topInt <=272 ){
		  	// 		ansC_cnt=0;//C欄答案不對,0分
		  	// 		// alert('ansD_cnt='+ansD_cnt);
		  	// 	}
		  	// }
		  	if( leftInt >=253 && leftInt <=266){
		  		if( topInt >=365&& topInt <=377 ){
		  			ansD_cnt=0;//D欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=208 && leftInt <=221){
		  		if( topInt >=277 && topInt <=290 ){
		  			ansE_cnt=0;//E欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=62 && leftInt <=73){
		  		if( topInt >=67 && topInt <=78 ){
		  			ansF_cnt=0;//F欄答案不對,0分
		  		}
		  	}
		  break;
  	 case 4://汕
		  	if( leftInt >=40 && leftInt <=54){
		  		if( topInt >=217 && topInt <=229 ){
		  			// ABFlag = true;//A欄,0分
		  			ansA_cnt=0;
		  		}
		  	}
		  	if( leftInt >=417 && leftInt <=430){
		  		if( topInt >=164 && topInt <=172 ){
		  			ansB_cnt=0;//B欄,0分
		  		}
		  	}
		  	if( leftInt >=460 && leftInt <=473){
		  		if( topInt >=263 && topInt <=275 ){
		  			ansC_cnt=0;//C欄答案不對,0分
		  		}
		  	}
		  	// if( leftInt >=342 && leftInt <=354){
		  	// 	if( topInt >=365&& topInt <=377 ){
		  	// 		ansD_cnt=0;//D欄答案不對,0分
		  	// 	}
		  	// }
		  	if( leftInt >=299 && leftInt <=313){
		  		if( topInt >=282 && topInt <=294 ){
		  			ansE_cnt=0;//E欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=152 && leftInt <=164){
		  		if( topInt >=71 && topInt <=82 ){
		  			ansF_cnt=0;//F欄答案不對,0分
		  		}
		  	}
		  break;
  	 case 5://溪
		  	if( leftInt >=-46 && leftInt <=-32){
		  		if( topInt >=216 && topInt <=229 ){
		  			ABFlag = true;//A欄,0分
		  			ansA_cnt=0;
		  		}
		  	}
		  	if( leftInt >=330 && leftInt <=343){
		  		if( topInt >=164 && topInt <=176 ){
		  			ansB_cnt=0;//B欄,0分
		  		}
		  	}
		  	if( leftInt >=373 && leftInt <=387){
		  		if( topInt >=262 && topInt <=275 ){
		  			ansC_cnt=0;//C欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=257 && leftInt <=271){
		  		if( topInt >=369&& topInt <=380 ){
		  			ansD_cnt=0;//D欄答案不對,0分
		  		}
		  	}
		  	// if( leftInt >=297 && leftInt <=310){
		  	// 	if( topInt >=277 && topInt <=290 ){
		  	// 		ansE_cnt=0;//E欄答案不對,0分
		  	// 	}
		  	// }
		  	if( leftInt >=66 && leftInt <=78){
		  		if( topInt >=71 && topInt <=83 ){
		  			ansF_cnt=0;//F欄答案不對,0分
		  		}
		  	}
		  break;
  	 case 6://山
		  	if( leftInt >=-137 && leftInt <=-123){
		  		if( topInt >=216 && topInt <=230 ){
		  			// ABFlag = true;//A欄,0分
		  			ansA_cnt=0;
		  		}
		  	}
		  	if( leftInt >=239 && leftInt <=252){
		  		if( topInt >=164 && topInt <=176 ){
		  			ansB_cnt=0;//B欄,0分
		  		}
		  	}
		  	if( leftInt >=283 && leftInt <=295){
		  		if( topInt >=262 && topInt <=274 ){
		  			ansC_cnt=0;//C欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=166 && leftInt <=179){
		  		if( topInt >=369&& topInt <=381 ){
		  			ansD_cnt=0;//D欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=121 && leftInt <=135){
		  		if( topInt >=281 && topInt <=293 ){
		  			ansE_cnt=0;//E欄答案不對,0分
		  		}
		  	}
		  	// if( leftInt >=151 && leftInt <=162){
		  	// 	if( topInt >=67 && topInt <=78 ){
		  	// 		ansF_cnt=0;//F欄答案不對,0分
		  	// 	}
		  	// }
		  break;

		  }
		}

	
		
		// alert ('c='+ansE_cnt +',D='+ansF_cnt +',E='+ansE_cnt);
		//TOTAL=A答對給1分+B答對給1分+E,F都答對才給1分
		// score = ansAB_cnt + ansCD_cnt + ansEF_cnt;
		score = ansA_cnt + ansB_cnt + ansC_cnt + ansD_cnt + ansE_cnt + ansF_cnt;
    // 6個全對：4分。
		// 4~5個對：3分。
		// 2~3個對：2分。
		// 1個對：1分。
		// 全錯：0分。
		if(score > 1){
			if((score == 2) || (score ==3) ){
				score=2;
			}
			if((score == 4) || (score ==5) ){
				score=3;
			}
			if(score == 6){
				score=4;
			}
		}
    let level = 0;
	    if(score > 0){
	      if(score > 1){
	        if(score > 2){
	        	if(score > 3){level = 4;}
	        	else{level = 3;}
	        }
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

