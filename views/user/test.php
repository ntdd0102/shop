<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Image Temp Path</title>
    <script>
        function displayImageTempPath(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var tempPath = e.target.result;
                    document.getElementById("image-preview").textContent = tempPath;

                    // Send the tempPath to the server for further processing
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/process-temp-path.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                            var actualPath = xhr.responseText;
                            document.getElementById("actual-path").textContent = actualPath;
                        }
                    };
                    xhr.send('tempPath=' + encodeURIComponent(tempPath));
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</head>

<body>
    <h2>Display Image Temp Path</h2>

    <input type="file" id="image" onchange="displayImageTempPath(this)">
    <p id="image-preview"></p>
    <p id="actual-path"></p>
</body>

</html>