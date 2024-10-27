<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- 这里是 CSRF 令牌 -->

    <title>Upload File to Aliyun OSS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        form {
            margin-top: 20px;
        }
        input[type="file"] {
            margin-bottom: 10px;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<h1>Upload File to Aliyun OSS</h1>

<!-- 文件上传表单 -->
<form action="/upload" method="POST" enctype="multipart/form-data" id="uploadForm">
    <!-- Laravel CSRF Token -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <!-- 文件选择 -->
    <input type="file" name="file" required>

    <!-- 上传按钮 -->
    <button type="submit">Upload</button>
</form>

<p id="error"></p>

<!-- 显示上传成功或错误消息 -->
<div id="message" class="message" style="display: none;"></div>

<script>
    document.getElementById('uploadForm').addEventListener('submit', async function(e) {

        e.preventDefault(); // 阻止表单默认提交行为

        e.preventDefault(); // 阻止表单的默认提交行为


        const form = document.getElementById('uploadForm');
        const formData = new FormData(form);
        const messageDiv = document.getElementById('message');


        try {

        // 清空之前的消息
        messageDiv.style.display = 'none';
        messageDiv.innerHTML = '';

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


            // 发送 POST 请求上传文件
            const response = await fetch('/upload', {
                method: 'POST',
                body: formData,
                headers: {

                    'X-CSRF-TOKEN': formData.get('_token') // 添加 CSRF 令牌
                }
            });

            const result = await response.json(); // 解析 JSON 响应

                    'X-CSRF-TOKEN': csrfToken // 添加 CSRF 令牌到请求头
                }
            });

            // 获取原始响应文本
            const responseText = await response.text();
            console.log('Server response:', responseText); // 打印服务器响应，帮助调试

            // 尝试解析 JSON
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (error) {
                throw new Error('Unexpected server response. Not valid JSON.');
            }


            // 如果上传成功，显示文件链接
            if (response.ok) {
                messageDiv.style.display = 'block';
                messageDiv.innerHTML = `<strong>Success:</strong> File uploaded successfully. <br> <a href="${result.file_url}" target="_blank">View File</a>`;
                messageDiv.style.color = 'green';
            } else {
                throw new Error(result.error || 'File upload failed');
            }
        } catch (error) {

            // 处理上传失败的情况

            messageDiv.style.display = 'block';
            messageDiv.innerHTML = `<strong>Error:</strong> ${error.message}`;
            messageDiv.style.color = 'red';
        }
    });

</script>
</body>
</html>
