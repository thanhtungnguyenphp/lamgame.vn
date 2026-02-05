@if($isEdit)
    @include('shop::seller.register-edit')
@else
    @include('shop::seller.register-new')
@endif
