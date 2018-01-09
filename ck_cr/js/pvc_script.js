var del_time = 100;
var timeplus = 1000;

/*
 *
 * function for feed back prompt winodw
 * use way add "bootstrap" and "jQuery UI" in the head
 * txt: what u want feed back information
 * url: make the page to redirect other page
 *
 */

function respons(txt, url, auto) {
    $("body").append('<div id="dialog-error" title=""></div>');

    $("#dialog-error").text(txt);
    $("#dialog-error").dialog({
        resizable: false,
        height: "auto",
        width: 400,
        modal: true,
        buttons: {
            確定: function () {
                let miliSecond = new Date();
                miliSecond = miliSecond.getTime() - itemstart.getTime();
                console.log(miliSecond);

                if (typeof pageName == "string") {
                    tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;message:" + txt + "@XX@" + miliSecond + ";" + "}@xx@";
                } else {
                    tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;message:" + txt + "@XX@" + miliSecond + ";" + "}@xx@";
                }

                if (typeof recordStr != "undefined") {
                    recordStr = recordStr + tmpRecordStr[0];
                } else {
                    recordStr = tmpRecordStr[0];
                }

                if (url !== undefined && url !== null) {
                    location.href = url;
                }
                $(this).dialog("close");
            }
        }
    });

    $(".ui-dialog-titlebar-close").remove();
    $(".ui-dialog-titlebar").remove();
    let chiBtn = $(".ui-dialog-buttonset").children("button");
    chiBtn.attr("class", "btn btn-raised btn-success");
    $(".ui-widget-content").css("border-width", "0px");

    if(auto === true){
        setTimeout(function(){
            $("#dialog-error").dialog("close");
        }, 60000)
    }
}

function responsImg(txt, url, auto) {
    $("body").append('<div id="dialog-error" title=""></div>');
    $("#dialog-error").text("");
    $("#dialog-error").append(txt);
    $("#dialog-error").dialog({
        resizable: false,
        height: "auto",
        width: 1000,
        modal: true,
        buttons: {
            確定: function () {
                let miliSecond = new Date();
                miliSecond = miliSecond.getTime() - itemstart.getTime();
                console.log(miliSecond);

                if (typeof pageName == "string") {
                    tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;messageImg:" + txt + "@XX@" + miliSecond + ";" + "}@xx@";
                } else {
                    tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;messageImg:" + txt + "@XX@" + miliSecond + ";" + "}@xx@";
                }

                if (typeof recordStr != "undefined") {
                    recordStr = recordStr + tmpRecordStr[0];
                } else {
                    recordStr = tmpRecordStr[0];
                }
                if (url != undefined && url !== null) {
                    location.href = url;
                }
                $(this).dialog("close");


            }
        }
    });
    $(".ui-dialog-titlebar-close").remove();
    $(".ui-dialog-titlebar").remove();
    let chiBtn = $(".ui-dialog-buttonset").children("button");
    chiBtn.attr("class", "btn btn-raised btn-success");
    $(".ui-widget-content").css("border-width", "0px");

    // auto trigger button when show_record was clicked
    if (auto === true) {
        setTimeout('$(".ui-dialog-buttonset button").trigger("click")', del_time);
    }
}


function responsEnd(txt, url, auto) {
    $("body").append('<div id="dialog-error" title=""></div>');
    $("#dialog-error").text(txt);
    $("#dialog-error").dialog({
        resizable: false,
        height: "auto",
        width: 400,
        modal: true,
        buttons: {
            確定: function () {
                let miliSecond = new Date();
                miliSecond = miliSecond.getTime() - itemstart.getTime();
                console.log(miliSecond);

                if (typeof pageName == "string") {
                    tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;message:" + txt + "@XX@" + miliSecond + ";" + "}@xx@";
                } else {
                    tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;message:" + txt + "@XX@" + miliSecond + ";" + "}@xx@";
                }

                if (typeof recordStr != "undefined") {
                    recordStr = recordStr + tmpRecordStr[0];
                } else {
                    recordStr = tmpRecordStr[0];
                }

                if (typeof url != 'undefined') {
                    location.href = url;
                }
                // $('#' + inputId).val("");
                $(this).dialog("close");
                window.close();
            }
        }
    });
    $(".ui-dialog-titlebar-close").remove();
    $(".ui-dialog-titlebar").remove();
    let chiBtn = $(".ui-dialog-buttonset").children("button");
    chiBtn.attr("class", "btn btn-raised btn-success");
    $(".ui-widget-content").css("border-width", "0px");
    if (auto === true) {
        setTimeout('$(".ui-dialog-buttonset button").trigger("click")', del_time);
        del_time += 100;
        alert("播放步驟完畢...");
    }
}

