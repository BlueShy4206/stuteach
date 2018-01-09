
	<div class="question">
		<div class="container">
			<p></p>
			<p class="q_text">
				注音符號大部份取自古文簡筆漢字，請問下列符號，取自哪一個國字？請將國字拉至符號下方的虛框格子內。
			</p>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col col-md-12">
				<div class="dragArea" id="dragArea">
					<p></p>
					<div class="image1_wrapper" id="image1_wrapper">
						<img src="ck_cr/chinese_01/title_01.png">
					</div>
					<p></p>
					<p style="font-size: 16;">操作選項</p>

					<div class="dragWrapper">
						<div class="dragArea0" id="dragArea0">
							<div class="dragObject1 dragAll" id="dragObject1" style="position: absolute; margin: 3px 6px;">
								<img src="ck_cr/chinese_01/n_opt_01.png">
							</div>
							<div class="dragObject2 dragAll" id="dragObject2" style="position: absolute; margin: 88px 95px;">
								<img src="ck_cr/chinese_01/n_opt_02.png">
							</div>
							<div class="dragObject3 dragAll" id="dragObject3" style="position: absolute; margin: 176px 0px;">
								<img src="ck_cr/chinese_01/n_opt_03.png">
							</div>
						</div>
						<div class="dragArea1" id="dragArea1">
							<div class="dragObject4 dragAll" id="dragObject4" style="position: absolute; margin: 86px 84px;">
								<img src="ck_cr/chinese_01/n_opt_04.png">
							</div>
							<div class="dragObject5 dragAll" id="dragObject5" style="position: absolute; margin: 172px 0px;">
								<img src="ck_cr/chinese_01/n_opt_05.png">
							</div>
							<div class="dragObject6 dragAll" id="dragObject6" style="position: absolute;">
								<img src="ck_cr/chinese_01/n_opt_06.png">
							</div>
						</div>
				
						<div class="dragArea2" id="dragArea2">	
							<div class="dragObject7 dragAll" id="dragObject7" style="position: absolute; margin: 3px 6px;">
								<img src="ck_cr/chinese_01/n_opt_07.png">
							</div>
							<div class="dragObject8 dragAll" id="dragObject8" style="position: absolute; margin: 180px 6px;">
								<img src="ck_cr/chinese_01/n_opt_08.png">
							</div>
							<div class="dragObject9 dragAll" id="dragObject9" style="position: absolute; margin: 90px 82px;">
								<img src="ck_cr/chinese_01/n_opt_09.png">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

	</div>
