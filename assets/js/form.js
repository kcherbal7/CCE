jQuery(document).ready(function($) {
    const form = $('#contact-dz-form');
    const shippingPrice = $('#shipping-price');

    // Update shipping price when wilaya changes
    $('#wilaya').on('change', function() {
        const wilaya = $(this).val();
        if (!wilaya) {
            shippingPrice.text('0');
            return;
        }

        $.ajax({
            url: contactDZ.ajaxurl,
            type: 'POST',
            data: {
                action: 'get_shipping_price',
                wilaya: wilaya
            },
            success: function(response) {
                if (response.success) {
                    shippingPrice.text(response.data.price);
                }
            }
        });
    });

    // Handle form submission
    form.on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('action', 'contact_dz_submit');
        formData.append('contact_dz_nonce', contactDZ.nonce);

        $.ajax({
            url: contactDZ.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    form[0].reset();
                } else {
                    alert(response.data.message || 'An error occurred');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
}); 