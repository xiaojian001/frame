function u_to_ymd(dateNum) {
    if (dateNum > 0) {
        var date = new Date(dateNum * 1000);
        return date.getFullYear() + "-" + fixZero(date.getMonth() + 1, 2) + "-" + fixZero(date.getDate(), 2);
    } else {
        return '';
    }
}

function u_to_ymdhis(dateNum) {
    if (dateNum > 0) {
        var date = new Date(dateNum * 1000);
        return date.getFullYear() + "-" + fixZero(date.getMonth() + 1, 2) + "-" + fixZero(date.getDate(), 2) + " " + fixZero(date.getHours(), 2) + ":" + fixZero(date.getMinutes(), 2) + ":" + fixZero(date.getSeconds(), 2);
    } else {
        return '';
    }
}

function fixZero(num, length) {
    var str = "" + num;
    var len = str.length;
    var s = "";
    for (var i = length; i-- > len;) {
        s += "0";
    }
    return s + str;
}

/*刷行区域*/
function G_Ajax(this_Dom, Dom_paren_type, ToDom, url) {
    $('#' + ToDom).load(url);
    $(this_Dom).parents(Dom_paren_type).children().removeClass("selected");
    $(this_Dom).addClass("selected");
}

//搜索

var Search_From_w;//搜索框的宽度
var Search_From_h;//搜索框的高度
function Data_Search(Search_From, Datagrid_data) {
    if (Search_From_w < 20 || Search_From_w == null) {
        Search_From_w = $('#' + Search_From).width();
        Search_From_h = $('#' + Search_From).height();
        if (Search_From_w < 20) {
            Search_From_w = 600
        }
        if (Search_From_h < 20) {
            Search_From_h = 350
        }
    }
    $('#' + Search_From).dialog({
        width: Search_From_w,
        height: Search_From_h,
        title: '搜索',
        top: 0,
        modal: true,
        cache: false,
        buttons: [{
            text: '搜索',
            iconCls: 'iconfont icon-search',
            handler: function () {
                var queryParams = $('#' + Datagrid_data).datagrid('options').queryParams;
                $.each($('#' + Search_From).serializeArray(), function () {
                    queryParams[this['name']] = this['value'];
                });
                $('#' + Datagrid_data).datagrid('reload');
            },
        }],
    })
}


function Add_Order(From, Datagrid_data) {
    $('#' + From).form('clear');
    $('#' + From).dialog({
        width: 900,
        top: 0,
        height: 500,
        title: '添加订单',
        modal: true,
        cache: false,
        buttons: [{
            text: '确定',
            iconCls: 'iconfont icon-add',
            handler: function () {
                $.post('Admin/Order/add', $('#' + From).serialize(), function (res) {
                    if (!res.status) {
                        $.messager.show({
                            title: '错误提示', msg: res.info, timeout: 5000, showType: 'slide', style: {
                                right: '',
                                top: document.body.scrollTop + document.documentElement.scrollTop,
                                bottom: ''
                            }
                        });
                    } else {
                        $('#' + From).dialog('close');
                        $.messager.show({
                            title: '成功提示', msg: res.info, timeout: 2000, showType: 'slide', style: {
                                right: '',
                                top: document.body.scrollTop + document.documentElement.scrollTop,
                                bottom: ''
                            }
                        });
                        $('#' + Datagrid_data).datagrid('reload');
                    }
                })
            },
        }],
    })
}


