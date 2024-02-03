<?php

header('content-type: application/json');

// $message_lists = [
//     'cek bill'
// ];

// logging 
$jsonString = file_get_contents('php://input');

file_put_contents("logs.txt", $jsonString, FILE_APPEND);

$data    = json_decode($jsonString, true);
$message = $data['message'];

$from    = $data['from'];
$from2   = explode('@',$from)[0];

$froms   = [$from2, '0'.substr($from2, 2)];

$conn = conn();
$db   = new Database($conn);

// if(!in_array($message, $message_lists))
// {
//     die();
// }

$report_id = activeMaster()?activeMaster()->id:0;

$where = "WHERE phone IN ('".implode("','", $froms)."')";

$db->query = "SELECT *, CONCAT(subjects.code,' - ',subjects.name) as subject_name, (SELECT groups.name FROM `groups` WHERE groups.id = (SELECT group_id FROM subject_groups WHERE user_id = subjects.user_id AND report_id = $report_id)) as group_name FROM subjects $where ORDER BY id";
$data  = $db->exec('single');

try {
    //code...
    if(!$data)
    {
        $msg = "No WA anda ".$froms[1]." tidak terdaftar di sistem. Hubungi petugas untuk penyesuaian data siswa dengan no WA terkait";
        Whatsapp::send($froms[0], $msg);
        die();
    }

    // random message
    // check sender session
    $sessionId = $from.'-'.date('Y-m-d');
    if(!file_exists("bot/$sessionId"))
    {
        // set session by sender number and date
        mkdir("bot/$sessionId");
        // send wellcome message
        $parent = explode('QQ', $data->address);
        $parent_name = $parent[0];
        $msg = "Assalamualaikum Wr Wb.
Terima kasih telah mengunjungi Sekolah Khazanah Ilmu, kami harap Bapak/Ibu $parent_name dalam keadaan sehat selalu.
        
Silakan pilih informasi yang dibutuhkan: (ketik angkanya saja). Beberapa pilihan hanya bisa diakses siswa aktif.
        
1. Cek Status Pembayaran (privat)
2. Info Jadwal/Kegiatan terdekat
3. Info PPDB
4. Cek status PPDB
5. Cek kelulusan (privat)
6. Download Brosur

Silahkan sebarkan informasi ini untuk saudara, keluarga & lingkungan terdekat Bapak/Ibu $parent_name 🙏
        
Agar semakin banyak yang mendapatkan manfaat atas keberadaan sekolah Khazanah Ilmu di tengah masyarakat";
        Whatsapp::send($froms[0], $msg);
        die();
    }

    if($message == 'cek bill')
    {
        require 'webhook-action/cek-bill.php';
    }
    else
    {
        if($message == 1)
        {
            require 'webhook-action/cek-bill.php';
        }
        else
        {
            Whatsapp::send($froms[0], "Mohon Maaf! Fitur masih dalam pengerjaan");
            die();
        }
    }
    // Whatsapp::send($from2, "");
} catch (\Throwable $th) {
    //throw $th;
}

die();