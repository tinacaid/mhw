{{--<!DOCTYPE html>--}}
{{--<html lang="zh-CN">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--    <title>文件上传</title>--}}
{{--</head>--}}
{{--<body>--}}
{{--<h1>文件上传</h1>--}}
{{--<form id="uploadForm" action="/api/upload" method="POST" enctype="multipart/form-data">--}}
{{--    @csrf--}}
{{--    <input type="file" name="file" required>--}}
{{--    <button type="submit">上传</button>--}}
{{--</form>--}}

{{--<div id="uploadResult" style="margin-top: 20px;"></div>--}}

{{--<script>--}}
{{--    document.getElementById('uploadForm').onsubmit = async function(event) {--}}
{{--        event.preventDefault(); // 防止表单默认提交--}}

{{--        const formData = new FormData(this);--}}
{{--        const response = await fetch('/api/upload', { // API 路由--}}
{{--            method: 'POST',--}}
{{--            body: formData,--}}
{{--            headers: {--}}
{{--                'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
{{--            }--}}
{{--        });--}}

{{--        const result = await response.json();--}}
{{--        const uploadResultDiv = document.getElementById('uploadResult');--}}

{{--        if (response.ok) {--}}
{{--            uploadResultDiv.innerHTML = `<p>文件上传成功！<br>文件地址：<a href="${result.url}" target="_blank">${result.url}</a></p>`;--}}
{{--        } else {--}}
{{--            uploadResultDiv.innerHTML = `<p>上传失败：${result.error}</p>`;--}}
{{--        }--}}
{{--    };--}}
{{--</script>--}}
{{--</body>--}}
{{--</html>--}}

{{--    <!DOCTYPE html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--    <title>File Upload to OSS</title>--}}
{{--    <style>--}}
{{--        body {--}}
{{--            font-family: Arial, sans-serif;--}}
{{--            padding: 20px;--}}
{{--        }--}}
{{--        form {--}}
{{--            margin-top: 20px;--}}
{{--        }--}}
{{--        input[type="file"] {--}}
{{--            margin-bottom: 10px;--}}
{{--        }--}}
{{--        .message {--}}
{{--            margin-top: 20px;--}}
{{--            padding: 10px;--}}
{{--            border: 1px solid #ccc;--}}
{{--        }--}}
{{--    </style>--}}
{{--</head>--}}
{{--<body>--}}
{{--<h1>Upload File to Aliyun OSS</h1>--}}

{{--<!-- 上传文件的表单 -->--}}
{{--<form action="/upload" method="POST" enctype="multipart/form-data" id="uploadForm">--}}
{{--    <!-- Laravel CSRF Token -->--}}
{{--    <input type="hidden" name="_token" value="{{ csrf_token() }}">--}}

{{--    <!-- 文件上传输入框 -->--}}
{{--    <input type="file" name="file" required>--}}

{{--    <!-- 提交按钮 -->--}}
{{--    <button type="submit">Upload</button>--}}
{{--</form>--}}

{{--<!-- 显示消息 -->--}}
{{--<div id="message" class="message" style="display: none;"></div>--}}

{{--<script>--}}
{{--    document.getElementById('uploadForm').addEventListener('submit', async function(e) {--}}
{{--        e.preventDefault();--}}

{{--        const form = document.getElementById('uploadForm');--}}
{{--        const formData = new FormData(form);--}}
{{--        const messageDiv = document.getElementById('message');--}}

{{--        try {--}}
{{--            const response = await fetch('/upload', {--}}
{{--                method: 'POST',--}}
{{--                body: formData,--}}
{{--                headers: {--}}
{{--                    'X-CSRF-TOKEN': formData.get('_token')--}}
{{--                }--}}
{{--            });--}}

{{--            const result = await response.json();--}}

{{--            if (response.ok) {--}}
{{--                messageDiv.style.display = 'block';--}}
{{--                messageDiv.innerHTML = `<strong>Success:</strong> File uploaded successfully. <br> <a href="${result.file_url}" target="_blank">View File</a>`;--}}
{{--                messageDiv.style.borderColor = 'green';--}}
{{--            } else {--}}
{{--                throw new Error(result.error || 'File upload failed');--}}
{{--            }--}}
{{--        } catch (error) {--}}
{{--            messageDiv.style.display = 'block';--}}
{{--            messageDiv.innerHTML = `<strong>Error:</strong> ${error.message}`;--}}
{{--            messageDiv.style.borderColor = 'red';--}}
{{--        }--}}
{{--    });--}}
{{--</script>--}}
{{--</body>--}}
{{--</html>--}}

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

<!-- 显示上传成功或错误消息 -->
<div id="message" class="message" style="display: none;"></div>

<script>
    document.getElementById('uploadForm').addEventListener('submit', async function(e) {
        e.preventDefault(); // 阻止表单默认提交行为

        const form = document.getElementById('uploadForm');
        const formData = new FormData(form);
        const messageDiv = document.getElementById('message');

        try {
            // 发送 POST 请求上传文件
            const response = await fetch('/upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token') // 添加 CSRF 令牌
                }
            });

            const result = await response.json(); // 解析 JSON 响应

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