function Assign_Worker(From, Datagrid_data) {
    $('#' + From).form('clear');
    var row = $('#' + Datagrid_data).datagrid('getSelected');
    if (row) {
        var order_id = row.id;
        if (!order_id) {
            $.messager.show({
                title: '错误提示', msg: '请选择你需要指派的订单！', timeout: 3000, showType: 'slide', style: {
                    right: '',
                    top: document.body.scrollTop + document.documentElement.scrollTop,
                    bottom: ''
                }
            });
        } else {
            if(row.order_status == 0){
                $.messager.show({
                    title: '错误提示', msg: '该订单已被关闭，不能指派！', timeout: 3000, showType: 'slide', style: {
                        right: '',
                        top: document.body.scrollTop + document.documentElement.scrollTop,
                        bottom: ''
                    }
                });
                return false;
            }
            if(row.order_status != 1){
                $.messager.show({
                    title: '错误提示', msg: '该订单已被指派过，不能重新指派！', timeout: 3000, showType: 'slide', style: {
                        right: '',
                        top: document.body.scrollTop + document.documentElement.scrollTop,
                        bottom: ''
                    }
                });
                return false;
            }
            var worker_id;
            $('#' + From).dialog({
                width: 360,
                top: 0,
                height: 500,
                title: '指派订单',
                modal: true,
                cache: false,
                buttons: [{
                    text: '确定',
                    iconCls: 'iconfont icon-add',
                    handler: function () {
                        worker_id = $('#worker_id').combogrid('getValue');
                        if (!worker_id) {
                            $.messager.show({
                                title: '错误提示', msg: '请选择你需要指派的配送员！', timeout: 5000, showType: 'slide', style: {
                                    right: '',
                                    top: document.body.scrollTop + document.documentElement.scrollTop,
                                    bottom: ''
                                }
                            });
                        } else {
                            $.post('Admin/Order/assignWorker', {
                                worker_id: worker_id,
                                order_id: order_id
                            }, function (res) {
                                if (!res.status) {
                                    $.messager.show({
                                        title: '错误提示', msg: res.info, timeout: 5000, showType: 'slide', style: {
                                            right: '',
                                            top: document.body.scrollTop + document.documentElement.scrollTop,
                                            bottom: ''
                                        }
                                    });
                                } else {
                                    $('#' + From).dialog('close');
                                    $.messager.show({
                                        title: '成功提示', msg: res.info, timeout: 2000, showType: 'slide', style: {
                                            right: '',
                                            top: document.body.scrollTop + document.documentElement.scrollTop,
                                            bottom: ''
                                        }
                                    });
                                    $('#' + Datagrid_data).datagrid('reload');
                                }
                            })
                        }
                    },
                }],
            })
        }
    } else {
        $.messager.show({
            title: '错误提示', msg: '请选择你需要指派的订单！', timeout: 3000, showType: 'slide', style: {
                right: '',
                top: document.body.scrollTop + document.documentElement.scrollTop,
                bottom: ''
            }
        });
    }
}


var LODOP; //声明为全局变量
function Order_Print(id, Datagrid_data) {
    var row = [];
    if (id) {
        row.id = id;
    } else {
        row = $('#' + Datagrid_data).datagrid('getSelected');
        if (!row) {
            $.messager.show({
                title: '错误提示', msg: '请选择你需要打印的订单！', timeout: 3000, showType: 'slide', style: {
                    right: '',
                    top: document.body.scrollTop + document.documentElement.scrollTop,
                    bottom: ''
                }
            });
            return false;
        }
    }
    $.post('Admin/Order/getPrintInfo', {id: row.id}, function (res) {
        if (res) {
            CreatePage(res);
            //LODOP.ADD_PRINT_SETUP_BKIMG("<img border='0' src='http://s1.sinaimg.cn/middle/721e77e5t99431b026bd0&690'>");
            //LODOP.SET_SHOW_MODE("BKIMG_PRINT",1);
            LODOP.PREVIEW();
        }
    });
};

function CreatePage(res) {
    LODOP = getLodop();
    LODOP.PRINT_INIT("打印订单");
    LODOP.ADD_PRINT_TEXT("4.2cm", "3.2cm", "2.2cm", "0.6cm", res.receiver_name);
    LODOP.ADD_PRINT_TEXT("4.2cm", "7.3cm", "3.9cm", "0.6cm", res.receiver_telephone);
    LODOP.ADD_PRINT_TEXT("4.4cm", "12.9cm", "4.5cm", "0.6cm", res.order_add_time);
    LODOP.ADD_PRINT_TEXT("6.0cm", "2.9cm", "14.0cm", "1.0cm", res.order_remark);
    LODOP.ADD_PRINT_TEXT("8.5cm", "3.6cm", "14.0cm", "1.0cm", res.address);
    LODOP.ADD_PRINT_TEXT("9.6cm", "3.6cm", "2.2cm", "0.6cm", res.good_money);
    LODOP.ADD_PRINT_TEXT("9.6cm", "8.7cm", "2.5cm", "0.6cm", res.service_fee);
    LODOP.ADD_PRINT_TEXT("9.6cm", "14.8cm", "2.5cm", "0.6cm", res.other_fee);
    LODOP.ADD_PRINT_TEXT("10.2cm", "3.6cm", "2.5cm", "0.6cm", res.total_money);
    LODOP.ADD_PRINT_TEXT("10.2cm", "8.2cm", "2.5cm", "0.6cm", res.admin);
    LODOP.ADD_PRINT_TEXT("10.8cm", "12.9cm", "2.5cm", "0.6cm", res.number);
};

