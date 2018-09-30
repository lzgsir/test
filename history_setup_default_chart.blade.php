@extends('layouts.manage')
@section('html_title','配置默认图')
@section('html_header')
    @@parent
@endsection
@section('page_header')
    <h1>
       统计查询
        <small id="type">配置默认图<a href="{{url('personal/manual#7-4-1-')}}" target="_blank" data-toggle="tooltip" data-placement="right" title="用户可以配置循环水、反渗透默认图，点击查看详情。"><i class="fa fa-info-circle"></i></a></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('statistics/defaultCharts')}}"><i class="fa fa-home"></i>统计查询</a></li>
        <li><a href="{{url('statistics/defaultCharts')}}"><i class="fa fa-dashboard"></i>默认图配置</a></li>
        <li class="active">配置默认图</li>
    </ol>
@endsection
@section('page_content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="border-top: none">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="box-header" style="padding-top: 5px">
                        <div class="col-sm-12" style="padding-bottom: 10px">
                            <div id="div" class="form-inline text-center" style="float: left;"></div>
                        </div>
                        <div class="col-sm-2">
                            <ul class="nav nav-stacked">
                                @forelse($chartItem as $charKey=>$charValue)
                                    @if($charKey == "cyclicWater")
                                        <li class="active"><a data-toggle="tab" href="#{{$charKey}}">{{$charValue['name']}}</a></li>
                                    @else
                                        <li class=""><a data-toggle="tab" href="#{{$charKey}}">{{$charValue['name']}}</a></li>
                                    @endif
                                @empty
                                @endforelse
                            </ul>
                        </div>
                        <div class="col-sm-10">
                            <div class="tab-content">
                                @forelse($chartItem as $charKey=>$charValue)
                                    <?php
                                    if($charKey == 'cyclicWater'){
                                        $active = "in active";
                                    }else{
                                        $active = "";
                                    }
                                    ?>
                                    <form id="{{$charKey}}" class="callout tab-pane fade {{$active}} col-sm-12 bg-gray disabled" style="min-height: 100px">
                                        <div class="row">
                                            @foreach($charValue['item'] as $itemKey=>$itemValue)
                                                <div class="col-sm-2 ">
                                                    @if($itemValue['type'] != 1)
                                                        <label class="pull-left">{{$itemValue['name']}}:</label>
                                                        <select name="{{$itemValue['value']}}" class="form-control select-sm" alias="item"></select>
                                                    @else
                                                        <label class="pull-left">{{$itemValue['name']}}:</label>
                                                        <a class="addItem" ><i class="fa fa-plus-square pull-right"></i></a>
                                                        <a class="delItem" ><i class="fa fa-trash pull-right"></i></a>
                                                        <select name="{{$itemValue['value']}}[]" class="form-control select-sm" alias="item"></select>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="col-sm-12 bg-gray disabled" style="margin-top: 5px">
                                            <div class="btn-group pull-left" data-toggle="buttons">
                                                <label class="btn btn-primary active"  style="width: 65px" >
                                                    <input type="radio" name="{{$charKey}}"  value="{{strtotime(date("Y-m-d",time()))-7*86400}}"> 1周
                                                </label>
                                                <label class="btn btn-primary" style="width: 65px" >
                                                    <input type="radio" name="{{$charKey}}" value="{{strtotime(date("Y-m-d",time()))-14*86400}}"> 2周
                                                </label>
                                                <label class="btn btn-primary" style="width: 65px">
                                                    <input type="radio" name="{{$charKey}}" value="{{strtotime(date("Y-m-d",time()))-30*86400}}"> 1个月
                                                </label>
                                                <label class="btn btn-primary" style="width: 65px">
                                                    <input type="radio" name="{{$charKey}}" value="{{strtotime(date("Y-m-d",time()))-90*86400}}"> 3个月
                                                </label>
                                                <label class="btn btn-primary" style="width: 65px">
                                                    <input type="radio" name="{{$charKey}}" value="{{strtotime(date("Y-m-d",time()))-365*86400}}"> 1 年
                                                </label>
                                            </div>
                                            <a class="btn btn-info pull-left" onclick="preview('{{$charKey}}')" style="margin-left: 10px;text-decoration:none;">预览</a>
                                            <a class="btn btn-primary pull-right" onclick="upCode('{{$charKey}}')" style="text-decoration:none;">保存配置</a>
                                        </div>
                                        {!! csrf_field() !!}
                                    </form>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div id="chart" class="box-body"></div>
                <div class="box-footer"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        填写图组名称
                    </h4>
                </div>
                <div class="box-header with-border">
                    <div class="form-horizontal">
                        <div class="col-sm-2">
                            <label class="control-label">名称</label>
                        </div>
                        <div class="col-sm-10">
                            <input id="tag_name" name="tag_name" class="form-control">
                            <input id="code" name="code" type="hidden">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="saveInfo()">提交</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
@endsection
@section('html_footer')
    @@parent
    <script src="{{ asset('js/echarts.min.js') }}"></script>
    <script>
        activeTopNav('{{ url('statistics/defaultCharts')}}');
        activeSidebarMenu("{{url('statistics/defaultChartsList')}}");
        function preview(type){
            var form = $("#"+type).serializeArray();
            var start_time=$(".btn.btn-primary.active").find("input[name='"+type+"']").val();
            $.get("{{url('statistics/preview')}}"+"?type="+type+"&start_time="+start_time,form,function(result){
                $("#chart").empty();
                $.each(result,function (i,v) {
                    $("#chart").append("<div id='chart-"+i+"' class='col-sm-6' style='height: 300px'>");
                    var myChart = echarts.init(document.getElementById('chart-'+i));
                    v['tooltip'].formatter=function (params){
                        var name='';
                        name=params[0].name+'<br/>';
                        $.each(params,function(j,k){
                            name=name+'<font color="'+k.color+'">●</font>'+k.seriesId+':'+k.value+'<br/>';
                        });
                        return name;
                    };
                    var option = v;
                    myChart.setOption(option);
                });
            });
        }
        //添加部件
        $(".addItem").click(function(){
            $(this).parent().parent().append($(this).parent().clone(true));
        });
        //删除部件
        $(".delItem").click(function(){
            $(this).parent().remove();
        });
        //菜单联动
        $(function(){
            $.get("{{url('statistics/defaultChartsMenu')}}",function (result) {
                var data = result;
                var oDiv = document.getElementById("div");
                oDiv.innerHTML = "";
                var ul=document.createElement("ul");
                var customer = document.createElement("select");
                customer.setAttribute("name","customer");
                customer.setAttribute("class","form-control");
                var group = document.createElement("select");
                group.setAttribute("name","group");
                group.setAttribute("id","group");
                group.setAttribute("class","form-control");
                var item = $("select[alias='item']");
                oDiv.appendChild(customer);
                oDiv.appendChild(group);
                customer.options[0] = new Option("请选择客户", "0");
                group.options[0] = new Option("请选择系统", "0");
                item.append("<option value='0'>请选择指标项</option>");
                for (var i = 0; i < data.length; i++) {
                    customer.options[customer.length] = new Option(data[i].name, data[i].value);
                    customer.onchange = function() {
                        group.options.length = 0;
                        group.options[group.length] = new Option("请选择系统", "0");
                        var item = $("select[alias='item']");
                        item.empty();
                        item.append("<option value='0'>请选择指标项</option>");
                        for (var j = 0; j < data[customer.selectedIndex - 1].customer.length; j++) {
                            group.options[group.length] = new Option(data[customer.selectedIndex - 1].customer[j].name, data[customer.selectedIndex - 1].customer[j].value);
                            group.onchange = function() {
                                var item = $("select[alias='item']");
                                item.empty();
                                item.append("<option value='0'>请选择指标项</option>");
                                for (var k = 0; k < data[customer.selectedIndex - 1].customer[group.selectedIndex - 1].group.length; k++) {
                                    var itemData = '';
                                    for (var m = 0;m<data[customer.selectedIndex - 1].customer[group.selectedIndex - 1].group[k].template.length; m++){
                                        itemData = itemData +"<option value='"+data[customer.selectedIndex - 1].customer[group.selectedIndex - 1].group[k].template[m].value+"'>"+
                                            data[customer.selectedIndex - 1].customer[group.selectedIndex - 1].group[k].template[m].name+
                                            "</option>";
                                    }
                                    var template = "<optgroup label='"+data[customer.selectedIndex - 1].customer[group.selectedIndex - 1].group[k].name+"'>"+itemData+"</optgroup>";
                                    item.append(template);
                                }
                            }
                        }
                    }
                }
            },'json');
        });
        function upCode(code){
            $("#code").val(code);
            $("#myModal").modal();
        }
        //保存配置
        function saveInfo(){
            var code = $("#code").val();
            var form = $("#"+code).serializeArray();
            form.unshift({name:'code',value:$("#code").val()},{name:'system_id',value:$("#group").val()},{name:'tag_name',value:$("#tag_name").val()});
            $.post("{{url('statistics/saveInfo')}}",form,function(result){
                if(result['state'] == 200){
                    window.location.href="{{url('statistics/defaultChartsList')}}";
                }else{
                    alert("创建失败！"+result['err']);
                    $("#myModal").modal("hide");
                }
            });
        }
    </script>
@endsection