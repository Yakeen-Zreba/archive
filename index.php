<?php
session_start(); // بدء الجلسة
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام أرشفة</title>
    <link rel="icon" href="logos/short_logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            direction: rtl;
            text-align: right;
        }
        .card-header {
            background-color: #8056fb;
            color: #fff;
        }
        .card-header img {
            height: 40px;
        }
        .card-header h2 {
            margin: 0;
        }
        .container {
            margin-top: 50px;
        }
        .btn-create {
            background-color: #8056fb;
            color: #fff;
        }
        .btn-create:hover {
            background-color: #6d4edb;
            color: #fff;
        }
        .btn-custom {
            border: 1px solid #8056fb;
            background-color: #fff;
            color: #8056fb;
        }
        .btn-custom:hover {
            border: 2px solid #6d4edb;
            background-color: #fff;
            color: #6d4edb;
        }
        .alert-custom {
        background-color: #8056fb;
        color: #fff;
       }
        .alert-success {
            background-color: #28a745;
        }
        .alert-danger {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">أرشفة</h2>
                <img src="logos\white_logo.png" alt="NICLibya">
                
            </div>
            <div class="card-body">
                <?php if(isset($_GET['message'])): ?>
                    <div class="alert alert-<?= $_GET['type'] == 'success' ? 'success' : 'danger' ?> alert-custom" role="alert">
                        <?= htmlspecialchars($_GET['message']) ?>
                    </div>
                <?php endif; ?>
                <form action="generate_pdf.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="start_date">تاريخ المستند </label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="indicator_no">الرقم الإشاري</label>
                        <input type="number" class="form-control" id="indicator_no" name="indicator_no" required>
                    </div>
                    <div class="form-group">
                        <label for="from">مـــن</label>
                        <input type="text" class="form-control" id="from" name="from" required>
                    </div>
                    <div class="form-group">
                        <label for="to">الـــى</label>
                        <input type="text" class="form-control" id="to" name="to" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">الموضوع</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="images">اختر الصور</label>
                        <input type="file" class="form-control-file" id="images" name="images[]" multiple>
                    </div>
                    <button type="submit" class="btn btn-create btn-block">انشاء pdf </button>
                    <button type="button" class="btn btn-custom btn-block mt-2" onclick="window.location.reload();">إعادة تحميل الصفحة</button>
                    <a href="search.php" class="btn btn-custom btn-block">بحث</a>                
                </form>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>