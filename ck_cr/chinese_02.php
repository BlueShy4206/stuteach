
	<div class="question">
		<div class="container">
			<p></p>
			<p class="q_text">
				下列語詞中有四個貶義詞，請將正確答案拖曳至答案區虛框格子內。(不必依序填入)
			</p>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col col-md-12">
				<div class="dragArea" id="dragArea">
					<p>答案區</p>
					<div class="image1_wrapper" id="image1_wrapper">
						<img src="ck_cr/chinese_02/title_01.png">
					</div>
					<p></p>
					<p style="font-size: 16;">操作選項</p>

					<div class="dragWrapper">
						<div class="dragArea0" id="dragArea0">
							<div class="dragObject1 dragAll" id="dragObject1" style="position: absolute; margin: 3px 6px;">
								<img src="ck_cr/chinese_02/n_opt_01.png">
							</div>
							<div class="dragObject2 dragAll" id="dragObject2" style="position: absolute; margin: 88px 95px;">
								<img src="ck_cr/chinese_02/n_opt_02.png">
							</div>
							<div class="dragObject3 dragAll" id="dragObject3" style="position: absolute; margin: 176px 6px;">
								<img src="ck_cr/chinese_02/n_opt_03.png">
							</div>
						</div>
						<div class="dragArea1" id="dragArea1">
							<div class="dragObject4 dragAll" id="dragObject4" style="position: absolute; margin: 86px 84px;">
								<img src="ck_cr/chinese_02/n_opt_04.png">
							</div>
							<div class="dragObject5 dragAll" id="dragObject5" style="position: absolute; margin: 172px 0px;">
								<img src="ck_cr/chinese_02/n_opt_05.png">
							</div>
							<div class="dragObject6 dragAll" id="dragObject6" style="position: absolute;">
								<img src="ck_cr/chinese_02/n_opt_06.png">
							</div>
						</div>
				
						<div class="dragArea2" id="dragArea2">	
							<div class="dragObject7 dragAll" id="dragObject7" style="position: absolute; margin: 3px 6px;">
								<img src="ck_cr/chinese_02/n_opt_07.png">
							</div>
							<div class="dragObject8 dragAll" id="dragObject8" style="position: absolute; margin: 180px 6px;">
								<img src="ck_cr/chinese_02/n_opt_08.png">
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
		width: 486px;
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
		max-width: 3626px;
		max-height: 1768px;
		margin: 0px;
	}

	.image1_wrapper img {
		max-width: 48%;
		max-height: 90%;
	}

	.dragObject1 {
		display: block;
	    /*width: 70px;
    	height: 87px;*/
	    width: 120px;
	    height: 57px;
	}

	.dragObject1 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject2 {
		display: block;
	    width: 90px;
	    height: 55px;
	}

	.dragObject2 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject3 {
		display: block;
	    width: 90px;
	    height: 55px;
	}

	.dragObject3 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject4 {
		display: block; 
	    width: 90px;
	    height: 55px;
	}

	.dragObject4 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject5 {
		display: block; 
	    width: 90px;
	    height: 55px;
	}

	.dragObject5 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject6 {
		display: block;
	    width: 120px;
	    height: 57px;
	}

	.dragObject6 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject7 {
		display: block;
		width: 90px;
	    height: 55px;
	}

	.dragObject7 img {
		max-width: 100%;
		max-height: 100%;
	}

	.dragObject8 {
		display: block;
		width: 90px;
	    height: 55px;
	}

	.dragObject8 img {
		max-width: 100%;
		max-height: 100%;
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
	let ansA_cnt=0, ansB_cnt=0, ansC_cnt=0, ansD_cnt=0;

	//拖拉event
	//A:Y
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
		if (-7 <= ui.position.left && ui.position.left <= 421) {
				console.log("right position!");
				if (  ui.position.top <= 241 && ui.position.top >= 38) {
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
	//B
	$("#dragObject2").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (e,ui) {
			
		console.log(ui.position.left, ui.position.top);

		}
	});
	//C
	$("#dragObject3").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (e,ui) {
			
		console.log(ui.position.left, ui.position.top);
		}
	});
	//D
	$("#dragObject4").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (event, ui) {
			console.log(ui.position.left, ui.position.top);

		}

	});
	//E:Y
	$("#dragObject5").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (e,ui) {
			
		console.log(ui.position.left, ui.position.top);
		if (3 <= ui.position.left && ui.position.left <= 456) {
				console.log("variety right position!");
				if ( ui.position.top <= 76 && ui.position.top >= -130) {
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
	//F:Y
	$("#dragObject6").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (event, ui) {
			console.log(ui.position.left, ui.position.top);
			if (3 <= ui.position.left && ui.position.left <= 428) {
				console.log("variety right position!");
				if ( ui.position.top <= 244 && ui.position.top >= 43) {
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
	//G:Y
	$("#dragObject7").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (event, ui) {
			console.log(ui.position.left, ui.position.top);
			if (-3 <= ui.position.left && ui.position.left <= 450) {
				console.log("right position!");
				if ( ui.position.top >= 40 && ui.position.top <= 244) {
					console.log("right postion!2");
					ansD_cnt = 1;

					// $(this).draggable("destroy");
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
	//H
	$("#dragObject8").draggable({
		drag: function (event, ui) {
			// console.log(ui.position.left, ui.position.top);
		},
		stop: function (event, ui) {
			console.log(ui.position.left, ui.position.top);
			
		}
	});
	function submit() {
		let ansX_cnt=0;
		let leftInt=0 ,topInt=0 ;
		let idName="";
		let ansA_cnt=0, ansB_cnt=0, ansC_cnt=0, ansD_cnt=0;

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
  	 case 1://A
		  	if( leftInt >=86 && leftInt <=136){
		  		if( topInt >=98 && topInt <=124 ){
		  			ansA_cnt+=1;//A欄,1分
		  		}
		  	}
		  	if( leftInt >=282 && leftInt <=331){
		  		if( topInt >=97 && topInt <=124 ){
		  			ansB_cnt+=1;//B欄,1分
		  		}
		  	}
		  	if( leftInt >=87 && leftInt <=136){
		  		if( topInt >=202 && topInt <=228 ){
		  			ansC_cnt+=1;//C欄,1分
		  		}
		  	}
		  	if( leftInt >=281 && leftInt <=330){
		  		if( topInt >=201 && topInt <=228 ){
		  			ansD_cnt+=1;//D欄,1分
		  		}
		  	}
		  break;
  	 case 2://B
		  	if( leftInt >=-12 && leftInt <=75){
		  		if( topInt >=11 && topInt <=41 ){
		  			ansA_cnt-=1;//A欄,0分
		  		}
		  	}
		  	if( leftInt >=194 && leftInt <=271){
		  		if( topInt >=12 && topInt <=42 ){
		  			ansB_cnt-=1;//B欄,0分
		  		}
		  	}
		  	if( leftInt >=-1 && leftInt <=75){
		  		if( topInt >=116 && topInt <=146 ){
		  			ansC_cnt-=1;//C欄,0分
		  		}
		  	}
		  	if( leftInt >=194 && leftInt <=271){
		  		if( topInt >=117 && topInt <=147 ){
		  			ansD_cnt-=1;//D欄,0分
		  		}
		  	}
		  break;
  	 case 3://C
		  	if( leftInt >=87 && leftInt <=167){
		  		if( topInt >=-76 && topInt <=-46 ){
		  			ansA_cnt-=1;//A欄,0分
		  		}
		  	}
		  	if( leftInt >=283 && leftInt <=362){
		  		if( topInt >=-76 && topInt <=-46 ){
		  			ansB_cnt-=1;//B欄,0分
		  		}
		  	}
		  	if( leftInt >=88 && leftInt <=165){
		  		if( topInt >=29 && topInt <=58 ){
		  			ansC_cnt-=1;//C欄,0分
		  		}
		  	}
		  	if( leftInt >=283 && leftInt <=359){
		  		if( topInt >=30 && topInt <=59 ){
		  			ansD_cnt-=1;//D欄,0分
		  		}
		  	}
		  break;
  	 case 4://D
		  	if( leftInt >=9 && leftInt <=88){
		  		if( topInt >=14 && topInt <=42 ){
		  			ansA_cnt-=1;//A欄,0分
		  		}
		  	}
		  	if( leftInt >=205 && leftInt <=283){
		  		if( topInt >=13 && topInt <=44 ){
		  			ansB_cnt-=1;//B欄,0分
		  		}
		  	}
		  	if( leftInt >=10 && leftInt <=87){
		  		if( topInt >=119 && topInt <=148 ){
		  			ansC_cnt-=1;//C欄,0分
		  		}
		  	}
		  	if( leftInt >=206 && leftInt <=284){
		  		if( topInt >=119 && topInt <=148 ){
		  			ansD_cnt-=1;//D欄,0分
		  		}
		  	}
		  break;
  	 case 5://E
		  	if( leftInt >=94 && leftInt <=173){
		  		if( topInt >=-73 && topInt <=-43 ){
		  			ansA_cnt+=1;//A欄,1分
		  		}
		  	}
		  	if( leftInt >=290 && leftInt <=367){
		  		if( topInt >=-72 && topInt <=-42 ){
		  			ansB_cnt+=1;//B欄,1分
		  		}
		  	}
		  	if( leftInt >=94 && leftInt <=172){
		  		if( topInt >=32 && topInt <=63 ){
		  			ansC_cnt+=1;//C欄,1分
		  		}
		  	}
		  	if( leftInt >=290 && leftInt <=367){
		  		if( topInt >=32 && topInt <=63 ){
		  			ansD_cnt+=1;//D欄,1分
		  		}
		  	}
		  break;
  	 case 6://F
		  	if( leftInt >=92 && leftInt <=143){
		  		if( topInt >=99 && topInt <=128 ){
		  			ansA_cnt+=1;//A欄,1分
		  		}
		  	}
		  	if( leftInt >=289 && leftInt <=339){
		  		if( topInt >=99 && topInt <=128 ){
		  			ansB_cnt+=1;//B欄,1分
		  		}
		  	}
		  	if( leftInt >=95 && leftInt <=145){
		  		if( topInt >=204 && topInt <=232 ){
		  			ansC_cnt+=1;//C欄,1分
		  		}
		  	}
		  	if( leftInt >=290 && leftInt <=337){
		  		if( topInt >=204 && topInt <=232 ){
		  			ansD_cnt+=1;//D欄,1分
		  		}
		  	}
		  break;
  	 case 7://G
		  	if( leftInt >=87 && leftInt <=166){
		  		if( topInt >=96 && topInt <=126 ){
		  			ansA_cnt+=1;//A欄,1分
		  		}
		  	}
		  	if( leftInt >=283 && leftInt <=362){
		  		if( topInt >=96 && topInt <=126 ){
		  			ansB_cnt+=1;//B欄,1分
		  		}
		  	}
		  	if( leftInt >=87 && leftInt <=165){
		  		if( topInt >=201 && topInt <=230 ){
		  			ansC_cnt+=1;//C欄,1分
		  		}
		  	}
		  	if( leftInt >=284 && leftInt <=361){
		  		if( topInt >=201 && topInt <=231 ){
		  			ansD_cnt+=1;//D欄,1分
		  		}
		  	}
		  break;
  	 case 8://H
		  	if( leftInt >=88 && leftInt <=165){
		  		if( topInt >=-81 && topInt <=-51 ){
		  			ansA_cnt-=1;//A欄,0分
		  		}
		  	}
		  	if( leftInt >=283 && leftInt <=361){
		  		if( topInt >=-81 && topInt <=-51 ){
		  			ansB_cnt-=1;//B欄,0分
		  		}
		  	}
		  	if( leftInt >=88 && leftInt <=167){
		  		if( topInt >=25 && topInt <=54 ){
		  			ansC_cnt-=1;//C欄,0分
		  		}
		  	}
		  	if( leftInt >=283 && leftInt <=361){
		  		if( topInt >=25 && topInt <=54 ){
		  			ansD_cnt-=1;//D欄,0分
		  		}
		  	}
		  break;
  	 

		  }
		}

		if(ansA_cnt != 1){
			ansA_cnt = 0;
		}
		if(ansB_cnt != 1){
			ansB_cnt = 0;
		}
		if(ansC_cnt != 1){
			ansC_cnt = 0;
		}
		if(ansD_cnt != 1){
			ansD_cnt = 0;
		}


		
		//TOTAL=A答對給1分+B答對給1分+E,F都答對才給1分
		score = ansA_cnt + ansB_cnt + ansC_cnt + ansD_cnt ;
		if( score < 0){ score = 0;}
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
