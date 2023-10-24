<?php

$conn = conn();
$db   = new Database($conn);

$subject = $db->single('subjects', ['id' => $_GET['id']]);

$link = routeTo('subjects/bills-download', ['id' => $subject->id]);

$message = "Yth. Ayah/Bunda Wali Murid 				
[namasiswa]

Assalamualaikum Wr Wb.

Semoga Ayah dan Bunda, wali murid dari ananda [namasiswa] senantiasa dalam keadaan sehat wal'afiat dan dalam perlindungan Allah SWT. Aamiin.

Pada hari Senin, tanggal 13 November 2023, akan dilaksanakan sesi Pengambilan Ijazah siswa melalui wali kelas.

Untuk sesi pengambilan ijazah ini, diharapkan semua administrasi siswa sudah terselesaikan. Bagi yang belum menyelesaikan administrasi siswa, mohon izin untuk menyampaikan perincian besarannya.

Informasi besaran ini bisa didapatkan dengan cara klik link berikut:

[link]

*_SAVE nomer WA ini agar link bisa diklik_

Mohon penyelesaian administrasi siswa tersebut bisa dilakukan paling lambat tanggal 10 November 2023 melalui bendahara sekolah. 

Demikian pemberitahuan ini. Atas perhatiannya, kami ucapkan terimakasih.

Wassalamu'alaikum Wr Wb.

Kepala Sekolah,
Mohamad Roji'i, M.Pd.


---
NB: Apabila ada kendala atau data yang tidak sesuai, mohon berkenan menghubungi bendahara sekolah, Ustadzah Farida di 085791305947.";

$message = str_replace('[namasiswa]', $subject->name, $message);
$message = str_replace('[link]', $link, $message);

try {
    //code...
    // $phone = '6282369378823'; // 
    $phone = $subject->phone;
    $wa = Whatsapp::send($phone, $message);
    set_flash_msg(['success'=>'Notifikasi berhasil dikirim']);
} catch (\Throwable $th) {
    //throw $th;
}

header('location:'.routeTo('crud/index',['table'=>'subjects']));