<div class="container">
    <div class="header">
        <h1>Important: Your Details  Has Been Edited By Admin</h1>
    </div>
    <div class="content">
        <p>Dear {{ $data['employee']->name }},</p>
        <p>This is to inform you that an administrator has edited your details.</p>

        <p>If you have any questions or concerns about this edit, please don't hesitate to contact us.</p>
    </div>
    <div class="footer">
        <p>Best regards,</p>
        <p>[Your Clinic Name]</p>
    </div>
</div>

<style>
    .container {
        max-width: 600px;
        margin: 40px auto;
        padding: 20px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .header {
        background-color: #333;
        color: #fff;
        padding: 10px;
        text-align: center;
    }

    .header h1 {
        margin: 0;
    }

    .content {
        padding: 20px;
    }

    blockquote {
        background-color: #f7f7f7;
        border-left: 4px solid #ccc;
        padding: 10px;
        margin: 20px 0;
    }

    .btn {
        background-color: #337ab7;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
    }

    .btn:hover {
        background-color: #23527c;
    }

    .footer {
        background-color: #333;
        color: #fff;
        padding: 10px;
        text-align: center;
        clear: both;
    }

</style>
