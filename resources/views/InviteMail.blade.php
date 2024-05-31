<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Clinic Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 36px;
            color: #333;
        }

        .content {
            margin-bottom: 30px;
        }

        .content p {
            font-size: 18px;
            color: #333;
        }

        .content a {
            display: block;
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
        }

        .content a:hover {
            background-color: #45a049;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #999;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Our Clinic
            </h1>
        </div>

        <div class="content">
            <p>Hello {{ $data['employee']->name }},</p>
            <p>Welcome to our Clinic! We're excited to have you on board.</p>
            <p>To get started, please click the button below to set your password:</p>
            <a href="">Set My Password</a>
        </div>
        <div class="footer">
            <p>If you have any questions, please don't hesitate to contact us.</p>
        </div>
    </div>
</body>
</html>