function responsImgEnd(txt, url, auto) {

    $("body").append('<div id="dialog-error" title=""></div>');
    $("#dialog-error").text("");
    $("#dialog-error").append(txt);
    $("#dialog-error").dialog({
        resizable: false,
        height: "auto",
        width: 1000,
        modal: true,
        buttons: {
            確定: function () {
                let miliSecond = new Date();
                miliSecond = miliSecond.getTime() - itemstart.getTime();
                console.log(miliSecond);

                if (typeof pageName == "string") {
                    tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;messageImg:" + txt + "@XX@" + miliSecond + ";" + "}@xx@";
                } else {
                    tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;messageImg:" + txt + "@XX@" + miliSecond + ";" + "}@xx@";
                }

                if (typeof recordStr != "undefined") {
                    recordStr = recordStr + tmpRecordStr[0];
                } else {
                    recordStr = tmpRecordStr[0];
                }
                if (typeof url != 'undefined') {
                    location.href = url;
                }
                $(this).dialog("close");
                window.close();

            }
        }
    });
    $(".ui-dialog-titlebar-close").remove();
    $(".ui-dialog-titlebar").remove();
    let chiBtn = $(".ui-dialog-buttonset").children("button");
    chiBtn.attr("class", "btn btn-primary");


}


let recordStr;
let tmpRecordStr = [];
let item_num;

let path = window.location.pathname;
let pageName = path.split("/").pop();
let folderName = path.substring(0, path.lastIndexOf('/')).substr(-6);

let itemstart = new Date();


function recordUserAction() {

    $("input:text").click(function (event) {
        let elemId;
        elemId = $(event.target).attr("id");
        console.log(elemId);
        if (elemId == "submit") {
            return recordStr;
        } else {
            let miliSecond = new Date();
            miliSecond = miliSecond.getTime() - itemstart.getTime();
            console.log(miliSecond)
            if (typeof pageName == "string") {
                tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + "@XX@" + $("#" + elemId).val() + "@XX@" + miliSecond + ";" + "}@xx@";
            } else {
                tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + "@XX@" + $("#" + elemId).val() + "@XX@" + miliSecond + ";" + "}@xx@";
            }

            if (typeof recordStr != "undefined") {
                recordStr = recordStr + tmpRecordStr[0];
            } else {
                recordStr = tmpRecordStr[0];
            }
        }

        $("#" + elemId).keyup(function (event) {
            console.log($("#" + elemId))
            let miliSecond = new Date();
            miliSecond = miliSecond.getTime() - itemstart.getTime();
            console.log(miliSecond);

            if (typeof pageName == "string") {
                tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + "@XX@" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
            } else {
                tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + "@XX@" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
            }

            if (typeof recordStr != "undefined") {
                recordStr = recordStr + tmpRecordStr[0];
            } else {
                recordStr = tmpRecordStr[0];
            }

            console.log("Record:" + recordStr);
        })

    })

    $("select").change(function () {
        let miliSecond = new Date();
        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond);

        let elemId = $(this).attr("id");

        if (typeof pageName == "string") {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + "@XX@" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        } else {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + "@XX@" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        }

        if (typeof recordStr != "undefined") {
            recordStr = recordStr + tmpRecordStr[0];
        } else {
            recordStr = tmpRecordStr[0];
        }

        console.log("selectRecord:" + recordStr);
    })

    $("input:radio").click(function (event) {
        let elemId = $(event.target).attr("id");
        let miliSecond = new Date();
        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond)
        if (typeof pageName == "string") {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + "@XX@" + $("#" + elemId).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        } else {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + "@XX@" + $("#" + elemId).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        }

        if (typeof recordStr != "undefined") {
            recordStr = recordStr + tmpRecordStr[0];
        } else {
            recordStr = tmpRecordStr[0];
        }
    })
    $("input:checkbox").click(function (event) {
        let elemId = $(event.target).attr("id");
        let miliSecond = new Date();
        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond)
        if (typeof pageName == "string") {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + "@XX@" + $("#" + elemId).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        } else {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + "@XX@" + $("#" + elemId).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        }

        if (typeof recordStr != "undefined") {
            recordStr = recordStr + tmpRecordStr[0];
        } else {
            recordStr = tmpRecordStr[0];
        }
    })
}

