@extends('layouts.admin')
@section('css')
    <link href="{{asset('vendors/dataTables/datatables.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>信息管理</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('admin/dash')}}">{!!trans('admin/breadcrumb.home')!!}</a>
                </li>
                <li class="active">
                    <strong>平台资讯</strong>
                </li>
            </ol>
        </div>
        @permission(config('admin.permissions.brand.create'))
        <div class="col-lg-2">
            <div class="title-action">
                <a href="{{url('business/article/create')}}" class="btn btn-info">添加资讯</a>
            </div>
        </div>
        @endpermission
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>资讯列表</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        @include('flash::message')
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTablesAjax" >
                                <thead>
                                <tr>
                                    <th><input type="checkbox" class="allCheck"></th>
                                    <th>文章标题</th>
                                    <th>资讯分类</th>
                                    <th>排序</th>
                                    <th>显示</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('vendors/dataTables/datatables.min.js')}}"></script>
    <script src="{{asset('vendors/layer/layer.js')}}"></script>
    <script src="{{asset('business/js/article/article.js')}}"></script>
    <script type="text/javascript">
        $(function(){
            $('.allCheck').on('click',function () {
                $('input[name="checkbox"]').prop('checked',this.checked);
            });
        })

        function deleted(id) {
            layer.confirm('{{trans('admin/alert.deleteTitle')}}', {
                btn: ['{{trans('admin/action.actionButton.destroy')}}', '{{trans('admin/action.actionButton.no')}}'],
                icon: 5,
            },function(index){
                $.post('{{url('business/article/destroy')}}',{
                    '_token':'{{csrf_token()}}',
                    id:id
                },function (data) {
                    layer.close(index);
                    if(data.status == 1){
                        showToastrReload('success',data.msg,location.href);
                    }else{
                        showToastrReload('error',data.msg);
                    }
                });

            });

        }
    </script>
@endsection