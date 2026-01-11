<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }

        .receipt {
            width: 100%;
            max-width: 300px; /* Approx 80mm */
            padding: 10px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .info td {
            padding: 2px 0;
        }

        .summary td {
            padding: 2px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="receipt">
    <div class="center bold">
        <?= esc($company->name ?? 'COMPANY NAME') ?><br>
        <?= esc($company->address ?? '') ?><br>
        <?= esc($company->phone ?? '') ?><br>
        <div class="divider"></div>
        RECEIPT
    </div>

    <table class="info">
        <tr>
            <td>Customer:</td>
            <td class="text-end"><?= esc($customer['name']) ?></td>
        </tr>
        <tr>
            <td>Date:</td>
            <td class="text-end"><?= date('Y-m-d H:i') ?></td>
        </tr>
        <tr>
            <td>Transaction:</td>
            <td class="text-end"><?= ucfirst($entry['type']) ?></td>
        </tr>
        <tr>
            <td>By:</td>
            <!--<td class="text-end"><?//= esc($entry['recorded_by']) ?></td>-->
            <td class="text-end"><?= esc($entry['recorded_by'] ?? '-') ?></td>

        </tr>
    </table>

    <div class="divider"></div>

    <table class="info">
        <tr>
            <td>Description:</td>
            <td><?= esc($entry['description'] ?? '-') ?></td>
        </tr>
        <tr>
            <td>Method:</td>
            <td><?= esc($entry['method'] ?? '-') ?></td>
        </tr>
        <tr>
            <td>Amount:</td>
            <td>KES <?= number_format($entry['amount'], 2) ?></td>
        </tr>
        <tr>
            <td>Balance:</td>
            <td>KES <?= number_format($entry['balance'], 2) ?></td>
        </tr>
    </table>

    <div class="divider"></div>
    <div class="center">
        Thank you!<br>
        <?= esc($company->name ?? '') ?>
    </div>
</div>
</body>
</html>
