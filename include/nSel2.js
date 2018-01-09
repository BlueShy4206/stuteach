/* script 不用動 */
var selAry=[];

function selData( i ){
    selAry = i; 
    
  $('[id^=LV]').each( function(){    
      /*將 select 的 id 拆解開來*/
      var LVi = $(this).attr('id');
      var LVs = LVi.split('LV');
      var LVj = LVs.join('');
             
      if( LVj==1 ){
        for( var key in selAry[LVj] ){
            $(this).append( '<option value="'+key+'" >'+selAry[LVj][key]+'</option>' );
        }
      }else{
        $(this).append( '<option value="'+0+'" >'+selAry[LVj][0][0]+'</option>' );
      }   
  } ); 
}

function chaSel( i ){ 
    /*將 select 的 id 拆解開來，偵測使用者選擇第幾個選項*/
    var LVi = $(i).attr('id');
    var LVs = LVi.split('LV');
    var LVj = LVs.join(''); 
    var userSelVal='';   
    
    $('[id^=LV]').each( function(){    
        /*將 select 的 id 拆解開來*/
        var LVi2 = $(this).attr('id');
        var LVs2 = LVi2.split('LV');
        var LVj2 = LVs2.join(''); 
        /*偵測使用者選擇了甚麼值，(LVj2-1)代表上一個值*/
        if( LVj2 <=2 ){
            userSelVal = $('#LV'+(LVj2-1)+' option:selected').val();
        }else{
            userSelVal = userSelVal+$('#LV'+(LVj2-1)+' option:selected').val();
        }                                
        
        if( LVj2 <= LVj ) return;
                
        $('#'+LVi2+'  option').remove();
        for( var key in selAry[LVj2][userSelVal] ){
            $(this).append( '<option value="'+key+'" >'+selAry[LVj2][userSelVal][key]+'</option>' );
        }   
    } );    
       
}

$(window).load(function(){
    $('[id^=LV]').each( function(){    
        $(this).bind('change',function(){ chaSel( this ); } );    
    } ); 
});
/* script 不用動 */

/* html 部分
      <table align="center">
          <tr>
              <td align="center"><br><h3>测验选择</h3></td>
          </tr>
          <tr>
              <td align="center">            
                  <div id="e04"></div>
                  <!-- 要幾個選項，就新增幾個 select，id 前兩碼一定要是 LV -->
                  <select id="LV1" name="SelTest[]"></select>
                  <select id="LV2" name="SelTest[]"></select>
                  <select id="LV3" name="SelTest[]"></select>
                  <select id="LV4" name="SelTest[]"></select>                                    
              </td>
          </tr>
          <tr>
              <td align="center"><input type="submit" value="選擇單元" >    </td>
          </tr>
      </table>

*/
/* php 部分
function FunSelTest(){
    global $dbh;

   

	// --firstSelAry 要改
    $firstSelAry=array( '版本', '領域', '冊別', '單元名稱' );
    foreach( $firstSelAry as $key=>$val ){
        $sel[($key+1)][0][0]=urlencode($val);
        $selHtml .= '<select id="LV'.($key+1).'" name="SelTest[]"></select>';
    }
	
	 //撈出所有的CS_id
    $sql = ' SELECT cs_id FROM concept_info ORDER BY cs_id ';
    $re = $dbh->getAll($sql);
	foreach( $re as $j=>$val ){
	
        $cs_id=$val[cs_id];		
        $CS_info=explode_cs_id($cs_id);				
		$pid=(int)$CS_info[0];
		$sid=($CS_info[1]);
		$vid=($CS_info[2]);
		$uid=($CS_info[3]);
		$subject=id2subject($sid);
        
        //-- $sel 是三維陣列，要符合格式 
        // $sel[1][a] = ...
        // $sel[2][a][b] =...
        // $sel[3][ab][c] = ...
        // $sel[4][abc][d] = ...
        
		$sel[1][$pid]=urlencode(id2publisher($pid));
		$sel[2][$pid][$sid]=urlencode($subject);
		$sel[3][$pid.$sid][$vid]=urlencode(vol2grade($subject,$vid).'〈第'.$vid.'册〉');
		$sel[4][$pid.$sid.$vid][$uid]=urlencode('第'.$uid.'单元-'.id2csname($cs_id));

	}
    echo('
      //--改 action 
      <form method="post" action="modules.php?op=modload&name=itemSkill&file=baiFun&opt=itemMatrix" >
      <table align="center">
          <tr>
              <td align="center"><br><h3>單元选择</h3></td>
          </tr>
          <tr>
              <td align="center">            
                  <div id="e04"></div>
                  '.$selHtml.'                                    
              </td>
          </tr>
          <tr>
              <td align="center"><input type="submit" value="選擇單元" >    </td>
          </tr>
      </table>
      </form>          
    ');	
	$jsSelLv1 = urldecode(json_encode( $sel ));
    echo '<script> selData( '.$jsSelLv1.' ); </script>';
}
*/