$(document).ready(function () {
    $('#name').on('blur', function () {
        var productName = $(this).val();

        // Kiểm tra trùng lặp tên sản phẩm
        $.ajax({
            url: '/shop/controllers/ProductController.php?action=checkProductName',
            method: 'POST',
            data: $.param({ name: productName }),
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    $('#nameError').text(response.error);
                } else {
                    $('#nameError').text('');
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    });
});
