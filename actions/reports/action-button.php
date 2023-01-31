<?php

return ($d->is_open == 'BUKA' ? '<a href="'.routeTo('reports/finish',['id'=>$d->id]).'" onclick="if(confirm(\'apakah anda yakin akan menutup data ini ?\')){return true}else{return false}" class="btn btn-sm btn-success">Tutup</a>' : '') . '<a href="'.routeTo('reports/toggle',['id' => $d->id]).'" class="btn btn-sm btn-info">'.($d->is_active=='YA'?'Non Aktifkan':'Aktifkan').'</a>';