function recordUserAction2() {
    $("body").click(function (event) {
        let miliSecond = new Date();
        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond)

        let elemId;
        elemId = $(event.target).attr("id");
        console.log(elemId);
        if (elemId == "submit") {
            return recordStr;
        }
        if ($(event.target).is("img")) {



            if (typeof pageName == "string") {
                tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + "@XX@" + miliSecond + ";" + "}@xx@";
            } else {
                tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + ";value:" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
            }

            if (typeof recordStr != "undefined") {
                recordStr = recordStr + tmpRecordStr[0];
            } else {
                recordStr = tmpRecordStr[0];
            }
        } else {
            $("#" + elemId).keyup(function (event) {



                if (typeof pageName == "string") {
                    tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + ";value:" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
                } else {
                    tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + ";value:" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
                }

                if (typeof recordStr != "undefined") {
                    recordStr = recordStr + tmpRecordStr[0];
                } else {
                    recordStr = tmpRecordStr[0];
                }

                console.log("Record2:" + recordStr);
            })
        }


        console.log("Record:" + recordStr, " tmpRecord:" + tmpRecordStr);
    })
}

function recordUserActionWithMouse() {
    $("body").mouseup(function (event) {
        let elemId = $(event.target).attr("id");

        let x = $(event.target).parent().css("top");
        let y = $(event.target).parent().css("left");
        // console.log($(event.target).parent().css("top"), y);

        let miliSecond = new Date();
        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond)

        if (elemId == "submit") {
            return recordStr;
        }



        if (typeof pageName == "string") {

            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + ";x:" + x + ";y:" + y + "@XX@" + miliSecond + ";" + "}@xx@";
        } else {

            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + ";x:" + x + "y:" + y + "@XX@" + miliSecond + ";" + "}@xx@";
        }

        if (typeof recordStr != "undefined") {
            recordStr = recordStr + tmpRecordStr[0];
        } else {
            recordStr = tmpRecordStr[0];
        }

        // console.log("Record:" + recordStr);
    })
}


function recordUserActionWithMouse2() {




    $("#gridMath").mouseup(function (event) {

        let elemId = $(event.target).attr("id");

        var parentOffset = $(this).parent().offset();
        console.log(parentOffset);
        var relX = event.pageX - parentOffset.left - 5;
        var relY = event.pageY - parentOffset.top;
        console.log("left:" + relX, "top:" + relY);
        // console.log($(event.target).parent().css("top"), y);
        let miliSecond = new Date();

        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond)



        if ($("p[id^= step]").length >= 6) {
            $("#gridMath > p[id^=step]").click(function () {


                if ($("#" + elemId).css("background-color") == "rgb(0,128,0)") {
                    return;
                }

                // miliSecond = miliSecond.getMilliseconds();

                if (typeof pageName == "string") {
                    tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + ";value: green;" + miliSecond + ";" + "}@xx@";
                } else {
                    tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + ";value: green;" + miliSecond + ";" + "}@xx@";
                }

                if (typeof recordStr != "undefined") {
                    recordStr = recordStr + tmpRecordStr[0];
                } else {
                    recordStr = tmpRecordStr[0];
                }

                console.log("Record:" + recordStr);
                return;
            })

        }

        // console.log(miliSecond)
        if (elemId == "submit") {
            return recordStr;
        }


        miliSecond = miliSecond.getTime() - itemstart.getTime();
        if (typeof pageName == "string") {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:step" + stepCount + ";left:" + relX + ";top:" + relY + "@XX@" + miliSecond + ";" + "}@xx@";
        } else {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:step" + stepCount + ";left:" + relX + "top:" + relY + "@XX@" + miliSecond + ";" + "}@xx@";
        }



        if (typeof recordStr != "undefined") {
            return recordStr = recordStr + tmpRecordStr[0];
        } else {
            recordStr = tmpRecordStr[0];
        }

        console.log("Record:" + recordStr);
    })


}

