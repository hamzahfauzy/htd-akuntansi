<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan</title>
    <style>
        table th {
            text-align:center;
        }
        table td, table th {
            padding:10px;
        }
    </style>
</head>
<body>
    <!-- HEAD -->
    <div style="text-align:center;padding-bottom:10px;border-bottom:2px solid #000;">
        <h2 style="margin:0;padding:0">INSTITUSI KHAZANAH ILMU</h2>
        <h3 style="margin:0;padding:0">
            SEKOLAH DASAR KHAZANAH ILMU<br>
            NSS: 104050214056    NPSN: 20576103<br>
        </h3>
        <p style="margin:0;padding:0">
            <i>
                Jl. Ubi II, No. 23 Wage, Taman, Sidoarjo 031-8553790 / 081615065375<br>
                Website:www.institusi-khazanahilmu.blogspot.com<br>
                Email: institusi.khazanahilmu@gmail.com
            </i>
        </p>
    </div>
    <!-- BODY -->
    <div>
        <h3 style="text-align:center"><u>TAGIHAN</u></h3>
        <p>Berikut adalah rincian tagihan atas nama <b><?=$subject->name?></b> dengan NIS <b><?=$subject->code?></b> : </p>

        <table border="1" cellpadding="5" cellspacing="0" align="center">
            <tr>
                <th width="125">Kode</th>
                <th width="100">Merchant</th>
                <th width="200">Deskripsi</th>
                <th width="100">Nominal</th>
            </tr>
            <?php if(empty($subject->bills)): ?>
            <tr>
                <td colspan="4"><i>Tidak ada tagihan</i></td>
            </tr>
            <?php else: ?>
            <?php $total = 0; foreach($subject->bills as $bill): $total+=$bill->amount;?>
            <tr>
                <td><?=$bill->bill_code?></td>
                <td><?=$bill->merchant_name?></td>
                <td><?=$bill->description?></td>
                <td>Rp. <?=number_format($bill->amount)?></td>
            </tr>
            <?php endforeach ?>
            <tr>
                <td colspan="3">TOTAL</td>
                <td>Rp. <?=number_format($total)?></td>
            </tr>
            
            <?php endif ?>
        </table>
    </div>
</body>
</html>