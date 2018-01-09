lock = function(theEvent) {
        if (theEvent != null) {
                event = theEvent;
        }
        // 擋滑鼠右鍵選單事件
        if (event.type == "contextmenu") {
            return false;
        // 擋滑鼠中鍵與右鍵事件
        } else if (event.type == "mousedown") {
                if (event.button == 2 || event.button == 3) {
                        return false;
                }
        // 擋 IE 按下 F1 鈕時會觸發的 onhelp 事件
        } else if (event.type == "help") {
            return false;
        // 擋特定按鍵
        } else if (event.type == "keydown") {
            // 擋 alt、ctrl 鍵
            if (event.altKey || event.ctrlKey) {
                    return false;
            }
            // 擋 F1 ~ F12 功能鍵，其中此種寫法只能擋 FF 的 F1 鍵，無法擋 IE 的 F1 鍵
            if (event.keyCode >= 112 && event.keyCode <= 123) {
                    try {
                            // IE 要將 keyCode 設為 0 才能真正擋下 F2 ~ F12 功能鍵，
                            // 但 FF 會丟 Exception，所以用 try-cache 擋住
                            event.keyCode = 0; 
                    } catch(e){}
                    return false;
            }
        }
}

document.onmousedown = lock;
document.oncontextmenu = lock;
document.onkeydown = lock;
window.onhelp = lock;