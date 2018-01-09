var checkf=0;

// 檢查欄位是否為空資料
function checkname(this_value,it)
{
 if (checkf == 1) return; else checkf=1;
 if (this_value == "")
    {alert("您必須輸入姓名!!");
     it.focus();  // 將焦點轉回該欄位
    }
  checkf=0;
}

// 檢查下拉選單是否選空資料
function checkcombo(selectName,itemname,it)
{
 if (checkf == 1) return; else checkf=1;
 if (selectName == itemname)
    {alert("您必須選擇一個項目!!");
     it.focus();  // 將焦點轉回下拉選單
    }
 checkf=0;
}

// 限制至少輸入的字元數
function checkmininput(inputn,min,it)
{
 if (inputn == "") return;
 if (checkf == 1) return; else checkf=1;
 if (inputn < min)   // 少於 min 就不行
    {alert("至少要輸入 "+ min +" 個數字,請重新輸入!!");
     it.focus();  // 將焦點轉回該欄位
    }
 checkf=0;
}

// 檢查輸入數字範圍
function rangecheck(min,max,it)
{
 if (it.value == "") return;
 if (checkf == 1) return; else checkf=1;
 if ((it.value < min)||(it.value > max))
    {alert("輸入不正確,請重新輸入!!");
     it.focus();  // 將焦點轉回該欄位
    }
 checkf=0;
}

// 檢查電子郵件位址格式是否正確
function check_email(it)
{
 if (it.value == "") return;
 if (checkf == 1) return; else checkf=1;
 if (it.value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1)
    {alert("電子郵件位址格式不正確,請重新輸入!!");
     it.focus();  // 將焦點轉回該欄位
    }
 checkf=0;
}

function chk_data() {
 if ( coolsus.to_user.value == "" ) {
		alert("「收件人的帳號」不可為空白！");
		coolsus.to_user.focus();
	} else if ( coolsus.subject.value == "" ) {
		alert("請填入適當標題，謝謝！");
		coolsus.subject.focus();
	} else if ( coolsus.message.value == "" ) {
		alert("請輸入簡訊內容，謝謝！");
		coolsus.message.focus();
	} else  {
		return true;
	}
	return false;
}

function chk_data1() {
 if ( Edit.title.value == "" ) {
		alert("請輸入「標題」！");
		Edit.title.focus();
	} else if ( Edit.author.value == "" ) {
		alert("請輸入「作者姓名」！");
		Edit.author.focus();
	} else  {
		return true;
	}
	return false;
}

function CheckAll() {
	for (var i=0;i<document.prvmsg.elements.length;i++) {
	    var e = document.prvmsg.elements[i];
	    if ((e.name != 'allbox') && (e.type=='checkbox'))
			e.checked = document.prvmsg.allbox.checked;
	}
}

function CheckCheckAll() {
	var TotalBoxes = 0;
	var TotalOn = 0;
	for (var i=0;i<document.prvmsg.elements.length;i++) {
	    var e = document.prvmsg.elements[i];
	    if ((e.name != 'allbox') && (e.type=='checkbox')) {
			TotalBoxes++;
			if (e.checked) {
				TotalOn++;
			}
	    }
	}
	if (TotalBoxes==TotalOn) {
	    document.prvmsg.allbox.checked=true;
	} else {
	    document.prvmsg.allbox.checked=false;
	}
}

function validate_frmadduser(frm) {
  var value = '';
  var errFlag = new Array();
  _qfMsg = '';

  value = new Array();

  value[0] = frm.elements['pass1'].value;
  value[1] = frm.elements['pass2'].value;
  if ('' != value[0] && !(value[0] == value[1]) && !errFlag['pass1']) {
    errFlag['pass1'] = true;
    _qfMsg = _qfMsg + '\n - 兩個密碼不相同，請重新輸入！';
  }
    
  if (_qfMsg != '') {
    _qfMsg = '==表單輸入錯誤==' + _qfMsg;
    _qfMsg = _qfMsg + '\n==請修正以上錯誤==';
    alert(_qfMsg);
    return false;
  }
  return true;
}
