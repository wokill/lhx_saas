var TableDatatablesAjax = function() {
    var datatableAjax = function(){
        dt = $('.dataTablesAjax');
        params={ '_token':$('meta[name="_token"]').attr('content')};
        ajax_datatable = dt.DataTable({
            "processing": true,
            "serverSide": true,
            "searching" : true,
            "searchDelay": 800,
            "search": {
                "regex": true
            },
            "ajax": {
                'url' : '/business/goods_common/getdata',
                type:'POST',
                data:function(param){
                    var data = Object.assign(param,params);
                    return data;
                }
            },
            ordering:false,
                "pagingType": "full_numbers",
            "dom" : '<"html5buttons"B>lTfgitp',
            "buttons": [
                {extend: 'copy',title: '商品库'},
                {extend: 'csv',title: '商品库'},
                {extend: 'excel', title: '商品库'},
                {extend: 'pdf', title: '商品库'},
                {extend: 'print',
                    customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ],
            "columns": [
                {
                    "data": "ck",
                    "name" : "ck",
                    "orderable" : false,
                },
                {
                    "data": "name",
                    "name": "name",
                    "orderable" : false,
                },
                {
                    "data": "brand",
                    "name": "brand",
                    "orderable" : false,
                },
                {
                    "data": "unit",
                    "name": "unit",
                    "orderable" : false,
                },
                {
                    "data": "status",
                    "name": "status",
                    "orderable" : false,
                },
                {
                    "data": "action",
                    "name": "action",
                    "type": "html",
                    "orderable" : false,
                },
            ],
            "drawCallback": function( settings ) {
                ajax_datatable.$('.tooltips').tooltip( {
                    placement : 'top',
                    html : true
                });
            },
            "language": {
                url: '/admin/i18n'
            }
        }).on('init.dt', function (){

            var select=$('<select name="status" class="form-control form-filter" style="margin-right: 8px;">'+
                '<option value="">状态（全部）</option>'+
                '<option value="1">未添加</option>'+
                '<option value="2">已添加</option>'+
                '</select>').prependTo('#DataTables_Table_0_filter').on('change',function(){
                var val = $.fn.dataTable.util.escapeRegex(
                    $(this).val()
                );
                params.status = val;
                ajax_datatable.draw();
            });

        });;

    };
    return {
        init : datatableAjax
    }
}();
$(function () {
    TableDatatablesAjax.init();
});