function Notice_worker(Datagrid_data) {
    var row = $('#' + Datagrid_data).datagrid('getSelected');
    if (!row) {
        $.messager.show({
            title: '错误提示', msg: '请选择你需要通知的订单！', timeout: 3000, showType: 'slide', style: {
                right: '',
                top: document.body.scrollTop + document.documentElement.scrollTop,
                bottom: ''
            }
        });
        return false;
    }
    $.post('Admin/Order/noticeWorker', {id: row.id}, function (res) {
        var tips;
        if (!res.status) {
            tips = '错误提示';
        } else {
            tips = '成功提示'
        }
        $.messager.show({
            title: tips, msg: res.info, timeout: 2000, showType: 'slide', style: {
                right: '',
                top: document.body.scrollTop + document.documentElement.scrollTop,
                bottom: ''
            }
        });
    });
};


editRow = undefined;
function list_edit(Data_List, Row_id) {
    if (editRow == undefined) {
        //开始编辑
        $('#save_bon,#redo_bon').show();
        $('#' + Data_List).datagrid('beginEdit', Row_id);
        editRow = Row_id
        $('#box').datagrid('unselectRow', Row_id);
    } else {
        if (editRow == Row_id) {
            list_save(Data_List);
        } else {
            //错误提示
            $.messager.show({title: '错误提示', msg: '请在保存后编辑新行', timeout: 2000, showType: 'slide'});
        }
    }
}
function list_save(Data_List) {
    $('#' + Data_List).datagrid('endEdit', editRow);
}

function list_redo(Data_List) {
    $('#save_bon,#redo_bon').hide();
    editRow = undefined;
    $('#' + Data_List).datagrid('rejectChanges');
}

//弹窗编辑

var Updata_From_w;//弹窗编辑框的宽度
var Updata_From_h;//弹窗编辑框的高度
function From_Data_Updata(Updata_From, Updata_From_Url, Updata_From_Title, Datagrid_data) {
    if (Updata_From_w < 20 || Updata_From_w == null) {
        Updata_From_w = $('#' + Updata_From).width();
        Updata_From_h = $('#' + Updata_From).height();
        if (Updata_From_w < 20) {
            Updata_From_w = 600
        }
        if (Updata_From_h < 20) {
            Updata_From_h = 350
        }
    }
    Data_From_action = $('#' + Updata_From).attr('url');
    $('#' + Updata_From).dialog({
        width: Updata_From_w,
        height: Updata_From_h,
        href: Updata_From_Url,
        title: '数据处理',
        modal: true,
        cache: false,
        buttons: [{
            text: '提交',
            iconCls: 'iconfont icon-success',
            handler: function () {
                $.post(Updata_From_Url, $('#' + Updata_From).serialize(), function (res) {
                    if (!res.status) {
                        $.messager.show({title: '错误提示', msg: res.info, timeout: 2000, showType: 'slide'});
                    } else {
                        $('#' + Updata_From).dialog('close');
                        $.messager.show({title: '成功提示', msg: res.info, timeout: 1000, showType: 'slide'});
                        $('#' + Datagrid_data).datagrid('reload');
                        $('#' + Datagrid_data).treegrid('reload');
                    }
                })
            },
        }],
    })
}

function Data_Remove(Data_from_url, Datagrid_data) {
    $.messager.confirm('确定操作', '您正在要删除所选的记录吗？', function (flag) {
        if (flag) {
            $.post(Data_from_url, {}, function (res) {
                if (!res.status) {
                    $.messager.show({title: '错误提示', msg: res.info, timeout: 2000, showType: 'slide'});
                } else {
                    $.messager.show({title: '成功提示', msg: res.info, timeout: 1000, showType: 'slide'});
                    $('#' + Datagrid_data).datagrid('reload');
                    $('#' + Datagrid_data).treegrid('reload');
                }
            })
        }
    })
}

