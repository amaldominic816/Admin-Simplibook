@extends('adminmodule::layouts.master')

@section('title',translate('Add_fund'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('add_Fund')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body p-30">
                            <form action="{{route('admin.customer.wallet.add-fund')}}" method="post" enctype="multipart/form-data"
                                  id="customer-fund-form">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-30">
                                            <select class="js-select" name="user_id" id="user_id" required>
                                                <option selected disabled>{{translate('Select_customer')}}</option>
                                                @foreach($users as $user)
                                                    <option value="{{$user->id}}" {{$user->id == old('user_id') ? 'selected' : ''}}>
                                                        {{$user->first_name.' '.$user->last_name}} ({{$user->phone}})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" name="amount" id="amount"
                                                       placeholder="{{translate('amount')}}"
                                                       required value="{{old('amount')}}"
                                                        min="0" max="99999999999999999999" step="any">
                                                <label>{{translate('Amount')}} *</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="reference"
                                                       placeholder="{{translate('Reference')}}"
                                                       value="{{old('reference')}}"
                                                       maxlength="100">
                                                <label>{{translate('Reference (Optional)')}}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn--primary" type="button"
                                                    onclick="fund_alert('customer-fund-form')">
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
    <script>
        function fund_alert(id) {
            if ($("#amount").val() <= 0) {
                toastr.error("{{translate('Amount can not be less than or equal to zero')}}", {CloseButton: true, ProgressBar: true});
                return false;
            }
            var message = '{{translate('Do you really want to add fund ')}}' + $("#amount").val() + " {{currency_code()}} to " + $('#user_id').find(":selected").text();
            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: message,
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonColor: 'var(--c2)',
                confirmButtonColor: 'var(--c1)',
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#' + id).submit()
                }
            })
        }
    </script>
@endpush
                                                                                                                                                                                                                                                                                                                                                                                                                                                                      @extends('adminmodule::layouts.master')

@section('title',translate('customer_add'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('add_new_customer')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body p-30">
                            <form action="{{route('admin.customer.store')}}" method="post" enctype="multipart/form-data"
                                  id="customer-add-form">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="first_name"
                                                       placeholder="{{translate('first_name')}} *"
                                                       required="" value="{{old('first_name')}}">
                                                <label>{{translate('first_name')}} *</label>
                                            </div>
                                        </div>

                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="last_name"
                                                       placeholder="{{translate('last_name')}} *"
                                                       required="" value="{{old('last_name')}}">
                                                <label>{{translate('last_name')}} *</label>
                                            </div>
                                        </div>

                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="email" class="form-control" name="email"
                                                       placeholder="{{translate('ex: abc@email.com')}} *"
                                                       required="" value="{{old('email')}}">
                                                <label>{{translate('email')}} *</label>
                                            </div>
                                        </div>

                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="tel" class="form-control" name="phone"
                                                    oninput="this.value = this.value.replace(/[^+\d]+$/g, '').replace(/(\..*)\./g, '$1');"
                                                    placeholder="{{translate('phone')}} *"
                                                    required="" value="{{old('phone')}}">
                                                <label>
                                                    {{translate('Phone')}}
                                                </label>
                                                <small class="text-danger mt-1 d-flex">* ( {{translate('country_code_required')}} )</small>
                                            </div>
                                        </div>

                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="password" class="form-control" name="password"
                                                    placeholder="{{translate('ex: password')}} *"
                                                    required="" value="{{old('password')}}" minlength="8">
                                                <label>{{translate('password')}} *</label>
                                                <span class="material-icons togglePassword">visibility_off</span>
                                            </div>
                                        </div>

                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="password" class="form-control" name="confirm_password"
                                                    placeholder="{{translate('ex: Confirm_Password')}} *"
                                                    required="" minlength="8">
                                                <label>{{translate('Confirm_Password')}} *</label>
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
                                                            src="{{asset('public/assets/admin-module')}}/img/media/upload-file.png"
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
