@extends('layouts.admin')
@section('css')
    <link href="{{asset('vendors/iCheck/custom.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/ueditor/third-party/webuploader/webuploader.css')}}">
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

    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>编辑资讯</h5>
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
                        <form class="form-horizontal" method='post' action='{{url("business/article",[$info->id])}}' id="createForm">
                            {{csrf_field()}}
                            {{method_field('PUT')}}
                            <div class='tab-content' >
                                <div class="form-group">
                                    <label for="brand_name" class="col-sm-2 control-label">标题</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="article_title" name='article_title' value="{{$info->article_title}}" placeholder="文章标题" required>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label for="class_ids" class="col-sm-2 control-label">资讯分类</label>
                                    <div class="col-sm-10">
                                        <select class="form-control selectpicker" name="ac_id" required {{--multiple--}}>
                                            @if(count($class_list))
                                                @foreach($class_list as $val)
                                                    <option value="{{$val->id}}" @if($info->ac_id == $val->id) selected @endif>{{$val->ac_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label for="brand_en_name" class="col-sm-2 control-label">链接</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control"  id="article_url" name='article_url' value="{{$info->article_url}}" placeholder="文章链接">
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label for="brand_en_name" class="col-sm-2 control-label">排序</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control"  id="article_sort" name='article_sort' value="{{$info->article_sort}}" placeholder="排序">
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label for="tags" class="col-sm-2 control-label">显示</label>
                                    <div class="col-sm-10 ">
                                        <label class="radio-inline">
                                            <input type="radio" name="article_show" @if($info->article_show == 1) checked @endif  value="1"> 是
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="article_show" @if($info->article_show == 0) checked @endif value="0"> 否
                                        </label>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <h3>文章内容</h3>
                                <div class="form-group">
                                    <label for="unit" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <textarea  id="article_content" name="article_content" style="height: 240px;">{{$info->article_content}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="hidden" name="publisher_id" value="{{$publisher_id}}">
                                        <button type="submit" class="btn btn-primary "> 提 交 </button>
                                        <a href="{{url('business/article/index')}}" class="btn btn-info">返回列表</a>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script type="text/javascript" charset="utf-8" src="{{asset('vendors/ueditor/ueditor.config.js')}}"></script>
    <script type="text/javascript" charset="utf-8" src="{{asset('vendors/ueditor/ueditor.all.min.js')}}"> </script>
    <script type="text/javascript" src="{{asset('vendors/ueditor/third-party/webuploader/webuploader.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendors/jquery.form.js')}}"></script>
    <script>
        $(function(){
            $("#createForm").submit(function(){
                $(this).ajaxSubmit(function(e) {
                    if(e.status == 1){
                        showToastr('success',e.msg);
                        setTimeout(function(){
                            window.location.href='{{url('business/article/index')}}';
                        },1500);
                    }else{
                        showToastr('error',e.msg);
                    }
                });
                return false;
            });

            //富文本
            UE.getEditor('article_content');

            var uploadConfig={
                auto: true,
                swf: '{{asset('vendors/ueditor/third-party/webuploader/Uploader.swf')}}',
                server: '{{url("admin/goods/upload")}}',
                formData : {_token:'{{csrf_token()}}',path:'brand'},
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png'
                }
            };
        });
    </script>
@endsection