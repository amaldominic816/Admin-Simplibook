<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{translate('Provider_Registration')}}</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>

    <link rel="shortcut icon"
          href="{{asset('storage/app/public/business')}}/{{(business_config('business_favicon', 'business_information'))->live_values ?? null}}"/>

    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
        rel="stylesheet"/>

    <link href="{{asset('public/assets/provider-module')}}/css/material-icons.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/bootstrap.min.css"/>
    <link rel="stylesheet"
          href="{{asset('public/assets/provider-module')}}/plugins/perfect-scrollbar/perfect-scrollbar.min.css"/>

    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/style.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/toastr.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin-module/css/intlTelInput.css')}}/">
</head>

<body>
<div class="preloader"></div>

<div class="dark-support">
    <div class="register-wrap style__two">
        <div class="register-left d-flex justify-content-center align-items-center bg-center" data-bg-img="{{asset('public/assets/provider-module')}}/img/media/login-bg.png">
            <div class="tf-box d-flex flex-column gap-3 align-items-center justify-content-center p-5 mx-5 h-75">
                <div class="px-xl-5 text-center">
                    <img class="login-img login-logo mb-2"
                    src="{{onErrorImage(
                                business_config('business_logo', 'business_information')->live_values??'',
                                asset('storage/app/public/business').'/' . business_config('business_logo', 'business_information')->live_values??'',
                                asset('public/assets/placeholder.png') ,
                                'business/')}}"
                        alt="{{ translate('logo') }}">
                    <h2 class="text-center text-dark mt-2">Your <strong class="c1">Right <br> Choice</strong> for On <br> Demand Business</h2>
                </div>
            </div>
        </div>

        <div class="register-right-wrap bg-white">
            <div class="register-right mx-auto p-3">
                <div class="text-center mb-5 d-flex flex-column gap-2 mt-3">
                    <h2 class="c1 fw-medium">{{translate('Self_Registration')}}</h2>
                    {{-- <h5 class="text-uppercase c1 mb-3">{{ (business_config('business_name', 'business_information'))->live_values ?? 'Demandium' }}</h5> --}}
                    <p>{{translate('Sign up to provide service broadly')}}</p>
                </div>

                <form action="{{route('provider.auth.sign-up-submit')}}" method="POST"
                        enctype="multipart/form-data" id="register-vertical-steps">
                    @csrf

                    <h3>{{translate('Step 1')}}</h3>
                    <section>
                        <div class="">
                            <h5 class="border-bottom mb-4 pb-2">{{translate('General_Information')}}</h5>
                            <div class="mb-4">
                                <div class="mb-30">
                                    <div class="form-floating form-floating__icon">
                                        <input type="text" class="form-control"
                                                value="{{old('company_name')}}" name="company_name"
                                                placeholder="{{translate('Company_Name')}}">
                                        <label>{{translate('Company_Name')}} <span class="text-danger">*</span></label>
                                        <span class="material-icons">apartment</span>
                                    </div>
                                </div>
                                <div class="mb-30">
                                    <div class="form-floating form-floating__icon">
                                        <input type="email" id="company_email" class="form-control"
                                                value="{{old('company_email')}}" name="company_email"
                                                placeholder="{{translate('email')}}">
                                        <label>{{translate('email')}}</label>
                                        <span class="material-icons">mail</span>
                                    </div>
                                </div>
                                <div class="mb-30">
                                    <div class="">
                                        <input type="tel" class="form-control"
                                                value="{{old('company_phone')}}" name="company_phone"
                                                id="company_phone"
                                                placeholder="{{translate('123 456 789')}}">
                                    </div>
                                </div>
                                <div class="mb-30">
                                    <div class="form-floating form-floating__icon">
                                            <input type="text" class="form-control" name="company_address"
                                                        placeholder="{{translate('Address')}}" value="{{old('company_address')}}">
                                        <label>{{translate('Address')}}</label>
                                        <span class="material-icons">location_on</span>
                                    </div>
                                </div>
                                <div class="mb-30">
                                    <h5 class="mb-3">{{ translate('company_logo') }} (1:1) <span class="text-text-danger">*</span></h5>
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                        <div>
                                            <div class="form-error-wrap upload-file">
                                                <input type="file" class="upload-file__input"
                                                        name="logo" accept=".jpg,.png,.jpeg,.gif">
                                                <span class="upload-file__edit">
                                                        <span class="material-icons">edit</span>
                                                    </span>
                                                <div class="upload-file__img">
                                                    <img
                                                        src="{{asset('public/assets/provider-module')}}/img/media/upload-file.png"
                                                        alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="opacity-75 max-w220">{{translate('Image_format_-_jpg,_png,_jpeg,_gif_Image_Size_-_maximum_size_2_MB_Image_Ratio_-_1:1')}}</p>
                                    </div>
                                </div>

                                <div class="border-bottom d-flex align-items-center justify-content-between gap-2 pb-2 mb-4">
                                    <h5>{{translate('Contact Person Information')}}</h5>
                                    <div class="d-flex gap-2 align-items-center">
                                        <label for="sameAsGI">{{translate('Same as general info')}}</label>
                                        <input type="checkbox" value="" id="sameAsGI">
                                      </div>
                                </div>

                                <div class="sameAsGI_div">
                                    <div class="mb-30">
                                        <div class="form-floating form-floating__icon">
                                            <input type="text" class="form-control"
                                                    value="{{old('contact_person_name')}}"
                                                    name="contact_person_name"
                                                    placeholder="{{translate('Contact_Person_Name')}}">
                                            <label>{{translate('Name')}}</label>
                                            <span class="material-icons">person</span>
                                        </div>
                                    </div>
                                    <div class="mb-30">
                                        <div class="">
                                            <input type="tel" class="form-control"
                                                    value="{{old('contact_person_phone')}}"
                                                    id="contact_person_phone"
                                                    name="contact_person_phone"
                                                    placeholder="{{translate('123 456 789')}}">
                                        </div>
                                    </div>
                                    <div class="mb-30">
                                        <div class="form-floating form-floating__icon">
                                            <input type="email" class="form-control"
                                                    value="{{old('contact_person_email')}}"
                                                    name="contact_person_email"
                                                    placeholder="{{translate('Email')}}">
                                            <label>{{translate('Email')}}</label>
                                            <span class="material-icons">mail</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <h3>{{translate('Step 2')}}</h3>
                    <section>
                        <div class="">
                            <h5 class="border-bottom mb-4 pb-2">{{translate('Business_Information')}}</h5>
                            <div class="mb-4">
                                <div class="mb-30">
                                    <div class="form-error-wrap">
                                        <select name="zone_id" id="zone_id" class="form-select">
                                            <option value="0" selected
                                                    disabled>{{translate('Select_Zone')}}</option>
                                            @foreach($zones as $zone)
                                                <option
                                                    value="{{$zone->id}}" {{old('zone_id')==$zone->id?'selected':''}}>{{$zone->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-30">
                                    <div id="location_map_div">
                                        <input id="pac-input" class="form-control w-auto"
                                                data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('search_your_location_here') }}"
                                                type="text"
                                                placeholder="{{ translate('search_here') }}"/>
                                        <div id="location_map_canvas"
                                                class="overflow-hidden rounded h-100"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="text" id="latitude" name="latitude"
                                                        class="form-control"
                                                        placeholder="{{ translate('Ex:') }} 23.8118428"
                                                        value="{{ old('latitude') }}" required
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{translate('Select from map')}}" readonly>
                                                <label for="latitude">{{ translate('latitude') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="text" step="0.1" name="longitude"
                                                        class="form-control"
                                                        placeholder="{{ translate('Ex:') }} 90.356331"
                                                        id="longitude"
                                                        value="{{ old('longitude') }}" required
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{translate('Select from map')}}" readonly>
                                                <label for="longitude">{{ translate('longitude') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-30">
                                    <div class="form-error-wrap">
                                        <select name="identity_type" id="identity_type"
                                                class="form-select">
                                            <option value="0" selected
                                                    disabled>{{translate('Identity_Type')}}</option>
                                            <option
                                                value="passport" {{old('identity_type')=='passport'?'selected':''}}>{{translate('passport')}}</option>
                                            <option
                                                value="nid" {{old('identity_type')=='nid'?'selected':''}}>{{translate('nid')}}</option>
                                            <option
                                                value="driving_license" {{old('identity_type')=='driving_license'?'selected':''}}>{{translate('driving_license')}}</option>
                                            <option
                                                value="trade_license" {{old('identity_type')=='trade_license'?'selected':''}}>{{translate('trade_license')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-30">
                                    <div class="form-floating form-floating__icon">
                                        <input type="text" class="form-control"
                                                value="{{old('identity_number')}}" name="identity_number"
                                                placeholder="{{translate('Identity_Number')}}">
                                        <label>{{translate('Identity_Number')}}</label>
                                        <span class="material-icons">badge</span>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="form-error-wrap">
                                        <div class="mb-3">{{translate('Identity_Image')}}(2:1) <span class="text-danger">*</span></div>
                                        <div id="multi_image_picker2" class="row"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <h3>{{translate('Step 3')}}</h3>
                    <section>
                        <div class="">
                            <h5 class="border-bottom mb-4 pb-2">{{translate('account_Information')}}</h5>
                            <div class="mb-4">
                                <div class="mb-30" data-bs-toggle="tooltip" data-bs-title="if you want to change
                                mail you need to go to first stage">
                                    <div class="form-floating form-floating__icon">
                                        <input type="email" id="account_email" class="form-control"
                                                value="{{old('account_email')}}" name="account_email"
                                                placeholder="{{translate('Email')}}" readonly>
                                        <label>{{translate('Email')}} <span class="text-danger">*</span></label>
                                        <span class="material-icons">mail</span>
                                    </div>
                                </div>
                                <div class="mb-30">
                                    <div class="form-floating form-floating__icon">
                                        <span class="material-icons togglePassword">visibility_off</span>
                                        <input type="password" class="form-control" value=""
                                                name="password" id="password"
                                                placeholder="{{translate('Password')}}">
                                        <label>{{translate('Password')}} <span class="text-danger">*</span></label>
                                        <span class="material-icons">lock</span>
                                    </div>
                                </div>
                                <div class="mb-30">
                                    <div class="form-floating form-floating__icon">
                                        <span class="material-icons togglePassword">visibility_off</span>
                                        <input type="password" class="form-control" value=""
                                                name="confirm_password"
                                                placeholder="{{translate('Confirm_Password')}}">
                                        <label>{{translate('Confirm_Password')}} <span class="text-danger">*</span></label>
                                        <span class="material-icons">lock</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('public/assets/provider-module')}}/js/jquery-3.6.0.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/main.js"></script>


<script src="{{asset('public/assets/provider-module')}}/js/sweet_alert.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/toastr.js"></script>
{!! Toastr::message() !!}


<script src="{{asset('public/assets/provider-module')}}/plugins/jquery-steps/jquery.steps.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="{{asset('public/assets/admin-module')}}/js/helper.js"></script>
<script src="{{asset('public/assets/admin-module/js/intlTelInput.js')}}"></script>

<script>
    "use strict";

    $(document).ready(function () {
        $("#company_email").on("change keyup paste", function () {
            $('#account_email').val($(this).val());
        });
        $("#company_phone").on("change keyup paste", function () {
            const countryCode = $('#register-vertical-steps-p-0').find('.iti__selected-dial-code').text();
            $('#account_phone').val(`${countryCode} ${$(this).val()}`);
        });
        $('#register-vertical-steps-p-0').find('.iti__flag-container').on("click", function () {
            const countryCode = $('#register-vertical-steps-p-0').find('.iti__selected-dial-code').text();
            $('#account_phone').val(`${countryCode} ${$("#company_phone").val()}`);
        });

        setInterval(() => {
            const countryCode = $('#register-vertical-steps-p-0').find('.iti__selected-dial-code').text();
            $('#account_phone').val(`${countryCode} ${$("#company_phone").val()}`);
            $('#account_email').val($('#company_email').val());
        }, 2000);


        $('#sameAsGI').on('change', function() {
            if ($(this).is(':checked')) {
                // $('.sameAsGI_div').slideUp('slow');
                $('[name="contact_person_name"]').val($('[name="company_name"]').val());
                $('[name="contact_person_email"]').val($('#company_email').val());
                $('#contact_person_phone').val($('#company_phone').val());
            } else {
                $('[name="contact_person_name"]').val('');
                $('[name="contact_person_email"]').val('');
                $('#contact_person_phone').val('');
                // $('.sameAsGI_div').slideDown('slow');
            }
        });
    });

</script>

<script>
    (function ($) {
        "use strict";

        let flag = "{{business_config('country_code', 'business_information')->live_values ?? 'bd'}}"
        let company_phone = undefined;
        let contact_person_phone = undefined;
        // let account_phone = undefined;

        let formWizard = $("#register-vertical-steps");

        // Form validation with jQuery
        formWizard.validate({
            errorPlacement: function (error, element) {
                element.parents('.form-floating, .form-error-wrap').after(error);
            },
        });

        function setValidationRulesAndMessages(rules, messages) {
            formWizard.validate().settings.rules = rules;
            formWizard.validate().settings.messages = messages;
        }

        function handleImageUploadValidation() {
            let uploaded = $("#multi_image_picker2 > .spartan_item_wrapper").length < 2;
            if (uploaded) {
                var errorMessageElement = formWizard.find(".coba-identity-img-err");

                if (errorMessageElement.length > 0) {
                    errorMessageElement.text("Please Upload identity Image");
                } else {
                    formWizard.find("#multi_image_picker2").parents(".form-error-wrap").after(`<span class="text-danger coba-identity-img-err">Please Upload identity Image</span>`);
                }
                return false;
            } else {
                formWizard.find(".coba-identity-img-err").remove();
            }
            return true;
        }

        formWizard.steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "fade",
            stepsOrientation: "vertical",
            autoFocus: true,
            labels: {
                finish: "Register",
                next: "Next",
                previous: "Previous"
            },
            onStepChanging: function (event, currentIndex, newIndex) {
                if (newIndex < currentIndex) {
                    return true;
                }

                switch (currentIndex) {
                    case 0:
                        setValidationRulesAndMessages({
                            company_name: "required",
                            company_email: {
                                required: true,
                                email: true
                            },
                            company_phone: "required",
                            company_address: "required",
                            logo: "required",
                            contact_person_name: "required",
                            contact_person_phone: "required",
                            contact_person_email: "required",
                        }, {
                            company_name: "Please enter your name",
                            company_phone: "Please enter your phone",
                            company_phone_2: "Please enter your phone",
                            company_email: "Please enter a valid email address",
                            company_address: "Please enter your address",
                            logo: "Please upload logo",
                            contact_person_name: "Please enter your name",
                            contact_person_phone: "Please enter your phone",
                            contact_person_email: "Please enter a valid email address",
                        });
                        break;
                    case 1:
                        setValidationRulesAndMessages({
                            zone_id: "required",
                            latitude: "required",
                            longitude: "required",
                            identity_type: "required",
                            identity_number: "required",
                        }, {
                            zone_id: "Please enter your Zone",
                            latitude: "Please enter latitude",
                            longitude: "Please enter longitude",
                            identity_type: "Please enter identity type",
                            identity_number: "Please enter identity number",
                        });

                        if (!handleImageUploadValidation()) {
                            return false;
                        }
                        break;
                }

                formWizard.validate().settings.ignore = ":disabled,:hidden";

                return formWizard.valid();
            },
            onFinished: function (event, currentIndex) {
                event.preventDefault();

                setValidationRulesAndMessages({
                    account_email: "required",
                    password: {
                        required: true,
                        minlength: 8
                    },
                    confirm_password: {
                        required: true,
                        minlength: 8,
                        equalTo: "#password"
                    },
                }, {
                    account_email: "Please enter email",
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 8 characters long"
                    },
                    confirm_password: {
                        required: "Please provide confirm password",
                        minlength: "Your password must be at least 8 characters long",
                        equalTo: "Please enter the same password as above"
                    },
                });

                var inputElement = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", "company_phone_2")
                    .val(`+${company_phone.s.dialCode}${$('#company_phone').val()}`);
                var inputElement2 = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", "contact_person_phone_2")
                    .val(`+${contact_person_phone.s.dialCode}${$('#contact_person_phone').val()}`);
                formWizard.append(inputElement);
                formWizard.append(inputElement2);
                formWizard.submit();
            }
        });

        company_phone = window.intlTelInput(document.querySelector("#company_phone"), {
            utilsScript: "{{asset('public/assets/admin-module/js/utils.js')}}",
            autoHideDialCode: false,
            autoPlaceholder: "ON",
            dropdownContainer: document.body,
            formatOnDisplay: true,
            hiddenInput: "company_phone",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
            initialCountry: flag,
        });
        contact_person_phone = window.intlTelInput(document.querySelector("#contact_person_phone"), {
            utilsScript: "{{asset('public/assets/admin-module/js/utils.js')}}",
            autoHideDialCode: false,
            autoPlaceholder: "ON",
            dropdownContainer: document.body,
            formatOnDisplay: true,
            hiddenInput: "contact_person_phone",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
            initialCountry: flag,
        });

    })(jQuery);