function recordUserActionWithMouse3() {
    $("#grid").mouseup(function () {
        let elemId = $(event.target).attr("id");
        console.log(elemId);

        var parentOffset = $(this).parent().offset();

        var relX = event.pageX - parentOffset.left - 5;
        var relY = event.pageY - parentOffset.top;

        let miliSecond = new Date();
        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond)

        // console.log($(event.target).parent().css("top"), y);

        if (elemId == "submit") {
            return recordStr;
        }
        if (typeof pageName == "string") {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:step" + stepCount + ";left:" + relX + ";top:" + relY + "@XX@" + miliSecond + ";" + "}@xx@";
        } else {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:step" + stepCount + ";left:" + relX + "top:" + relY + "@XX@" + miliSecond + ";" + "}@xx@";
        }

        if (typeof recordStr != "undefined") {
            recordStr = recordStr + tmpRecordStr[0];
        } else {
            recordStr = tmpRecordStr[0];
        }
        console.log("Record:" + recordStr);
    })

    $("input[id$=text]").keyup(function (event) {


        let miliSecond = new Date();
        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond)

        if (typeof pageName == "string") {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + $(this).attr("id") + ";value:" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        } else {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + $(this).attr("id") + ";value:" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        }

        if (typeof recordStr != "undefined") {
            recordStr = recordStr + tmpRecordStr[0];
        } else {
            recordStr = tmpRecordStr[0];
        }
        console.log("Record keyUp:" + recordStr);

    })




}


