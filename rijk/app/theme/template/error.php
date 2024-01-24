<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title;?></title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url("theme/assets/css/bootstrap.css");?>">
    <link rel="stylesheet" href="<?= url("theme/assets/vendors/bootstrap-icons/bootstrap-icons.css");?>">
    <link rel="stylesheet" href="<?= url("theme/assets/css/jquery.toast.min.css");?>">
    <link rel="stylesheet" href="<?= url("theme/assets/css/app.css");?>">
    <link rel="stylesheet" href="<?= url("theme/assets/css/pages/error.css");?>">
    <link rel="shortcut icon" href="<?= url("theme/assets/images/favicon.svg");?>" type="image/x-icon">
</head>
<body>
<?= $v->section("content");?>
</body>
</html>