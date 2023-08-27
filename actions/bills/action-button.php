<?php

return '<a href="'.routeTo('bills/send-notif',['id'=>$d->id]).'" onclick="if(confirm(\'apakah anda yakin akan mengirim notifikasi tagihan ini ?\')){return true}else{return false}" class="btn btn-sm btn-success">Send Notif</a>';