function Data_Ajax(Data_from_url, Datagrid_data, count) {
    if (count != '') {
        $.messager.confirm('确定操作', count, function (flag) {
            if (flag) {
                $.post(Data_from_url, {}, function (res) {
                    if (!res.status) {
                        $.messager.show({title: '错误提示', msg: res.info, timeout: 2000, showType: 'slide'});
                    } else {
                        $.messager.show({title: '成功提示', msg: res.info, timeout: 1000, showType: 'slide'});
                        $('#' + Datagrid_data).datagrid('reload');
                        $('#' + Datagrid_data).treegrid('reload');
                    }
                })
            }
        })
    } else {
        $.post(Data_from_url, {}, function (res) {
            if (!res.status) {
                $.messager.show({title: '错误提示', msg: res.info, timeout: 2000, showType: 'slide'});
            } else {
                $.messager.show({title: '成功提示', msg: res.info, timeout: 1000, showType: 'slide'});
                $('#' + Datagrid_data).datagrid('reload');
                $('#' + Datagrid_data).treegrid('reload');
            }
        })
    }
}

/* 刷新页面 */
function Data_Reload(Data_Box) {
    $('#' + Data_Box).datagrid('reload');
    $('#' + Data_Box).treegrid('reload');
}


KindEditor.ready(function (K) {
});


/* 上传附件 */

function updata_fields(file_box) {
    KindEditor.ready(function (K) {
        updata_fields_editor = K.editor({
            allowFileManager: true,
            pasteType: ke_pasteType,
            fileManagerJson: ke_fileManagerJson,
            uploadJson: ke_uploadJson,
            extraFileUploadParams: {
                uid: ke_Uid
            }
        });
        updata_fields_editor.loadPlugin('insertfile', function () {
            updata_fields_editor.plugin.fileDialog({
                fileUrl: $('#' + file_box).textbox('getValue'),
                clickFn: function (url, title) {
                    $('#' + file_box).textbox('setValue', url);
                    updata_fields_editor.hideDialog();
                }
            });
        });
    });
}

/* 上传图片 */

function updata_image(image_box) {
    KindEditor.ready(function (K) {
        var updata_image_editor = K.editor({
            allowFileManager: true,
            pasteType: ke_pasteType,
            fileManagerJson: ke_fileManagerJson,
            uploadJson: ke_uploadJson,
            extraFileUploadParams: {
                uid: ke_Uid
            }
        });
        updata_image_editor.loadPlugin("image", function () {
            updata_image_editor.plugin.imageDialog({
                imageUrl: $('#' + image_box).textbox('getValue'),
                clickFn: function (url, title, width, height, border, align) {
                    $('#' + image_box).textbox('setValue', url);
                    updata_image_editor.hideDialog();
                }
            });
        });
    });
}

(function ($, K) {
    if (!K) throw "KindEditor未定义!";

    function create(target) {
        var opts = $.data(target, 'kindeditor').options;
        var editor = K.create(target, opts);
        $.data(target, 'kindeditor').options.editor = editor;
    }

    $.fn.kindeditor = function (options, param) {
        if (typeof options == 'string') {
            var method = $.fn.kindeditor.methods[options];
            if (method) {
                return method(this, param);
            }
        }
        options = options || {};
        return this.each(function () {

            if ($(this).attr('config_date') == 0) {
                config_date = ['fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link'];
            } else if ($(this).attr('config_date') == 1) {
                config_date = [
                    'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
                    'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                    'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                    'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                    'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
                    'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                    'anchor', 'link', 'unlink', '|', 'about'
                ];
            } else {
                config_date = ['fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link'];
            }

            var state = $.data(this, 'kindeditor');
            if (state) {
                $.extend(state.options, options);
            } else {
                state = $.data(this, 'kindeditor', {
                    options: $.extend(
                        {},
                        {
                            resizeType: 1,
                            allowPreviewEmoticons: false,
                            allowImageUpload: false,
                            items: config_date,
                            allowFileManager: true,
                            pasteType: ke_pasteType,
                            fileManagerJson: ke_fileManagerJson,
                            uploadJson: ke_uploadJson,
                            extraFileUploadParams: {
                                uid: ke_Uid
                            },
                            afterChange: function () {
                                this.sync();
                            },
                            afterBlur: function () {
                                this.sync();
                            }
                        },
                        $.fn.kindeditor.parseOptions(this), options)
                });
            }
            create(this);
        });
    }

    $.fn.kindeditor.parseOptions = function (target) {
        return $.extend({},
            $.parser.parseOptions(target, []));
    };

    $.fn.kindeditor.methods = {
        editor: function (jq) {
            return $.data(jq[0], 'kindeditor').options.editor;
        }
    };
    $.parser.plugins.push("kindeditor");
})(jQuery, KindEditor);