</script>

<script src="{{asset('public/assets/provider-module')}}/js//tags-input.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/spartan-multi-image-picker.js"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key={{business_config('google_map', 'third_party')?->live_values['map_api_key_client']}}&libraries=places&v=3.45.8"></script>
<script>
    "use strict";

    $("#multi_image_picker2").spartanMultiImagePicker({
        fieldName: 'identity_images[]',
        maxCount: 2,
        allowedExt: 'png|jpg|jpeg',
        rowHeight: 'auto',
        groupClassName: 'col-6',
        dropFileLabel: "{{translate('Drop_here')}}",
        placeholderImage: {
            image: '{{asset('public/assets/admin-module')}}/img/media/banner-upload-file.png',
            width: '100%',
        },

        onRenderedPreview: function (index) {
            toastr.success('{{translate('Image_added')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        },
        onRemoveRow: function (index) {
            console.log(index);
        },
        onExtensionErr: function (index, file) {
            toastr.error('{{translate('Please_only_input_png_or_jpg_type_file')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        },
        onSizeErr: function (index, file) {
            toastr.error('{{translate('File_size_too_big')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }

    });
    @if ($errors->any())
    @foreach($errors->all() as $error)
    toastr.error('{{$error}}', Error, {
        CloseButton: true,
        ProgressBar: true
    });
    @endforeach
    @endif

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#viewer').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileEg1").change(function () {
        readURL(this);
    });


    $(document).ready(function () {
        function initAutocomplete(mapCanvasId) {
            var myLatLng = {
                lat: 23.811842872190343,
                lng: 90.356331
            };
            const map = new google.maps.Map(document.getElementById(mapCanvasId), {
                center: {
                    lat: 23.811842872190343,
                    lng: 90.356331
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap(map);
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                marker.setPosition(latlng);
                map.panTo(latlng);

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];


                geocoder.geocode({
                    'latLng': latlng
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').innerHtml = results[1].formatted_address;
                        }
                    }
                });
            });

            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];

            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];

                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });
                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();
                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        initAutocomplete("location_map_canvas");
    });


    $('.__right-eye').on('click', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active')
            $(this).find('i').removeClass('tio-invisible')
            $(this).find('i').addClass('tio-hidden-outlined')
            $(this).siblings('input').attr('type', 'password')
        } else {
            $(this).addClass('active')
            $(this).siblings('input').attr('type', 'text')


            $(this).find('i').addClass('tio-invisible')
            $(this).find('i').removeClass('tio-hidden-outlined')
        }
    })
</script>
</body>
</html>