function recordUserActionWithMouse4() {


    $("#grid").mouseup(function (event) {

        let elemId = $(event.target).attr("id");

        var parentOffset = $(this).parent().offset();
        console.log(parentOffset);
        var relX = event.pageX - parentOffset.left - 5;
        var relY = event.pageY - parentOffset.top;
        console.log("left:" + relX, "top:" + relY);
        // console.log($(event.target).parent().css("top"), y);

        let miliSecond = new Date();
        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond)

        if (elemId == "submit") {
            return recordStr;
        }

        if (typeof pageName == "string") {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:step" + stepCount + ";left:" + relX + "px;top:" + relY + "px;" + miliSecond + ";" + "}@xx@";
        } else {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:step" + stepCount + ";left:" + relX + "px;top:" + relY + "px;" + miliSecond + ";" + "}@xx@";
        }

        if (typeof recordStr != "undefined") {
            recordStr = recordStr + tmpRecordStr[0];
        } else {
            recordStr = tmpRecordStr[0];
        }

        console.log("Record:" + recordStr);
    })

    $("#grid2").click(function (event) {

        let elemId = $(event.target).attr("id");

        var parentOffset = $(this).parent().offset();
        console.log(parentOffset);
        var relX = event.pageX - parentOffset.left - 5;
        var relY = event.pageY - parentOffset.top;
        console.log("left:" + relX, "top:" + relY);
        // console.log($(event.target).parent().css("top"), y);

        let miliSecond = new Date();
        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond)

        $("#grid2 > p[id^=step]").click(function () {
            let elemId = $(this).attr("id");

            let miliSecond = new Date();
            miliSecond = miliSecond.getTime() - itemstart.getTime();
            console.log(miliSecond)

            if ($("#" + elemId).css("background-color") == "rgb(0,128,0)") {
                return;
            }

            if ($("#grid2 > p[id^=step]").length == 2) {
                if (typeof pageName == "string") {
                    tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + ";value: green;" + miliSecond + ";" + "}@xx@";
                } else {
                    tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + ";value: green;" + miliSecond + ";" + "}@xx@";
                }
            }
            if (typeof recordStr != "undefined") {
                recordStr = recordStr + tmpRecordStr[0];
            } else {
                recordStr = tmpRecordStr[0];
            }

            console.log("Record:" + recordStr);
            return recordStr;
        })


        if (elemId == "submit") {
            return recordStr;
        }

        if (typeof pageName == "string") {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:step" + stepCount + ";left:" + relX + "px;top:" + relY + "px;" + miliSecond + ";" + "}@xx@";
        } else {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:step" + stepCount + ";left:" + relX + "px;top:" + relY + "px;" + miliSecond + ";" + "}@xx@";
        }

        if (typeof recordStr != "undefined") {
            recordStr = recordStr + tmpRecordStr[0];
        } else {
            recordStr = tmpRecordStr[0];
        }

        console.log("Record:" + recordStr);
    })


    $("#step4").mouseup(function () {
        let elemId = $(this).attr("id");

        let miliSecond = new Date();
        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond)

        if ($("#" + elemId).css("background-color") == "rgb(0,128,0)") {
            return;
        }

        // miliSecond = miliSecond.getMilliseconds();
        if ($("#grid2 > p[id^=step]").length == 2) {
            if (typeof pageName == "string") {
                tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + ";value: green;" + miliSecond + ";" + "}@xx@";
            } else {
                tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + ";value: green;" + miliSecond + ";" + "}@xx@";
            }
        }
        if (typeof recordStr != "undefined") {
            recordStr = recordStr + tmpRecordStr[0];
        } else {
            recordStr = tmpRecordStr[0];
        }

        console.log("Record:" + recordStr);
        return recordStr;
    })



    $('input:text').keyup(function () {
        let elemId = $(this).attr("id");

        let miliSecond = new Date();
        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond)

        if (typeof pageName == "string") {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + ";value:" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        } else {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + ";value:" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        }

        if (typeof recordStr != "undefined") {
            recordStr = recordStr + tmpRecordStr[0];
        } else {
            recordStr = tmpRecordStr[0];
        }

        console.log("Record:" + recordStr);

    })

    $('input:radio').click(function () {
        let elemId = $(this).attr("id");

        let miliSecond = new Date();
        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond)

        if (typeof pageName == "string") {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + ";value:" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        } else {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + ";value:" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        }

        if (typeof recordStr != "undefined") {
            recordStr = recordStr + tmpRecordStr[0];
        } else {
            recordStr = tmpRecordStr[0];
        }

        console.log("Record:" + recordStr);

    })



}

function recordUserActionDragWithInput() {
    $("body").mouseup(function () {
        let elemId = $(event.target).attr("id");
        if ($(event.target).attr("class") == "draggable ui-draggable ui-draggable-handle ui-draggable-dragging") {
            let x = $(event.target).css("top");
            let y = $(event.target).css("left");
            console.log($(event.target).parent().css("top"), y);

            let miliSecond = new Date();
            miliSecond = miliSecond.getTime() - itemstart.getTime();
            console.log(miliSecond)

            if (elemId == "submit") {
                return recordStr;
            }

            if (typeof pageName == "string") {
                tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + ";x:" + x + ";y:" + y + "@XX@" + miliSecond + ";" + "}@xx@";
            } else {
                tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + ";x:" + x + "y:" + y + "@XX@" + miliSecond + ";" + "}@xx@";
            }

            if (typeof recordStr != "undefined") {
                recordStr = recordStr + tmpRecordStr[0];
            } else {
                recordStr = tmpRecordStr[0];
            }

            console.log("Record:" + recordStr);
        }
    })

    $("input:text").keyup(function (event) {
        let elemId = $(event.target).attr("id");
        let miliSecond = new Date();
        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond)

        if (typeof pageName == "string") {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + ";value:" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        } else {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + ";value:" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        }

        if (typeof recordStr != "undefined") {
            recordStr = recordStr + tmpRecordStr[0];
        } else {
            recordStr = tmpRecordStr[0];
        }

        console.log("Record:" + recordStr, " tmpRecord:" + tmpRecordStr);
        return recordStr;
    })
}

