<?php

$conn = conn();
$db   = new Database($conn);

$subject = $db->single('subjects', ['id' => $_GET['id']]);

$link = routeTo('subjects/bills-download', ['id' => $subject->id]);

$message = "Yth. Bapak/Ibu Wali Murid 				
$subject->name

Semoga Bapak dan Ibu wali murid dari $subject->name senantiasa dalam keadaan sehat wal'afiat dan dalam perlindungan Allah SWT.

Mengingat akan dilaksanakanya Pengambilan Ijazah pada  13 November 2023, dengan ini disampaikan perincian tunggakan sampai pada bulan ini. Klik lampiran berikut:

$link

Untuk itu, demi kelancaran proses belajar mengajar, dalam rangka memberikan pendidikan terbaik bagi putra-putri Bapak dan Ibu, kami mohon kerja samanya untuk melunasi tunggakan tersebut di atas. 

Paling lambat tanggal 10 November 2023.

Demikian pemberitahuan kami. Atas perhatiannya, kami ucapkan terimakasih.

Kepala Sekolah,
Mohamad Roji'i, M.Pd.


---
NB: Apabila ada data yang tidak sesuai, mohon berkenan menghubungi bendahara sekolah, Ustadzah Farida di 085791305947";

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