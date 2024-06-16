@extends('adminmodule::layouts.master')

@section('title',translate('coupons'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/select.dataTables.min.css"/>
@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('coupons')}}</h2>
                    </div>

                    <div
                        class="d-flex flex-wrap justify-content-between align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                        <ul class="nav nav--tabs">
                            <li class="nav-item">
                                <a class="nav-link {{$discountType=='all'?'active':''}}"
                                   href="{{url()->current()}}?discount_type=all">
                                    {{translate('all')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$discountType=='service'?'active':''}}"
                                   href="{{url()->current()}}?discount_type=service">
                                    {{translate('service_wise')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$discountType=='category'?'active':''}}"
                                   href="{{url()->current()}}?discount_type=category">
                                    {{translate('category_wise')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$discountType=='mixed'?'active':''}}"
                                   href="{{url()->current()}}?discount_type=mixed">
                                    {{translate('mixed')}}
                                </a>
                            </li>
                        </ul>

                        <div class="d-flex gap-2 fw-medium">
                            <span class="opacity-75">{{translate('Total_Coupons')}}:</span>
                            <span class="title-color">{{$coupons->total()}}</span>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                <form action="{{url()->current()}}?discount_type={{$discountType}}"
                                      class="search-form search-form_style-two"
                                      method="POST">
                                    @csrf
                                    <div class="input-group search-form__input_group">
                                    <span class="search-form__icon">
                                        <span class="material-icons">search</span>
                                    </span>
                                        <input type="search" class="theme-input-style search-form__input"
                                               value="{{$search}}" name="search"
                                               placeholder="{{translate('search_here')}}">
                                    </div>
                                    <button type="submit"
                                            class="btn btn--primary">{{translate('search')}}</button>
                                </form>

                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    <div class="dropdown">
                                        @can('coupon_export')
                                            <button type="button"
                                                    class="btn btn--secondary text-capitalize dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                            <span
                                                class="material-icons">file_download</span> {{translate('download')}}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                <a class="dropdown-item"
                                                   href="{{route('admin.coupon.download')}}?search={{$search}}">
                                                    {{translate('excel')}}
                                                </a>
                                            </ul>
                                        @endcan
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="example" class="table align-middle">
                                    <thead class="text-nowrap">
                                    <tr>
                                        <th>{{translate('SL')}}</th>
                                        <th>{{translate('title')}}</th>
                                        <th>{{translate('coupon_type')}}</th>
                                        <th>{{translate('coupon_code')}}</th>
                                        <th>{{translate('discount_type')}}</th>
                                        <th>{{translate('zones')}}</th>
                                        <th>{{translate('Limit_per_user')}}</th>
                                        @can('coupon_manage_status')
                                            <th>{{translate('status')}}</th>
                                        @endcan
                                        @canany(['coupon_delete', 'coupon_update'])
                                            <th>{{translate('action')}}</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($coupons as $key=>$item)
                                        <tr>
                                            <td>{{$coupons->firstitem()+$key}}</td>
                                            <td>{{$item->discount->discount_title}}</td>
                                            <td>{{str_replace('_',' ',$item->coupon_type)}}</td>
                                            <td>{{$item->coupon_code}}</td>
                                            <td>{{$item->discount->discount_type}}</td>
                                            <td>
                                                @foreach($item->discount->zone_types as $type)
                                                    {{$type->zone?$type->zone->name.',':''}}
                                                @endforeach
                                            </td>
                                            <td>{{$item->discount?->limit_per_user}}</td>
                                            @can('coupon_manage_status')
                                                <td>

                                                    <label class="switcher">
                                                        <input class="switcher_input"
                                                               data-status="{{$item->id}}"
                                                               type="checkbox" {{$item->is_active?'checked':''}}>
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </td>
                                            @endcan
                                            @canany(['coupon_delete', 'coupon_update'])
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        @can('coupon_update')
                                                            <a href="{{route('admin.coupon.edit',[$item->id])}}"
                                                               class="action-btn btn--light-primary"
                                                               style="--size: 30px">
                                                                <span class="material-icons">edit</span>
                                                            </a>
                                                        @endcan
                                                        @can('coupon_delete')
                                                            <button type="button" data-id="{{$item->id}}"
                                                                    class="action-btn btn--danger  delete_section"
                                                                    style="--size: 30px">
                                                                <span class="material-symbols-outlined">delete</span>
                                                            </button>
                                                            <form action="{{route('admin.coupon.delete',[$item->id])}}"
                                                                  method="post" id="delete-{{$item->id}}"
                                                                  class="hidden">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                {!! $coupons->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.js"></script>
    <script>
        "use Strict";

        $('.switcher_input').on('click', function () {
            let itemId = $(this).data('status');
            let route = '{{ route('admin.coupon.status-update', ['id' => ':itemId']) }}';
            route = route.replace(':itemId', itemId);
            route_alert(route, '{{ translate('want_to_update_status') }}');
        })

        $('.delete_section').on('click', function () {
            let itemId = $(this).data('id');
            form_alert('delete-' + itemId, '{{ translate('want_to_delete_this') }}');
        })

        $(document).ready(function () {
            $('.js-select').select2();
        });
    </script>
    <script src="{{asset('public/assets/admin-module')}}/plugins/dataTables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/admin-module')}}/plugins/dataTables/dataTables.select.min.js"></script>
@endpush