function recordUserActionGraphicChart() {
    $("input:text").click(function (event) {
        let elemId;
        elemId = $(event.target).attr("id");
        console.log(elemId);
        if (elemId == "submit") {
            return recordStr;
        } else {
            let miliSecond = new Date();
            miliSecond = miliSecond.getTime() - itemstart.getTime();
            console.log(miliSecond)
            if (typeof pageName == "string") {
                tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + "@XX@" + $("#" + elemId).val() + "@XX@" + miliSecond + ";" + "}@xx@";
            } else {
                tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + "@XX@" + $("#" + elemId).val() + "@XX@" + miliSecond + ";" + "}@xx@";
            }

            if (typeof recordStr != "undefined") {
                recordStr = recordStr + tmpRecordStr[0];
            } else {
                recordStr = tmpRecordStr[0];
            }
        }

        $("#" + elemId).keyup(function (event) {
            console.log($("#" + elemId))
            let miliSecond = new Date();
            miliSecond = miliSecond.getTime() - itemstart.getTime();
            console.log(miliSecond)

            if (typeof pageName == "string") {
                tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + "@XX@" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
            } else {
                tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + "@XX@" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
            }

            if (typeof recordStr != "undefined") {
                recordStr = recordStr + tmpRecordStr[0];
            } else {
                recordStr = tmpRecordStr[0];
            }

            console.log("Record:" + recordStr);
        })
    })

    $("select").change(function () {
        let miliSecond = new Date();
        miliSecond = miliSecond.getTime() - itemstart.getTime();
        console.log(miliSecond);

        let elemId = $(this).attr("id");

        if (typeof pageName == "string") {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + folderName + "_1;id:" + elemId + "@XX@" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        } else {
            tmpRecordStr[0] = "@xx@{indicateId:" + $('#title').text() + ";item_num:" + pageName.substr(0, 8) + ";id:" + elemId + "@XX@" + $(this).val() + "@XX@" + miliSecond + ";" + "}@xx@";
        }

        if (typeof recordStr != "undefined") {
            recordStr = recordStr + tmpRecordStr[0];
        } else {
            recordStr = tmpRecordStr[0];
        }

        console.log("selectRecord:" + recordStr);
    })
}

function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
}


//
//  請學長協助寫入開始、結束、持續時間及date還有使用者ID
//

let startTime = itemstart.getTime();
console.log(startTime);

function ajaxInsertData() {
    console.log("ajaxInsrt:" + recordStr);
    let endSecond = new Date();

    endSecond = endSecond.getTime();
    console.log(endSecond);
    $.ajax({
            url: '../DB.php',
            type: 'POST',
            data: {
                org_res: recordStr,
                item_num: $("#title").text(),
                indicate_id: folderName,
                startTime: startTime,
                endTime: endSecond
            },
        })
        .done(function (data) {
            console.log(recordStr);
            console.log("success" + data);
        })
        .fail(function (err) {
            console.log("error:" + err);
        });
}

// show user record action button
function ckInsertData() {
    if (recordStr.length != 0) {
        $("#showRecord").show();
    }
}

