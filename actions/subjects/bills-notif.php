<?php

$conn = conn();
$db   = new Database($conn);

$subject = $db->single('subjects', ['id' => $_GET['id']]);

$link = routeTo('subjects/bills-download', ['id' => $subject->id]);

$message = "Yth. Ayah/Bunda Wali Murid 				
[namasiswa]

Assalamu'alaikum Wr Wb.

Semoga Ayah dan Bunda, wali murid dari ananda [namasiswa] senantiasa dalam keadaan sehat wal'afiat dan dalam perlindungan Allah SWT. Aamiin.

Izin menginformasikan mengenai jadual  pembagian buku Semester 2 oleh Walikelas kepada Ananda, yang insyaAllah akan dilaksanakan pada hari Selasa, tanggal 02 Januari 2024.

Untuk kelancaran proses tersebut, dimohon semua administrasi siswa sudah terselesaikan. Bagi yang belum, mohon izin menyampaikan perincian besarannya dengan cara klik link berikut:

[link]

Penyelesaian administrasi siswa tersebut bisa dibayarkan paling lambat tanggal 30 Januari 2024.

Pembayaran administrasi sekolah Ananda dilakukan melalui system' autodebet, yaitu dana disetorkan  ke rekening Ananda masing-masing, baik transfer ke rekening Ananda, ataupun menitipkan tabungan yang sudah tertulis slip menabungnya ke Bendahara sekolah (slip tabungan tersedia di meja Bendahara sekolah).*

Selama libur sekolah, Kantor Khazanah Ilmu buka hari Senin-Jum'at (08.00-12.00) dan hari Sabtu (08.00-12.00).

Mohon berkenan juga untuk rutin melakukan pengecekan mutasi di rekening Ananda masing-masing, apakah sudah sesuai dengan dana yang Ayah/Bunda setorkan ke rekening Ananda dan juga pendebetan dari bank terkait administrasi sekolah.

Demikian pemberitahuan ini kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terimakasih.


Wassalamu'alaikum Wr Wb.

Kepala Sekolah,
Mohamad Roji'i, M.Pd.



SAVE nomer WA ini agar link bisa diklik dan mohon untuk memberikan konfirmasi dengan cara mereply pesan ini
---


NB : Jika ada yang belum sesuai, bisa melakukan konfirmasi langsung ke Bendahara sekolah, Ustadzah Farida di 085791305947.";

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