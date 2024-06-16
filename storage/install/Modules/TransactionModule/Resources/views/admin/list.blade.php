@extends('adminmodule::layouts.master')

@section('title',translate('withdrawal_method_list'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div
                        class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                        <h2 class="page-title">{{translate('Withdrawal_method_List')}}</h2>
                        <a href="{{route('admin.withdraw.method.create')}}" class="btn btn--primary">+ {{translate('Add_method')}}</a>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="all-tab-pane">
                            <div class="card">
                                <div class="card-body">
                                    <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                        <form action="{{url()->current()}}?status={{$status}}"
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
                                    </div>

                                    <div class="table-responsive">
                                        <table id="example" class="table align-middle">
                                            <thead class="text-nowrap">
                                            <tr>
                                                <th>{{translate('SL')}}</th>
                                                <th>{{translate('Method_name')}}</th>
                                                <th>{{translate('Method_Fields')}}</th>
                                                <th>{{translate('Active_Status')}}</th>
                                                <th>{{translate('Default_Method')}}</th>
                                                <th>{{translate('Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($withdrawal_methods as $key=>$withdrawal_method)
                                            <tr>
                                                <td>{{$withdrawal_methods->firstitem()+$key}}</td>
                                                <td>{{$withdrawal_method['method_name']}}</td>
                                                <td>
                                                    @foreach($withdrawal_method['method_fields'] as $key=>$method_field)
                                                        <span class="badge badge-success opacity-75 fz-12 border border-white">
                                                            <b>{{translate('Name')}}:</b> {{translate($method_field['input_name'])}} |
                                                            <b>{{translate('Type')}}:</b> {{ $method_field['input_type'] }} |
                                                            <b>{{translate('Placeholder')}}:</b> {{ $method_field['placeholder'] }} |
                                                            <b>{{translate('Is Required')}}:</b> {{ $method_field['is_required'] ? translate('yes') : translate('no') }}
                                                        </span><br/>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <label class="switcher">
                                                        <input class="switcher_input"
                                                               onclick="route_alert_reload('{{route('admin.withdraw.method.status-update',[$withdrawal_method->id])}}','{{translate('want_to_update_status')}}')"
                                                               type="checkbox" {{$withdrawal_method->is_active?'checked':''}}>
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="switcher">
                                                        <input class="switcher_input"
                                                               onclick="route_alert_reload('{{route('admin.withdraw.method.default-status-update',[$withdrawal_method->id])}}','{{translate('want_to_make_default_method')}}')"
                                                               type="checkbox" {{$withdrawal_method->is_default?'checked':''}}>
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="table-actions">
                                                        <a href="{{route('admin.withdraw.method.edit',[$withdrawal_method->id])}}"
                                                           class="table-actions_edit demo_check">
                                                            <span class="material-icons">edit</span>
                                                        </a>

                                                        @if(!$withdrawal_method->is_default)
                                                            <button type="button"
                                                                    class="table-actions_delete bg-transparent border-0 p-0 demo_check"
                                                                    data-bs-toggle="modal" data-bs-target="#deleteAlertModal"
                                                                    @if(env('APP_ENV')!='demo')
                                                                    onclick="form_alert('delete-{{$withdrawal_method->id}}','{{translate('want_to_delete_this_method')}}?')"
                                                                    @endif
                                                                >
                                                                <span class="material-icons">delete</span>
                                                            </button>
                                                            <form action="{{route('admin.withdraw.method.delete',[$withdrawal_method->id])}}"
                                                                  method="post" id="delete-{{$withdrawal_method->id}}" class="hidden">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {!! $withdrawal_methods->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->
@endsection

@push('script')

@endpush
                                                                                                                    @extends('adminmodule::layouts.master')

@section('title',translate('transaction_list'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div
                        class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                        <h2 class="page-title">{{translate('transaction_list')}}</h2>
                    </div>

                    <div
                        class="d-flex flex-wrap justify-content-between align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                        <ul class="nav nav--tabs">
                            <li class="nav-item">
                                <a class="nav-link {{$trx_type=='all'?'active':''}}"
                                   href="{{url()->current()}}?trx_type=all">
                                    {{translate('all')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$trx_type=='debit'?'active':''}}"
                                   href="{{url()->current()}}?trx_type=debit">
                                    {{translate('debit')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$trx_type=='credit'?'active':''}}"
                                   href="{{url()->current()}}?trx_type=credit   ">
                                    {{translate('credit')}}
                                </a>
                            </li>
                        </ul>

                        <div class="d-flex gap-2 fw-medium">
                            <span class="opacity-75">{{translate('Total_Transactions')}}:</span>
                            <span class="title-color">{{$transactions->total()}}</span>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="all-tab-pane">
                            <div class="card">
                                <div class="card-body">
                                    <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                        <form action="{{url()->current()}}?trx_type={{$trx_type}}"
                                              class="search-form search-form_style-two"
                                              method="POST">
                                            @csrf
                                            <div class="input-group search-form__input_group">
                                            <span class="search-form__icon">
                                                <span class="material-icons">search</span>
                                            </span>
                                                <input type="search" class="theme-input-style search-form__input"
                                                       value="{{$search}}" name="search"
                                                       placeholder="{{translate('search_by_trx_id')}}">
                                            </div>
                                            <button type="submit"
                                                    class="btn btn--primary">{{translate('search')}}</button>
                                        </form>

                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <div class="dropdown">
                                                <button type="button"
                                                        class="btn btn--secondary text-capitalize dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                    <span class="material-icons">file_download</span> {{translate('download')}}
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{route('admin.transaction.download')}}?search={{$search}}&trx_type={{$trx_type}}">
                                                        {{translate('excel')}}
                                                    </a>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="example" class="table align-middle">
                                            <thead class="text-nowrap">
                                                <tr>
                                                    <th>{{translate('Transaction_ID')}}</th>
                                                    <th>{{translate('Transaction_Date')}}</th>
                                                    <th>{{translate('Transaction_From')}}</th>
                                                    <th>{{translate('Transaction_To')}}</th>
                                                    <th>{{translate('Debit')}}</th>
                                                    <th>{{translate('Credit')}}</th>
                                                    <th>{{translate('Balance')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($transactions as $transaction)
                                                <tr>
                                                    <td>{{$transaction->id}}</td>
                                                    <td>{{date('d-M-y H:iA', strtotime($transaction->created_at))}}</td>
                                                    <td>
                                                        @if($transaction?->from_user?->provider)
                                                            {{Str::limit($transaction->from_user->provider->company_name, 30)}} <br/>
                                                            <small class="opacity-75">{{translate($transaction?->from_user_account)}}</small>
                                                        @else
                                                            {{Str::limit($transaction?->from_user?->first_name.' '.$transaction?->from_user?->last_name, 30)}} <br/>
                                                            <small class="opacity-75">{{translate($transaction?->from_user_account)}}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($transaction->to_user->provider)
                                                            {{Str::limit($transaction->to_user->provider->company_name, 30)}} <br/>
                                                            <small class="opacity-75">{{translate($transaction->to_user_account)}}</small>
                                                        @else
                                                            {{Str::limit($transaction->to_user->first_name.' '.$transaction->to_user->last_name, 30)}} <br/>
                                                            <small class="opacity-75">{{translate($transaction->to_user_account)}}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{with_currency_symbol($transaction->debit)}}</td>
                                                    <td>{{with_currency_symbol($transaction->credit)}}</td>
                                                    <td>{{with_currency_symbol($transaction->balance)}}</td>
                                                </tr>
                                            @empty
                                                <tr class="text-center"><td colspan="7">{{translate('No data available')}}</td></tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {!! $transactions->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->
@endsection

@push('script')


@endpush
