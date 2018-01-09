<script language="javascript"> 	
error_n = new Array(5);
error_n[0] = '請選擇"試題反應理論"';
error_n[1] = '請選擇"能力估計法"';
error_n[2] = '請選擇"選題法"';
error_n[3] = '請選擇"曝光率控制"';
error_n[4] = '請選擇"更新曝光的人數"';

y=0;
</script> 
<?php
for($i=0;$i<3;$i++)
{
  if ($mirt_c[$i]==0)
  {
    ?>
    <script language="javascript"> 	      
      alert(error_n[y]);
      y = y+1;
    </script>    
    <?PHP
  }else
  {
    ?>
    <script language="javascript"> 	
      y = y+1;
    </script>    
    <?PHP  
  }      
}
if ($mirt_c[3]==0)
{
    ?>
    <script language="javascript"> 	      
      alert(error_n[3]);
      alert(error_n[4]);
    </script>    
    <?PHP
}else
{
    if ($mirt_c[4]==0)
    {
      ?>
      <script language="javascript"> 
        alert(error_n[4]);
      </script>    
      <?PHP
    }  
}    
$class[0] = $_REQUEST['city_code'];
$class[1] = $sid;
$class[2] = $gid;
$class[3] = $cid;
EP2class($class);
?>
