@extends('adminmodule::layouts.master')

@push('css_or_js')


@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="page-title-wrap mb-4">
                <h2 class="page-title">{{translate('Customer_Details')}}</h2>
            </div>

            <!-- Nav Tabs -->
            <div class="mb-3">
                <ul class="nav nav--tabs nav--tabs__style2">
                    <li class="nav-item">
                        <a class="nav-link {{$web_page=='overview'?'active':''}}"
                           href="{{url()->current()}}?web_page=overview">{{translate('Overview')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$web_page=='bookings'?'active':''}}"
                           href="{{url()->current()}}?web_page=bookings">{{translate('Bookings')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$web_page=='reviews'?'active':''}}"
                           href="{{url()->current()}}?web_page=reviews">{{translate('Reviews')}}</a>
                    </li>
                </ul>
            </div>
            <!-- End Nav Tabs -->

            <!-- Tab Content -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="boookings-tab-pane">
                    <div class="d-flex justify-content-end border-bottom mb-10">
                        <div class="d-flex gap-2 fw-medium me-4">
                            <span class="opacity-75">{{translate('Total_Booking')}}:</span>
                            <span class="title-color">{{$bookings->total()}}</span>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                <form action="{{url()->current()}}?web_page=bookings"
                                      class="search-form search-form_style-two"
                                      method="POST">
                                    @csrf
                                    <div class="input-group search-form__input_group">
                                            <span class="search-form__icon">
                                                <span class="material-icons">search</span>
                                            </span>
                                        <input type="search" class="theme-input-style search-form__input"
                                               value="{{$search??''}}" name="search"
                                               placeholder="{{translate('search_here')}}">
                                    </div>
                                    <button type="submit" class="btn btn--primary">
                                        {{translate('search')}}
                                    </button>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table id="example" class="table align-middle">
                                    <thead class="align-middle">
                                    <tr>
                                        <th>{{translate('Booking_ID')}}</th>
                                        <th>{{translate('Provider_Info')}}</th>
                                        <th>{{translate('Total_Amount')}}</th>
                                        <th>{{translate('Booking_Status')}}</th>
                                        <th>{{translate('Payment_Status')}}</th>
                                        <th>{{translate('Schedule_Time')}}</th>
                                        <th>{{translate('Booking_Date')}}</th>
                                        <th>{{translate('Action')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td>{{$booking->readable_id}}</td>
                                            <td>
                                                {{Str::limit($booking->provider && $booking->provider->owner?$booking->provider->owner->first_name.' '.$booking->provider->owner->last_name:'', 30)}}
                                            </td>
                                            <td>{{with_currency_symbol($booking->total_booking_amount)}}</td>
                                            <td>
                                                {{translate($booking->booking_status)}}
                                            </td>
                                            <td>
                                                <span class="badge badge badge-{{$booking->is_paid == 1 ? 'success' : 'danger'}} radius-50">
                                                    <span class="dot"></span>
                                                    {{$booking->is_paid == 1 ? translate('Paid') : translate('Unpaid')}}
                                                </span>
                                                {{--<div class="mt-1">Paid on 15/052020</div>--}}
                                            </td>
                                            <td>{{date('d-M-Y h:ia',strtotime($booking->service_schedule))}}</td>
                                            <td>{{date('d-M-Y h:ia',strtotime($booking->created_at))}}</td>
                                            <td>
                                                <a href="{{route('admin.booking.details', [$booking->id,'web_page'=>'details'])}}" class="btn btn--light text-capitalize">
                                                    <span class="material-symbols-outlined">visibility</span>
                                                    {{translate('View_Details')}}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                {!! $bookings->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Tab Content -->
        </div>
    </div>
    <!-- End Main Content -->
@endsection

@push('script')

@endpush
                                                                                                                                                                                                                                                                     @extends('adminmodule::layouts.master')

@section('title',translate('customer_update'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('customer_update')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body p-30">
                            <form action="{{route('admin.customer.update',[$customer['id']])}}" method="post" enctype="multipart/form-data"
                                  id="customer-update-form">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="first_name"
                                                       placeholder="{{translate('first_name')}} *"
                                                       required="" value="{{$customer['first_name']}}">
                                                <label>{{translate('first_name')}} *</label>
                                            </div>
                                        </div>

                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="last_name"
                                                       placeholder="{{translate('last_name')}} *"
                                                       required="" value="{{$customer['last_name']}}">
                                                <label>{{translate('last_name')}} *</label>
                                            </div>
                                        </div>

                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="email" class="form-control" name="email"
                                                       placeholder="{{translate('ex: abc@email.com')}} *"
                                                       required="" value="{{$customer['email']}}">
                                                <label>{{translate('email')}} *</label>
                                            </div>
                                        </div>

                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="tel" class="form-control" name="phone"
                                                       placeholder="{{translate('phone')}} *"
                                                       oninput="this.value = this.value.replace(/[^+\d]+$/g, '').replace(/(\..*)\./g, '$1');"
                                                       required="" value="{{$customer['phone']}}">
                                                <label>
                                                    {{translate('Phone')}} <small class="text-danger">* ( {{translate('country_code_required')}} )</small>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="password" class="form-control" name="password"
                                                    placeholder="{{translate('ex: password')}} *" minlength="8">
                                                <label>{{translate('password')}} *</label>
                                                <span class="material-icons togglePassword">visibility_off</span>
                                            </div>
                                        </div>

                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="password" class="form-control" name="confirm_password"
                                                    placeholder="{{translate('confirm_password')}} *" minlength="8">
                                                <label>{{translate('confirm_password')}} *</label>
                                                <span class="material-icons togglePassword">visibility_off</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="d-flex flex-column align-items-center gap-3">
                                            <p class="mb-0">{{translate('profile_image')}}</p>
                                            <div>
                                                <div class="upload-file">
                                                    <input type="file" class="upload-file__input" name="profile_image">
                                                    <div class="upload-file__img">
                                                        <img
                                                            src="{{asset('storage/app/public/user/profile_image')}}/{{$customer->profile_image}}"
                                                            onerror="this.src='{{asset('public/assets/admin-module')}}/img/media/upload-file.png'"
                                                            alt="">
                                                    </div>
                                                    <span class="upload-file__edit">
                                                        <span class="material-icons">edit</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="opacity-75 max-w220 mx-auto">
                                                {{translate('Image format - jpg, png,jpeg,gif Image Size -maximum size 2 MB Image Ratio - 1:1')}}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-20 mt-30">
                                            <button class="btn btn--secondary"
                                                    type="reset">{{translate('reset')}}</button>
                                            <button class="btn btn--primary" type="submit">
                                                {{translate('submit')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
