<?php
##################   CourseData   取班級資料物件     ##############################
require_once "include/adp_API.php";

class CourseData {

	private $InUserID; 
	private $InUserName;
	private $InUserLevel;

	function __construct($CourseID) {
		global $dbh;
		$sql="select * from course WHERE course_id='$CourseID'";
		//debug_msg("第".__LINE__."行 sql ", $sql);
		$result =$dbh->query($sql);
		while ($row=$result->fetchRow()){
			$this->CourseID=$CourseID;
			$this->ShortName=$row[name];
			$this->Year=$row[year];
			$this->Seme=$row[seme];
			//$this->FullName=$row[year]."學年度第".$row[seme]."學期-".$row[name];
			//$this->YearSeme=$row[year]."學年度第".$row[seme]."學期";
			$this->FullName=$row[year]."學年度-".$row[name];
			$this->YearSeme=$row[year]."學年度";
		}
	}


	function CourseMember() {
		global $dbh;

		$sql="SELECT a.user_id, b.uid, b.uname, c.access_level, b.organization_id FROM user_course a, user_info b, user_status c WHERE a.course_id ='".$this->CourseID."' AND a.user_id=b.user_id AND a.user_id=c.user_id ORDER BY a.user_id";
		//debug_msg(__LINE__."行  sql", $sql);
		$result = $dbh->query($sql);
		$IN=0;
		while ($data = $result->fetchRow()) {
			$this->InUserID[$IN]=$data['user_id'];
			$this->InUserName[$data['user_id']]=$data['uname'];
			$this->InUserLevel[$data['user_id']]=$data['access_level'];
			$this->member[$IN] = $data['uid']._SPLIT_SYMBOL.$data['user_id']._SPLIT_SYMBOL.$data['uname']._SPLIT_SYMBOL.$data['access_level']._SPLIT_SYMBOL.$data['organization_id'];
			$IN++;
		}

	}

	function getMemberID(){
		return $this->InUserID;
	}

	function getMemberName(){
		return $this->InUserName;
	}

	function getMemberLevel(){
		return $this->InUserLevel;
	}

	function getMember(){
		return $this->member;
	}

}

?>
