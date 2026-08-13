@extends('layouts.app.app')

@section('title', 'Checkout')

@section('styles')
@endsection

@section('content')

<div class="container checkout-container">

    {{-- BACK --}}
    <a href="{{ route('cart.index') }}" class="checkout-back">
        <i class="icon-arrow-left"></i>
        Back to Cart
    </a>


    {{-- HEADER --}}
    <div class="checkout-header">

        <span>ZENSTYLE SALON & SPA</span>

        <h2>Checkout</h2>

        <p>
            Complete your information to place your order.
        </p>

    </div>


    {{-- FORM --}}
    <div class="checkout-card">

        <div class="checkout-card-header">

            <h4>Billing Details</h4>

            <p>
                Please enter your information carefully.
            </p>

        </div>


        <form method="POST">

            @csrf


            {{-- FULL NAME --}}
            <div class="checkout-field">

                <label for="fullname">
                    Full Name <b>*</b>
                </label>

                <input type="text" id="fullname" name="fullname"
                    value="{{ old('fullname', $customer->FullName ?? '') }}" placeholder="Enter your full name">

                @error('fullname')
                <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>


            {{-- PHONE + EMAIL --}}
            <div class="checkout-row">

                {{-- PHONE --}}
                <div class="checkout-field">

                    <label for="phone">
                        Phone <b>*</b>
                    </label>

                    <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->Phone ?? '') }}"
                        placeholder="Enter your phone number">

                    @error('phone')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>


                {{-- EMAIL --}}
                <div class="checkout-field">

                    <label for="email">
                        Email address <b>*</b>
                    </label>

                    <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->Email) }}"
                        placeholder="Enter your email">

                    @error('email')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>

            </div>


            {{-- DELIVERY INFORMATION --}}
            <div class="checkout-section">

                <h4>Delivery Information</h4>

                <p>
                    Where should we deliver your order?
                </p>

            </div>


            {{-- PROVINCE + WARD --}}
            <div class="checkout-row">

                {{-- PROVINCE --}}
                <div class="checkout-field">

                    <label for="province">
                        Province / City <b>*</b>
                    </label>

                    <div class="select-wrapper">

                        <select id="province" name="province" required>

                            <option value="">
                                Select Province / City
                            </option>

                        </select>

                        <i class="icon-chevron-down"></i>

                    </div>

                    @error('province')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>


                {{-- WARD --}}
                <div class="checkout-field">

                    <label for="ward">
                        Ward / Commune <b>*</b>
                    </label>

                    <div class="select-wrapper">

                        <select id="ward" name="ward" required disabled>

                            <option value="">
                                Select Ward / Commune
                            </option>

                        </select>

                        <i class="icon-chevron-down"></i>

                    </div>

                    @error('ward')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>

            </div>


            {{-- STREET ADDRESS --}}
            <div class="checkout-field">

                <label for="address">
                    Detailed Address <b>*</b>
                </label>

                <input type="text" id="address" name="address" value="{{ old('address') }}"
                    placeholder="House number, street name..." required>

                <small class="field-hint">
                    Example: No. 20, Nguyen Trai Street
                </small>

                @error('address')
                <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>


            {{-- ADDITIONAL INFORMATION --}}
            <div class="checkout-section">

                <h4>Additional Information</h4>

                <p>
                    Add a note about your order if needed.
                </p>

            </div>


            {{-- ORDER NOTES --}}
            <div class="checkout-field">

                <label for="note">
                    Order Notes <span>(optional)</span>
                </label>

                <textarea id="note" name="note" rows="4"
                    placeholder="Notes about your order, e.g. special delivery instructions...">{{ old('note') }}</textarea>

                @error('note')
                <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>


            {{-- PAYMENT --}}
            <div class="checkout-section">

                <h4>Payment Method</h4>

                <p>
                    Choose your preferred payment method.
                </p>

            </div>


            <div class="payment-options">

                {{-- CASH --}}
                <label class="payment-option">

                    <input type="radio" name="payment_method" value="cash"
                        {{ old('payment_method', 'cash') == 'cash' ? 'checked' : '' }}>

                    <div class="payment-icon">
                        <i class="icon-money"></i>
                    </div>

                    <div class="payment-info">

                        <strong>Cash on Delivery</strong>

                        <span>
                            Pay when your order is delivered
                        </span>

                    </div>

                </label>


                {{-- BANK --}}
                <label class="payment-option">

                    <input type="radio" name="payment_method" value="bank"
                        {{ old('payment_method') == 'bank' ? 'checked' : '' }}>

                    <div class="payment-icon">
                        <i class="icon-credit-card"></i>
                    </div>

                    <div class="payment-info">

                        <strong>Bank Transfer</strong>

                        <span>
                            Pay via bank transfer
                        </span>

                    </div>

                </label>

            </div>


            @error('payment_method')
            <small class="text-danger">{{ $message }}</small>
            @enderror


            {{-- ACTION --}}
            <div class="checkout-actions">

                <button type="submit" class="checkout-submit">
                    Place Order
                    <i class="icon-arrow-right"></i>
                </button>

            </div>

        </form>

    </div>
    ```

</div>

{{-- VIETNAM ADDRESS API --}}

<script>
document.addEventListener('DOMContentLoaded', function() {

    const provinceSelect = document.getElementById('province');
    const wardSelect = document.getElementById('ward');

    const oldProvince = @json(old('province'));
    const oldWard = @json(old('ward'));

    const API_URL = 'https://provinces.open-api.vn/api/v2';


    /*
    |--------------------------------------------------------------------------
    | Load Provinces / Cities
    |--------------------------------------------------------------------------
    */

    fetch(`${API_URL}/p/`)
        .then(response => {

            if (!response.ok) {
                throw new Error('Cannot load provinces.');
            }

            return response.json();

        })
        .then(provinces => {

            provinces.forEach(province => {

                const option = document.createElement('option');

                option.value = province.code;
                option.textContent = province.name;

                if (String(oldProvince) === String(province.code)) {
                    option.selected = true;
                }

                provinceSelect.appendChild(option);

            });

            if (oldProvince) {
                loadWards(oldProvince, oldWard);
            }

        })
        .catch(error => {

            console.error('Province API error:', error);

            provinceSelect.innerHTML = `
                <option value="">
                    Unable to load Province / City
                </option>
            `;

        });


    /*
    |--------------------------------------------------------------------------
    | Province Changed
    |--------------------------------------------------------------------------
    */

    provinceSelect.addEventListener('change', function() {

        const provinceCode = this.value;

        wardSelect.innerHTML = `
            <option value="">
                Select Ward / Commune
            </option>
        `;

        wardSelect.disabled = true;

        if (!provinceCode) {
            return;
        }

        loadWards(provinceCode);

    });


    /*
    |--------------------------------------------------------------------------
    | Load Wards
    |--------------------------------------------------------------------------
    */

    function loadWards(provinceCode, selectedWard = '') {

        wardSelect.innerHTML = `
            <option value="">
                Loading Ward / Commune...
            </option>
        `;

        wardSelect.disabled = true;


        fetch(`${API_URL}/w/?province=${provinceCode}`)
            .then(response => {

                if (!response.ok) {
                    throw new Error('Cannot load wards.');
                }

                return response.json();

            })
            .then(wards => {

                wardSelect.innerHTML = `
                    <option value="">
                        Select Ward / Commune
                    </option>
                `;


                wards.forEach(ward => {

                    const option = document.createElement('option');

                    option.value = ward.code;
                    option.textContent = ward.name;

                    if (String(selectedWard) === String(ward.code)) {
                        option.selected = true;
                    }

                    wardSelect.appendChild(option);

                });

                wardSelect.disabled = false;

            })
            .catch(error => {

                console.error('Ward API error:', error);

                wardSelect.innerHTML = `
                    <option value="">
                        Unable to load Ward / Commune
                    </option>
                `;

            });

    }

});
</script>

@endsection