<?php

return  '<a href="'.routeTo('subjects/bills-download',['id'=>$d->id]).'" class="btn btn-info btn-sm"><i class="fas fa-fw fa-download"></i> Download Tagihan</a> '.
        '<a href="'.routeTo('subjects/view',['id'=>$d->id]).'" class="btn btn-success btn-sm"><i class="fas fa-fw fa-eye"></i> Lihat</a>';