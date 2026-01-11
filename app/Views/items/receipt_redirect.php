<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Printing Receipt...</title>
    <script>
        window.onload = function () {
            window.open("<?= site_url('salesorder/receipt/' . $orderId) ?>", '_blank');
            window.location.href = "<?= site_url('items/express_sale') ?>";
        };
    </script>
</head>
<body>
    <p>Processing receipt... Please wait.</p> <? echo $orderId;  ?>
</body>
</html>
