@extends('layouts.admin.app')

@section('title', translate('table order'))

@push('css_or_js')
@endpush

@section('content')
    <div class="container-fluid py-5">

        <!-- Page Header -->
        <div class="">
            <div class="row align-items-center mb-3">
                <div class="col-3">
                    <h1 class="">{{translate('table')}} {{'orders'}} <span class="badge badge-soft-dark ml-2"></span></h1>
                </div>
                <div class="col-3 ">
                    <!-- Select -->
                    <div id="invoice_btn" class="{{ is_null($table_id) ? 'd-none' : '' }}">
                        <a class="btn btn-sm btn-white float-right" href="{{ route('admin.table.order.running.invoice', ['table_id' => $table_id]) }}"><i class="tio-print"></i> {{translate('invoice')}}</a>
                    </div>
                    <!-- End Select -->
                </div>
                <div class="col-3">
                    <!-- Select -->
                    <select class="custom-select custom-select-sm text-capitalize" name="branch" onchange="filter_branch_orders(this.value)">
                        <option disabled>--- {{translate('select')}} {{translate('branch')}} ---</option>
                        @foreach(\App\Model\Branch::all() as $branch)
                            <option
                                value="{{$branch['id']}}" {{session('branch_filter')==$branch['id']?'selected':''}}>{{$branch['name']}}</option>
                        @endforeach
                    </select>
                    <!-- End Select -->
                </div>
                <div class="col-3">
                    <!-- Select -->
                    <select class="custom-select custom-select-sm text-capitalize" name="table" id="select_table">
                        <option disabled selected>--- {{translate('select')}} {{translate('table')}} ---</option>
                        @foreach($tables as $table)
                            <option value="{{$table['id']}}" {{$table_id==$table['id'] ? 'selected' : ''}}>{{translate('Table')}} - {{$table['number']}}</option>
                        @endforeach
                    </select>
                    <!-- End Select -->
                </div>
            </div>
        </div>
        <div id="all_running_order">
            <div class="card">
                <div class="card-body p-3">
                    <div class="table-responsive datatable-custom">
                        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               style="width: 100%">
                            <thead class="thead-light">
                            <tr>
                                <th class="">
                                    {{translate('#')}}
                                </th>
                                <th class="table-column-pl-0">{{translate('order')}}</th>
                                <th>{{translate('date')}}</th>
                                <th>{{translate('branch')}}</th>
                                <th>{{translate('table')}}</th>
                                <th>{{translate('payment')}} {{translate('status')}}</th>
                                <th>{{translate('total')}}</th>
                                <th>{{translate('order')}} {{translate('status')}}</th>
                                <th>{{translate('number of people')}}</th>
                                <th>{{translate('actions')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($orders as $key=>$order)

                                <tr class="status-{{$order['order_status']}} class-all">
                                    <td class="">
                                        {{$orders->firstitem()+$key}}
                                    </td>
                                    <td class="table-column-pl-0">
                                        <a href="{{route('admin.orders.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
                                    </td>
                                    <td>{{date('d M Y',strtotime($order['created_at']))}}</td>
                                    <td>
                                        <label class="badge badge-soft-primary">{{$order->branch?$order->branch->name:'Branch deleted!'}}</label>
                                    </td>
                                    <td>
                                        @if($order->table)
                                            <label class="badge badge-soft-info">{{translate('table')}} - {{$order->table->number}}</label>
                                        @else
                                            <label class="badge badge-soft-info">{{translate('table deleted')}}</label>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->payment_status=='paid')
                                            <span class="badge badge-soft-success">
                                        <span class="legend-indicator bg-success"></span>{{translate('paid')}}</span>
                                        @else
                                            <span class="badge badge-soft-danger">
                                        <span class="legend-indicator bg-danger"></span>{{translate('unpaid')}}</span>
                                        @endif
                                    </td>
                                    <td>{{ \App\CentralLogics\Helpers::set_symbol($order['order_amount']) }}</td>
                                    <td class="text-capitalize">
                                        @if($order['order_status']=='pending')
                                            <span class="badge badge-soft-info ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-info"></span>{{translate('pending')}}</span>
                                        @elseif($order['order_status']=='confirmed')
                                            <span class="badge badge-soft-info ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-info"></span>{{translate('confirmed')}}</span>
                                        @elseif($order['order_status']=='cooking')
                                            <span class="badge badge-soft-info ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-info"></span>{{translate('cooking')}}</span>
                                        @elseif($order['order_status']=='done')
                                            <span class="badge badge-soft-info ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-info"></span>{{translate('done')}}</span>
                                        @elseif($order['order_status']=='completed')
                                            <span class="badge badge-soft-info ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-info"></span>{{translate('completed')}}</span>
                                        @elseif($order['order_status']=='processing')
                                            <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-warning"></span>{{translate('processing')}}</span>
                                        @elseif($order['order_status']=='out_for_delivery')
                                            <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-warning"></span>{{translate('out_for_delivery')}}</span>
                                        @elseif($order['order_status']=='delivered')
                                            <span class="badge badge-soft-success ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-success"></span>{{translate('delivered')}}</span>
                                        @else
                                            <span class="badge badge-soft-danger ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-danger"></span>{{str_replace('_',' ',$order['order_status'])}}</span>
                                        @endif
                                    </td>
                                    <td>{{$order['number_of_people']}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-white"
                                               href="{{route('admin.orders.details',['id'=>$order['id']])}}"><i
                                                    class="tio-visible"></i> {{translate('view')}}</a>
                                        </div>
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <!-- Pagination -->
                    <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                        <div class="col-sm-auto">
                            <div class="d-flex justify-content-center justify-content-sm-end">
                                {!! $orders->links() !!}
                            </div>
                        </div>
                    </div>
                    <!-- End Pagination -->
                </div>
            </div>
        </div>

    </div>

@endsection

@push('script_2')
    <script>
        function filter_branch_orders(id) {
            location.href = '{{url('/')}}/admin/orders/branch-filter/' + id;
        }
    </script>
    <script>
        $(document).ready(function (){
            $('#select_table').on('change', function (){
                location.href = '{{route('admin.table.order.running')}}' + '?table_id=' + $(this).val();

            });
        });
    </script>

@endpush
