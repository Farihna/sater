<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sater - @yield('title', 'Home')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('store/css/style.css') }}" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{env('MIDTRANS_CLIENT_KEY') }}"></script>
    @livewireStyles
</head>

<body>
    <!-- header -->
    @include('landing.layouts.header')

    <!-- Main Content -->
    @yield('content')

    <!-- footer -->
    @include('landing.layouts.footer')

@livewireScripts
@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ asset('store/js/app.js') }}"></script>
<script>
$(document).ready(function() {
    $.getJSON('{{ route('cart.count') }}', function(data) {
        if (data.count !== undefined) {
            $('#cartCount').text(data.count);
        }
    });

    function updateCartQuantity(itemId, quantity, inputElement) {
        $.ajax({
            url: '/cart/update/' + itemId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PATCH',
                quantity: quantity
            },
            dataType: 'json',
            success: function(response) {
                const itemId = inputElement.closest('.cart-item').data('product-id'); 
                const formattedItemTotal = 'Rp' + response.item_total.toLocaleString('id-ID');
                const formattedSubtotal = 'Rp' + response.subtotal.toLocaleString('id-ID');
                $('#globalSubtotal').text(formattedSubtotal);
                $('#globalTotal').text(formattedSubtotal);
                $('#item-total-' + itemId).text(formattedItemTotal);
            },
            error: function(xhr) {
                showToast(xhr.responseJSON.message || 'Gagal memperbarui kuantitas.', 'danger');
                window.location.reload(); 
            }
        });
    }
    const inputQuantity = $('#productQuantity');
    if (inputQuantity.length) { 
        const maxStock = parseInt(inputQuantity.attr('max'));
        
        $('.quantity-btn').on('click', function() {
            let currentVal = parseInt(inputQuantity.val());
            let action = $(this).text(); 
            
            if (action === '+') {
                if (currentVal < maxStock) {
                    inputQuantity.val(currentVal + 1);
                }
            } else if (action === '-') {
                if (currentVal > 1) {
                    inputQuantity.val(currentVal - 1);
                }
            }
        });
        
        inputQuantity.on('change keyup', function() {
            let val = parseInt($(this).val());
            if (val < 1 || isNaN(val)) {
                $(this).val(1);
            } else if (val > maxStock) {
                $(this).val(maxStock);
            }
        });
    }

    function performAddToCart(form, redirect = false) {
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            dataType: 'json', 
            success: function(response) {
                if (response.cart_count !== undefined) {
                    $('#cartCount').text(response.cart_count);
                }
                if (redirect) {
                    window.location.href = "{{ route('checkout.index') }}"; 
                } else {
                    showToast(response.message, 'success');
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON.message || 'Gagal menambahkan produk.';
                showToast(errorMessage, 'danger');
            }
        });
    }
    $('.add-to-cart-form').on('submit', function(e) {
        e.preventDefault();
        performAddToCart($(this), false); 
    });

    $('#buyNowBtn').on('click', function() {
        const form = $('#mainCartForm');
        performAddToCart(form, true); 
    });

    $(document).on('click', '.quantity-btn', function() {
        const action = $(this).data('action');
        const itemId = $(this).data('id');
        const inputElement = $(this).siblings('input[name="quantity"]');
        let currentVal = parseInt(inputElement.val());
        const maxStock = parseInt(inputElement.attr('max'));
        
        let newQuantity = currentVal;
        if (action === 'plus' && currentVal < maxStock) {
            newQuantity = currentVal + 1;
        } else if (action === 'minus' && currentVal > 1) {
            newQuantity = currentVal - 1;
        } else {
            return;
        }
        inputElement.val(newQuantity);
        updateCartQuantity(itemId, newQuantity, inputElement);
    });

    $(document).on('change', 'input[name="quantity"]', function() {
        const itemId = $(this).closest('.cart-item').data('product-id');
        let newQuantity = parseInt($(this).val());
        const maxStock = parseInt($(this).attr('max'));
        
        if (newQuantity < 1 || isNaN(newQuantity)) {
            newQuantity = 1;
            $(this).val(1);
        } else if (newQuantity > maxStock) {
            newQuantity = maxStock;
            $(this).val(maxStock);
        }
        updateCartQuantity(itemId, newQuantity, $(this));
    });


    let itemIdToDelete = null;

    $(document).on('click', '.remove-item-btn', function() {
        itemIdToDelete = $(this).data('id');
        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    });

    $('#confirmDeleteButton').on('click', function() {
        if (itemIdToDelete !== null) {
            $('#deleteConfirmModal').modal('hide');
            performDeleteItem(itemIdToDelete);
        }
    });

    function performDeleteItem(itemId) {
        const rowElement = $('.cart-item[data-product-id="' + itemId + '"]');

        $.ajax({
            url: '{{ url('/cart/remove/') }}/' + itemId, 
            type: 'POST', 
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function(response) {
                rowElement.remove(); 
                window.location.reload(); 
                
                $('#cartCount').text(response.cart_count); 
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON.message || 'Gagal menghapus item.';
                showToast(errorMessage, 'danger');
            }
        });
    }
});
</script>
</body>