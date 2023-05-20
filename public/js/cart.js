// // Bắt sự kiện thay đổi số lượng trong trường nhập
// document.querySelectorAll('.quantity-input').forEach(function (input) {
//     input.addEventListener('change', function () {
//         var quantity = parseInt(this.value);
//         var productId = this.dataset.productId;

//         // Gửi yêu cầu AJAX để cập nhật số lượng trong biến session cart
//         var xhr = new XMLHttpRequest();
//         xhr.open('POST', '/shop/controllers/CartController.php?action=quantity_change');
//         xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//         xhr.onload = function () {
//             if (xhr.status === 200) {
//                 // Cập nhật giao diện hiển thị số lượng
//                 var quantityElement = document.getElementById('quantity-' + productId);
//                 if (quantityElement) {
//                     quantityElement.textContent = quantity; 
//                 }
//             }
//         };
//         var data = 'productId=' + encodeURIComponent(productId) + '&quantity=' + encodeURIComponent(quantity);
//         xhr.send(data);
        
//         // Cập nhật tổng giá tiền
        
//     });
// });