</body>
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
    	height: 277px;
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
		max-width: 2942px;
		max-height: 1702px;
		margin: 0px;
	}

	.image1_wrapper img {
		max-width: 65%;
		max-height: 90%;
	}

	.dragObject1 {
		display: block;
	    /*width: 70px;
    	height: 87px;*/
	    width: 90px;
	    height: 90px;
	}

	.dragObject1 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject2 {
		display: block;
	    width: 90px;
	    height: 90px;
	}

	.dragObject2 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject3 {
		display: block;
	    width: 90px;
	    height: 90px;
	}

	.dragObject3 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject4 {
		display: block; 
	    width: 90px;
	    height: 90px;
	}

	.dragObject4 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject5 {
		display: block; 
	    width: 90px;
	    height: 90px;
	}

	.dragObject5 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject6 {
		display: block;
	    width: 90px;
	    height: 90px;
	}

	.dragObject6 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject7 {
		display: block;
		width: 90px;
	    height: 90px;
	}

	.dragObject7 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject8 {
		display: block;
		width: 90px;
	    height: 90px;
	}

	.dragObject8 img {
		max-width: 100%;
		max-height: 100%;
	}
	.dragObject9 {
		display: block;
	    width: 90px;
	    height: 90px;
	}

	.dragObject9 img {
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

	//拖拉event
	//包
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
        	// leftInt = ui.position.left;
        	// topInt = ui.position.top;
			// if( leftInt >=42 && leftInt <=62){
		 //  		if( topInt >=95 && topInt <=118 ){
		 //  			document.getElementById('dragObject1').style.cssText="position: absolute; margin: 3px 6px; left: 51px; top: 104px;";
		 //  			// document.getElementById('dragObject1').style.top="104px";
		 //  			// document.getElementById('dragObject1').style.left="51px";
		 //  			// CDFlag = true;
		 //  			ansA_cnt=0;//A欄,0分
		 //  		}
		 //  	}
		}
	});
	//刀
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
		if (-47 <= ui.position.left && ui.position.left <= -27) {
				console.log("right position!");
				if (  ui.position.top <= 32 && ui.position.top >= 12) {
					console.log("right postion!2");
					ansA_cnt = 1;

					//$(this).draggable("destroy");
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
	//力
	$("#dragObject3").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (e,ui) {
			
		console.log(ui.position.left, ui.position.top);
		}
	});
	//山
	$("#dragObject4").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (event, ui) {
			console.log(ui.position.left, ui.position.top);
		}
	});
	//之
	$("#dragObject5").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (e,ui) {
			
		console.log(ui.position.left, ui.position.top);
		if (225 <= ui.position.left && ui.position.left <= 247) {
				console.log("variety right position!");
				if ( ui.position.top <= -51 && ui.position.top >= -73) {
					console.log("variety right postion!2");
					ansB_cnt = 1;

					//$(this).draggable("destroy");
				}
				else {
					if (ansB_cnt != 0) {
					ansB_cnt = ansB_cnt - 1;
					}

				}
			}
			else {
				if (ansB_cnt != 0) {
				ansB_cnt = ansB_cnt - 1;
				}

			}

		}
	});
	//七
	$("#dragObject6").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (event, ui) {
			console.log(ui.position.left, ui.position.top);
			if (406 <= ui.position.left && ui.position.left <= 428) {
				console.log("variety right position!");
				if ( ui.position.top <= 121 && ui.position.top >= 100) {
					console.log("variety right postion!2");
					ansC_cnt = 1;

					//$(this).draggable("destroy");
				}
				else {
					if (ansC_cnt != 0) {
					ansC_cnt = ansC_cnt - 1;
					}

				}
			}
			else {
				if (ansC_cnt != 0) {
				ansC_cnt = ansC_cnt - 1;
				}

			}

		}
	});
	//育
	$("#dragObject7").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (event, ui) {
			console.log(ui.position.left, ui.position.top);

		}
	});
	//凶
	$("#dragObject8").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (event, ui) {
			console.log(ui.position.left, ui.position.top);
			
		}
	});
	//五
	$("#dragObject9").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (event, ui) {
			console.log(ui.position.left, ui.position.top);
			if (503 <= ui.position.left && ui.position.left <= 526) {
				console.log("right position!");
				if ( ui.position.top >= 9 && ui.position.top <= 31) {
					console.log("right postion!2");
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
  	 case 1://包
		  	if( leftInt >=42 && leftInt <=62){
		  		if( topInt >=95 && topInt <=118 ){
		  			// CDFlag = true;
		  			ansA_cnt=0;//A欄,0分
		  		}
		  	}
		  	if( leftInt >=219 && leftInt <=241){
		  		if( topInt >=95 && topInt <=118 ){
		  			// CDFlag = true;
		  			ansB_cnt=0;//B欄,0分
		  		}
		  	}
		  	if( leftInt >=399 && leftInt <=420){
		  		if( topInt >=95 && topInt <=118 ){
		  			ansC_cnt=0;//C欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=579 && leftInt <=601){
		  		if( topInt >=95 && topInt <=118 ){
		  			ansD_cnt=0;//D欄答案不對,0分
		  		}
		  	}
		  break;
  	 case 2://刀
		  	// if( leftInt >=68 && leftInt <=133){
		  	// 	if( topInt >=-31 && topInt <=15 ){
		  	// 		ABFlag = true;//A欄,0分
		  	// 		ansA_cnt=0;
		  	// 		// alert('ansD_cnt='+ansD_cnt);
		  	// 	}
		  	// }
		  	if( leftInt >=130 && leftInt <=150){
		  		if( topInt >=11 && topInt <=33 ){
		  			ansB_cnt=0;//B欄,0分
		  			// alert('ansD_cnt='+ansD_cnt);
		  		}
		  	}
		  	if( leftInt >=310 && leftInt <=331){
		  		if( topInt >=11 && topInt <=33 ){
		  			ansC_cnt=0;//C欄答案不對,0分
		  			// alert('ansD_cnt='+ansD_cnt);
		  		}
		  	}
		  	if( leftInt >=490 && leftInt <=511){
		  		if( topInt >=11&& topInt <=33 ){
		  			ansD_cnt=0;//D欄答案不對,0分
		  			// alert('ansD_cnt='+ansD_cnt);
		  		}
		  	}
		  break;
  	 case 3://力
		  	if( leftInt >=47 && leftInt <=69){
		  		if( topInt >=-77 && topInt <=-55 ){
		  			ABFlag = true;//A欄,0分
		  			ansA_cnt=0;
		  			// alert('ansD_cnt='+ansD_cnt);
		  		}
		  	}
		  	if( leftInt >=226 && leftInt <=246){
		  		if( topInt >=-77 && topInt <=-55 ){
		  			ansB_cnt=0;//B欄,0分
		  			// alert('ansD_cnt='+ansD_cnt);
		  		}
		  	}
		  	if( leftInt >=406 && leftInt <=427){
		  		if( topInt >=-77 && topInt <=-55 ){
		  			ansC_cnt=0;//C欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=585 && leftInt <=607){
		  		if( topInt >=-77 && topInt <=-55 ){
		  			ansD_cnt=0;//D欄答案不對,0分
		  		}
		  	}
		  break;
  	 case 4://山
		  	if( leftInt >=-37 && leftInt <=-14){
		  		if( topInt >=13 && topInt <=35 ){
		  			ABFlag = true;//A欄答案不對,0分
		  			ansA_cnt=0;
		  		}
		  	}
		  	if( leftInt >=140 && leftInt <=164){
		  		if( topInt >=13 && topInt <=35 ){
		  			ansB_cnt=0;//B欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=321 && leftInt <=343){
		  		if( topInt >=13 && topInt <=35 ){
		  			ansC_cnt=0;//C欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=503 && leftInt <=523){
		  		if( topInt >=13 && topInt <=35 ){
		  			ansD_cnt=0;//D欄答案不對,0分
		  		}
		  	}
		  break;
  	 case 5://之
		  	if( leftInt >=46 && leftInt <=67){
		  		if( topInt >=-73 && topInt <=-51 ){
		  			ABFlag = true;//A欄,0分
		  			ansA_cnt=0;
		  			// alert('ansD_cnt='+ansD_cnt);
		  		}
		  	}
		  	// if( leftInt >=165 && leftInt <=227){
		  	// 	if( topInt >=62 && topInt <=138 ){
		  	// 		ABFlag = true;//B欄,0分
		  	// 		ansB_cnt=0;
		  	// 		// alert('ansD_cnt='+ansD_cnt);
		  	// 	}
		  	// }
		  	if( leftInt >=406 && leftInt <=427){
		  		if( topInt >=-73 && topInt <=-51 ){
		  			ansC_cnt=0;//C欄答案不對,0分
		  		}
		  	}
		  	if( leftInt >=586 && leftInt <=607){
		  		if( topInt >=-73 && topInt <=-51 ){
		  			ansD_cnt=0;//D欄答案不對,0分
		  		}
		  	}
		  break;
  	 case 6://七
		  	if( leftInt >=46 && leftInt <=69){
		  		if( topInt >=99 && topInt <=121 ){
		  			CDFlag = true;//A欄,0分
		  			ansA_cnt=0;
		  		}
		  	}
		  	if( leftInt >=225 && leftInt <=247){
		  		if( topInt >=99 && topInt <=121 ){
		  			ansB_cnt=0;//B欄,0分
		  		}
		  	}
		  	// if( leftInt >=406 && leftInt <=427){
		  	// 	if( topInt >=-73 && topInt <=-51 ){
		  	// 		ansC_cnt=0;//C欄答案不對,0分
		  	// 	}
		  	// }
		  	if( leftInt >=586 && leftInt <=607){
		  		if( topInt >=99 && topInt <=121 ){
		  			ansD_cnt=0;//D欄答案不對,0分
		  		}
		  	}
		  break;
  	 case 7://育
		  	if( leftInt >=42 && leftInt <=63){
		  		if( topInt >=96 && topInt <=119 ){
		  			ABFlag = true;//A欄,0分
		  			ansA_cnt=0;
		  			// alert('ansD_cnt='+ansD_cnt);
		  		}
		  	}
		  	if( leftInt >=219 && leftInt <=242){
		  		if( topInt >=96 && topInt <=119 ){
		  			ABFlag = true;//B欄,0分
		  			ansB_cnt=0;
		  			// alert('ansD_cnt='+ansD_cnt);
		  		}
		  	}
		  	if( leftInt >=400 && leftInt <=421){
		  		if( topInt >=96 && topInt <=119 ){
		  			ansC_cnt=0;//C欄答案不對,0分
		  		}
		  	}		  	
		  	if( leftInt >=579 && leftInt <=602){
		  		if( topInt >=96 && topInt <=119 ){
		  			ansD_cnt=0;//D欄答案不對,0分
		  		}
		  	}
		  break;
  	 case 8://凶
		  	if( leftInt >=41 && leftInt <=63){
		  		if( topInt >=-81 && topInt <=-59 ){
		  			ABFlag = true;//A欄,0分
		  			ansA_cnt=0;
		  			// alert('ansD_cnt='+ansD_cnt);
		  		}
		  	}
		  	if( leftInt >=219 && leftInt <=242){
		  		if( topInt >=-81 && topInt <=-59 ){
		  			// ABFlag = true;
		  			ansB_cnt=0;//B欄,0分
		  			// alert('ansD_cnt='+ansD_cnt);
		  		}
		  	}
		  	if( leftInt >=400 && leftInt <=421){
		  		if( topInt >=-81 && topInt <=-59 ){
		  			ansC_cnt=0;//C欄答案不對,0分
		  		}
		  	}		  	
		  	if( leftInt >=579 && leftInt <=602){
		  		if( topInt >=-81 && topInt <=-59 ){
		  			ansD_cnt=0;//D欄答案不對,0分
		  		}
		  	}
		  break;
  	 case 9://五
		  	if( leftInt >=-35 && leftInt <=-13){
		  		if( topInt >=9 && topInt <=31 ){
		  			ansA_cnt=0;//A欄,0分
		  			// alert('ansD_cnt='+ansD_cnt);
		  		}
		  	}
		  	if( leftInt >=143 && leftInt <=165){
		  		if( topInt >=9 && topInt <=31 ){
		  			// ABFlag = true;
		  			ansB_cnt=0;//B欄,0分
		  			// alert('ansD_cnt='+ansD_cnt);
		  		}
		  	}
		  	if( leftInt >=325 && leftInt <=346){
		  		if( topInt >=9 && topInt <=31 ){
		  			ansC_cnt=0;//C欄答案不對,0分
		  		}
		  	}
		  	// if( leftInt >=676 && leftInt <=720){
		  	// 	if( topInt >=-62 && topInt <=12 ){
		  	// 		CDFlag = true;//C欄答案不留白,0分
		  	// 		ansD_cnt=0;
		  	// 	}
		  	// }
		  break;

		  }
		}

		
		// alert ('c='+ansE_cnt +',D='+ansF_cnt +',E='+ansE_cnt);
		//TOTAL=A答對給1分+B答對給1分+E,F都答對才給1分
		// score = ansAB_cnt + ansCD_cnt + ansEF_cnt;
		score = ansA_cnt + ansB_cnt + ansC_cnt + ansD_cnt;
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