$(function () {

    if(window.screen.availWidth < 1280 && window.screen.availHeight < 768){
        alert("請調整螢幕解析度為1280x768。");
        window.close();
    }

    if (pageName == "index.html" || pageName == "") {
        pageName = folderName;
    } else {
        if (pageName.length == 11) {
            pageName = pageName.substr(0, 9);
        } else {
            pageName = pageName.substr(0, 8);
        }

    }



    switch ($("#title").text()) {
        case "3-n-05_9":
            recordUserActionWithMouse3();
            break;
        case "3-n-05_2":
            recordUserAction2();
            break;
        case "6-n-10_6":
            recordUserActionWithMouse2();
            break;
        case "6-n-10_7":
            recordUserActionWithMouse4();
            break;
        case "6-n-10":
            recordUserActionWithMouse();
            break;
        case "6-n-09":
            recordUserActionDragWithInput();
            break;
        case "6-d-01_2":
            recordUserActionGraphicChart();
            break;
        case "6-d-01_5":
            recordUserActionGraphicChart();
            break;
        case "6-d-01_6":
            recordUserActionGraphicChart();
            break;
        case "4-n-16_10":
            alert("請勿縮小視窗，以免造成教學繪圖工具判斷發生誤差");
            break;
        case "4-n-16_11":
            alert("請勿縮小視窗，以免造成教學繪圖工具判斷發生誤差");
            break;
        case "4-s-03_8":
            alert("請勿縮小視窗，以免造成教學繪圖工具判斷發生誤差");
            break;
        case "4-s-03_9":
            alert("請勿縮小視窗，以免造成教學繪圖工具判斷發生誤差");
            break;
        case "4-s-03_10":
            alert("請勿縮小視窗，以免造成教學繪圖工具判斷發生誤差");
            break;
        case "4-s-08_1":
            alert("請勿縮小視窗，以免造成教學繪圖工具判斷發生誤差");
            break;
        case "4-s-08_2":
            alert("請勿縮小視窗，以免造成教學繪圖工具判斷發生誤差");
            break;
        case "4-s-08_3":
            alert("請勿縮小視窗，以免造成教學繪圖工具判斷發生誤差");
            break;
        default:
            recordUserAction();
            break;
    }
})


// show user Input action
let testCount = 0;
//let tmpTime =0;
let autoInput;

function show_record() {
    let splitRecordStr = recordStr.split(";");
    console.log(splitRecordStr);
    let tmpId = "";

    $("input").val("");
    alert("開始播放步驟...")

    for (let x = 0; x <= splitRecordStr.length; x++) {
        if (splitRecordStr[x] === undefined) {
            return;
        }

        let y = splitRecordStr[x].split(":");

        console.log(y);

        switch (y[0]) {
            case "id":
                var strSearchValue = y[1].split("@XX@");
                let indicate = $("#title").text().split("_");
                switch (indicate[0]) {
                    case "1-s-03":
                        $("#" + strSearchValue[0]).animate({
                            "left": parseInt(strSearchValue[2])+"px",
                            "top": parseInt(strSearchValue[4]) + "px",
                        }).delay(parseInt(strSearchValue[5])/1000);
                    break;
                    default:
                        autoInput = setTimeout("ssss(" + strSearchValue[0] + ", " + strSearchValue[1] + ")", parseInt(strSearchValue[2]));
                        var inputTime = parseInt(strSearchValue[2]);
                        //                if (tmpValue[0] < parseInt(strSearchValue[2]) && tmpValue[0] !== undefined) {
                        //                    del_time = (parseInt(tmpValue[0])) + (parseInt(strSearchValue[2]))
                        //                    tmpValue = del_time;
                        //                } else {
                        //                    tmpValue[0] = parseInt(strSearchValue[2]);
                        //                }
                        console.log(strSearchValue);
                    break;
                }

            break;

            case "message":
                let str = y[1].split("@XX@");
                let message = str[0];
                let msgShowTime =str[1];

                if( inputTime === undefined){
                    inputTime = 0;
                }

                let msgClickTime = parseInt(msgShowTime) - inputTime;

                console.log(msgShowTime, msgShowTime, inputTime);

                setTimeout(function(){
                    respons(message);
                    setTimeout(function(){
                       $("#dialog-error").dialog("close");
                    }, msgClickTime)
                }, parseInt(msgShowTime))
            break;

        }

    }
}

function ssss(id, value) {
    $("#" + id.id).val(value);
}

// id: canvas id
// x1: draw line x1, value must fixed.
// y1: draw line y1, value must fixed.
// x2: draw line x2, value can modify.
// y2: draw line y2, value can modify.
function canvasAutodrawLine(id, x1, y1, x2, y2){
    let canvasId = document.getElementById(id);
    let canvaContext = canvas.getContext("2d");
    canvaContext.beginPath();
    canvaContext.moveTo(x1, y1);
    canvaContext.lineTo(x2, y2);
    canvaContext.lineWidth = 5;
    canvaContext.strokeStyle = '#ff0000';
    canvaContext.stroke();
}

