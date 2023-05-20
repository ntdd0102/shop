// Bắt sự kiện khi người dùng nhấn submit form
document.getElementById("register-form").addEventListener("submit", function(event) {
    // Ngăn chặn submit form mặc định
    event.preventDefault();

    // Lấy giá trị các trường dữ liệu từ form
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var phone = document.getElementById("phone").value;
    // ... Lấy giá trị các trường dữ liệu khác

    // Thực hiện kiểm tra các trường dữ liệu
    var isValid = true;

    // Kiểm tra username
if (username.trim() === "") {
    document.getElementById("username-error").textContent = "Vui lòng nhập username.";
    isValid = false;
} else if (username.length < 5 || username.length > 15) {
    document.getElementById("username-error").textContent = "Username phải từ 5 đến 15 ký tự.";
    isValid = false;
} else {
    document.getElementById("username-error").textContent = "";
}

// Kiểm tra password
if (password.trim() === "") {
    document.getElementById("password-error").textContent = "Vui lòng nhập password.";
    isValid = false;
} else if (password.length < 6 || password.length > 12) {
    document.getElementById("password-error").textContent = "Password phải từ 6 đến 12 ký tự.";
    isValid = false;
} else if (!/[A-Z]/.test(password)) {
    document.getElementById("password-error").textContent = "Password phải chứa ít nhất 1 chữ in hoa.";
    isValid = false;
} else if (/[^a-zA-Z0-9]/.test(password)) {
    document.getElementById("password-error").textContent = "Password không được chứa ký tự đặc biệt.";
    isValid = false;
} else {
    document.getElementById("password-error").textContent = "";
}

// Kiểm tra tên
if (name.trim() === "") {
    document.getElementById("name-error").textContent = "Vui lòng nhập tên.";
    isValid = false;
} else {
    document.getElementById("name-error").textContent = "";
}

// Kiểm tra email
var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
if (email.trim() === "") {
    document.getElementById("email-error").textContent = "Vui lòng nhập email.";
    isValid = false;
} else if (!emailPattern.test(email)) {
    document.getElementById("email-error").textContent = "Email không đúng định dạng.";
    isValid = false;
} else {
    document.getElementById("email-error").textContent = "";
}

// Kiểm tra số điện thoại
var phonePattern = /^\d{10}$/;
if (phone.trim() === "") {
    document.getElementById("phone-error").textContent = "Vui lòng nhập số điện thoại.";
    isValid = false;
} else if (!phonePattern.test(phone)) {
    document.getElementById("phone-error").textContent = "Số điện thoại không đúng định dạng.";
    isValid = false;
} else {
    document.getElementById("phone-error").textContent = "";
}


    // ... Kiểm tra các trường dữ liệu khác

    // Nếu có lỗi, không submit form
    if (!isValid) {
        return;
    }

    // Gửi yêu cầu Ajax tới server để kiểm tra username
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/shop/controllers/UserController.php?action=check_username", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);

            // Kiểm tra kết quả từ server
            if (response.exists) {
                document.getElementById("username-error").textContent = "Tên người dùng đã tồn tại!";
            } else {
                // Nếu không có lỗi, submit form
                document.getElementById("register-form").submit();
            }
        }
    };
    xhr.send("username=" + username);
});
