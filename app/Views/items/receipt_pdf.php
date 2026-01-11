<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: monospace;
            font-size: 11px; /* Increased from 10px */
            margin: 10px 5px 10 -35px; /* Top, Right, Bottom, Left */
            padding: 0;
        }
        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 4px 0;
        }
        .center {
            text-align: center;
        }
        .line {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            margin: 4px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 0;
            margin: 0;
            vertical-align: top;
        }
        .item-name {
            width: 65%;
            padding-left: 2px;
        }
        .item-total {
            width: 35%;
            text-align: right;
            padding-right: 2px;
        }
        .receipt-info {
            padding: 0 2px;
        }
        .qr-code {
            display: block;
            margin: 5px auto 0;
            width: 100px;
            height: auto;
        }
    </style>

    <script>
    window.onload = function() {
        window.print();
    };
</script>
</head>
<body>

<div class="center">
<!-- QR Code Section -->
<div class="center">
    <img class="qr-code" src="https://nairobimetaldetectors.net/logo.png" alt="www.nairobimetaldetectors.net"><br>

</div><br>
    <strong><?= esc($company->name) ?></strong><br>
    <?= esc($company->address) ?><br>
    Phone: <?= esc($company->phone) ?><br>
    <?= esc($company->email) ?><br>
</div>

<div class="line"></div>

<div class="receipt-info">
    Receipt #: <?= esc($order->id) ?><br>
    <?php date_default_timezone_set('Africa/Nairobi'); ?>
  <!--  Date: <?//= date('Y-m-d H:i:s') ?><br>-->
      Cashier:  <?= esc($order->created_by_user_name) ?> <br>
    <!--Cashier: <?//= esc(session()->get('name')) ?><br>-->
    Date:  <?= esc($order->date_time) ?> <br>
</div>
<hr>

<table>
<?php
$total = 0;
$vat = 0;
$change = 0;
foreach ($items as $item):
    $line = $item->item_name . ' x' . $item->quantity;
    $cost = $item->sellingPrice * $item->quantity;
    $vat += $item->vat_amnt ?? 0;
    $total += $cost;
?>
<tr>
    <td class="item-name"><?= esc($line) ?></td>
    <td class="item-total"><?= number_format($cost, 2) ?></td>
</tr>
<?php endforeach; ?>
</table>

<hr>
<div class="receipt-info">
	<hr>
    <strong>Subtotal:</strong> <?= number_format($total, 2) ?><br>
    <strong>VAT:</strong> <?= number_format($vat, 2) ?><br>

    <?php if (!empty($appliedDiscount)): ?>
        <strong>Discount (<?= esc($appliedDiscount['discount_name']) ?>):</strong> -<?= number_format($appliedDiscount['discount_amount'], 2) ?><br>
        <strong>Total:</strong> <?= number_format($total - $appliedDiscount['discount_amount'], 2) ?><br>
    <?php else: ?>
        <strong>Total:</strong> <?= number_format($total, 2) ?><br>
    <?php endif; ?>
</div>

<div class="receipt-info">
	<hr>
    <strong>Amount Paid:</strong> <?= number_format($amountPaid, 2) ?><br>
    <strong>Change:</strong> <?= number_format($bchange, 2) ?><br>
    <strong>Paid By:</strong> <?= esc($order->payment_method) ?><br>
</div>


<div class="line"></div>
<div class="center">Thank you!</div>

<!-- QR Code Section -->
<div class="center">
    <img class="qr-code" src="https://nairobimetaldetectors.net/nairobimetaldetectors.png" alt="www.nairobimetaldetectors.net"><br>
    <!--<small>Visit us online</small>-->
    <small>Welcome Again to Simplicity with a touch of Luxe!</small>
</div><br>
<br>
<br>
<script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>