/*
 *
 * function for mouse move rotation
 *
 *
 *
 */




/*
 *
 * function for canvas draw line
 * this can prevent mouse down/up in same place
 * still clear problem.
 * use way:
 * now there's start.x/y and end.x/y variable can use is easy to defind
 * where i start where i end
 * original code from stackoverflow
 * startPointX/Y, startPointX2/Y2: where is your start
 * endPointX/Y, endPointX2/Y2:  where is your end
 * draw_x_y: bool value check is clear or not.
 * $("clear"): can be button or something can be click
 *
 */

// var draw_x_y = false;
// function drawLine(startPointX, startPointX2, startPointY, startPointY2, endPointX, endPointX2, endPointY, endPointY2) {
//     console.log(startPointX, startPointX2, startPointY, startPointY2, endPointX, endPointX2, endPointY, endPointY2);
//     $("#clear").show();
//     var drawLine = false;
//     var draw_wrong = false;
//     var theCanvas = document.getElementById("canvas");
//     var finalPos = {
//         x: 0,
//         y: 0
//     };
//     var startPos = {
//         x: 0,
//         y: 0
//     };
//     var ctx = theCanvas.getContext('2d');
//     // theCanvas.width = 420;
//     // theCanvas.height = 300;

//     var canvasOffset = $("#canvas").offset();

//     function line(cnvs) {
//         if ((startPos.x == 0 && startPos.y == 0) || (finalPos.x == 0 && finalPos.y == 0)) {
//             return;
//         }
//         cnvs.beginPath();
//         cnvs.moveTo(startPos.x, startPos.y);
//         cnvs.lineTo(finalPos.x, finalPos.y);
//         cnvs.stroke();
//     }

//     function clearCanvas() {

//         ctx.clearRect(0, 0, theCanvas.width, theCanvas.height);

//     }

//     $('#canvas').mousemove(function (e) {
//         if (drawLine === true) {
//             finalPos = {
//                 x: e.pageX - canvasOffset.left,
//                 y: e.pageY - canvasOffset.top
//             };
//             clearCanvas();
//             line(ctx);
//         }
//     });

//     $('#canvas').mousedown(function (e) {
//         drawLine = true;
//         ctx.strokeStyle = 'blue';
//         ctx.lineWidth = 5;
//         ctx.lineCap = 'round';
//         ctx.beginPath();
//         startPos = {
//             x: e.pageX - canvasOffset.left,
//             y: e.pageY - canvasOffset.top
//         };
//     });

//     $('#canvas').mouseup(function () {
//         console.log("1232")
//         console.log("start:" + startPos.x, startPos.y)
//         console.log("end:" + finalPos.x, finalPos.y)
//         if (((parseInt(startPos.x) >= startPointX && parseInt(startPos.x) <= startPointX2) &&
//             (parseInt(finalPos.x) >= endPointX && parseInt(finalPos.x) <= endPointX2))) {
//             if (((parseInt(startPos.y) >= startPointY && parseInt(startPos.y) <= startPointY2) &&
//                 (parseInt(finalPos.y) >= endPointY && parseInt(finalPos.y) <= endPointY2))) {
//                 draw_x_y = true;
//                 console.log("g")
//             } else {
//                 draw_x_y = false;
//             }
//         } else if (((parseInt(finalPos.x) >= startPointX && parseInt(finalPos.x) <= startPointX2) &&
//             (parseInt(startPos.x) >= endPointX && parseInt(startPos.x) <= endPointX2))) {
//             if (((parseInt(finalPos.y) >= startPointY && parseInt(finalPos.y) <= startPointY2) &&
//                 (parseInt(startPos.y) >= endPointY && parseInt(startPos.y) <= endPointY2))) {
//                 draw_x_y = true;
//                 console.log("g")
//             } else {
//                 draw_x_y = false;
//             }
//         } else {
//             draw_x_y = false;
//         }
//         // clearCanvas();
//         // Replace with var that is second canvas
//         line(ctx);
//         finalPos = {
//             x: 0,
//             y: 0
//         };
//         startPos = {
//             x: 0,
//             y: 0
//         };
//         drawLine = false;
//     });
// }
