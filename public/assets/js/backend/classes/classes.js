define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'classes/classes/index',
                    add_url: 'classes/classes/add',
                    edit_url: 'classes/classes/edit',
                    del_url: 'classes/classes/del',
                    multi_url: 'classes/classes/multi',
                    import_url: 'classes/classes/import',
                    table: 'classes',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                searchFormVisible: true,//显示通用搜索
                searchFormTemplate: 'customformtpl',//对应index.html模板中的ID
                showToggle: false,
                showColumns: false,
                showExport: false,
                search:false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'year', title: __('Year')},
                        {field: 'semester', title: __('Semester'), searchList: {"1":__('Semester 1'),"2":__('Semester 2')}, formatter: Table.api.formatter.normal,custom:{1:'primary', 2:'primary'}},
                        {field: 'category.name', title: __('Category.name')},
                        {field: 'user.username', title: __('User.username')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});