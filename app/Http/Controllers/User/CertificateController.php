<?php
// app/Http/Controllers/User/CertificateController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserCourse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CertificateController extends Controller
{
    /**
     * Generate certificate number format:  DDMMYYYY/EDU/ROMAN_MONTH/YYYY
     * Example: 15122025/EDU/XII/2025
     */
    private function generateCertificateNumber($date)
    {
        $day = $date->format('d');
        $month = $date->format('m');
        $year = $date->format('Y');

        // Roman numerals for months
        $romanMonths = [
            '01' => 'I',
            '02' => 'II',
            '03' => 'III',
            '04' => 'IV',
            '05' => 'V',
            '06' => 'VI',
            '07' => 'VII',
            '08' => 'VIII',
            '09' => 'IX',
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII'
        ];

        $romanMonth = $romanMonths[$month];

        return "{$day}{$month}{$year}/EDU/{$romanMonth}/{$year}";
    }

    public function download($userCourseId)
    {
        /** @var User $user */
        $user = Auth::user();

        $userCourse = UserCourse::with('kursus')
            ->where('id', $userCourseId)
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->firstOrFail();

        if (!$userCourse->kursus->certificate) {
            abort(404, 'Sertifikat tidak tersedia untuk kursus ini');
        }

        $templatePath = public_path('assets/media/certificate-template.png');

        if (! file_exists($templatePath)) {
            abort(404, 'Template sertifikat tidak ditemukan');
        }

        $manager = new ImageManager(new Driver());
        $image = $manager->read($templatePath);

        // Font paths - Times New Roman
        $fontRegular = public_path('assets/fonts/times. ttf');
        $fontBold = public_path('assets/fonts/timesbd.ttf');

        if (!file_exists($fontRegular)) {
            $fontRegular = public_path('assets/fonts/Arial.ttf');
        }
        if (! file_exists($fontBold)) {
            $fontBold = $fontRegular;
        }

        // Data untuk sertifikat
        $nama = strtoupper($user->name);
        $kursus = $userCourse->kursus->title;
        $tanggalSelesai = $userCourse->completed_at->locale('id')->translatedFormat('d F Y');

        // Generate certificate number dengan format baru
        $certificateId = $this->generateCertificateNumber($userCourse->completed_at);

        $width = $image->width();
        $height = $image->height();

        // 1. CERTIFICATE OF COMPLETION
        $image->text('CERTIFICATE OF COMPLETION', $width / 2, 220, function ($font) use ($fontBold) {
            $font->file($fontBold);
            $font->size(72);
            $font->color('003d82');
            $font->align('center');
            $font->valign('middle');
        });

        // 2. NOMOR SERTIFIKAT
        $image->text('NOMOR SERTIFIKAT: ' . $certificateId, $width / 2, 300, function ($font) use ($fontRegular) {
            $font->file($fontRegular);
            $font->size(40);
            $font->color('666666');
            $font->align('center');
            $font->valign('middle');
        });

        // 3. NAMA LENGKAP
        $image->text($nama, $width / 2, 850, function ($font) use ($fontBold) {
            $font->file($fontBold);
            $font->size(82);
            $font->color('1a1a1a');
            $font->align('center');
            $font->valign('bottom');
        });

        // 4. NAMA KURSUS
        $image->text($kursus, $width / 2, 970, function ($font) use ($fontBold) {
            $font->file($fontBold);
            $font->size(70);
            $font->color('003d82');
            $font->align('center');
            $font->valign('top');
        });

        // 5. TANGGAL
        $tanggalText = "Telah menyelesaikan kursus pada tanggal " .  $tanggalSelesai;
        $image->text($tanggalText, $width / 2, 1080, function ($font) use ($fontRegular) {
            $font->file($fontRegular);
            $font->size(60);
            $font->color('555555');
            $font->align('center');
            $font->valign('top');
        });

        // Encode to PNG
        $encoded = $image->toPng();

        $filename = 'Sertifikat-' . str_replace(' ', '-', $user->name) . '-' . time() . '.png';

        \Log::info('Certificate generated', [
            'user' => $user->name,
            'user_id' => $user->id,
            'course' => $kursus,
            'certificate_number' => $certificateId,
            'completed_at' => $userCourse->completed_at->toDateTimeString()
        ]);

        return response($encoded->toString())
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function show($userCourseId)
    {
        /** @var User $user */
        $user = Auth::user();

        $userCourse = UserCourse::with('kursus')
            ->where('id', $userCourseId)
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->firstOrFail();

        if (!$userCourse->kursus->certificate) {
            abort(404, 'Sertifikat tidak tersedia untuk kursus ini');
        }

        $templatePath = public_path('assets/media/certificate-template.png');

        if (!file_exists($templatePath)) {
            abort(404, 'Template sertifikat tidak ditemukan');
        }

        $manager = new ImageManager(new Driver());
        $image = $manager->read($templatePath);

        $fontRegular = public_path('assets/fonts/times.ttf');
        $fontBold = public_path('assets/fonts/timesbd.ttf');

        if (!file_exists($fontRegular)) {
            $fontRegular = public_path('assets/fonts/Arial.ttf');
        }
        if (!file_exists($fontBold)) {
            $fontBold = $fontRegular;
        }

        $nama = strtoupper($user->name);
        $kursus = $userCourse->kursus->title;
        $tanggalSelesai = $userCourse->completed_at->locale('id')->translatedFormat('d F Y');

        // Generate certificate number dengan format baru
        $certificateId = $this->generateCertificateNumber($userCourse->completed_at);

        $width = $image->width();
        $height = $image->height();

        // Apply same text layout as download
        $image->text('CERTIFICATE OF COMPLETION', $width / 2, 220, function ($font) use ($fontBold) {
            $font->file($fontBold);
            $font->size(72);
            $font->color('003d82');
            $font->align('center');
            $font->valign('middle');
        });

        $image->text('NOMOR SERTIFIKAT: ' . $certificateId, $width / 2, 300, function ($font) use ($fontRegular) {
            $font->file($fontRegular);
            $font->size(40);
            $font->color('666666');
            $font->align('center');
            $font->valign('middle');
        });

        $image->text($nama, $width / 2, 850, function ($font) use ($fontBold) {
            $font->file($fontBold);
            $font->size(82);
            $font->color('1a1a1a');
            $font->align('center');
            $font->valign('bottom');
        });

        $image->text($kursus, $width / 2, 970, function ($font) use ($fontBold) {
            $font->file($fontBold);
            $font->size(70);
            $font->color('003d82');
            $font->align('center');
            $font->valign('top');
        });

        $tanggalText = "Telah menyelesaikan kursus pada tanggal " . $tanggalSelesai;
        $image->text($tanggalText, $width / 2, 1080, function ($font) use ($fontRegular) {
            $font->file($fontRegular);
            $font->size(60);
            $font->color('555555');
            $font->align('center');
            $font->valign('top');
        });

        $encoded = $image->toPng();

        return response($encoded->toString())
            ->header('Content-Type', 'image/png');
    }

    public function preview($userCourseId)
    {
        /** @var User $user */
        $user = Auth::user();

        $userCourse = UserCourse::with('kursus')
            ->where('id', $userCourseId)
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->firstOrFail();

        if (!$userCourse->kursus->certificate) {
            abort(404, 'Sertifikat tidak tersedia untuk kursus ini');
        }

        // Generate certificate number untuk preview
        $certificateId = $this->generateCertificateNumber($userCourse->completed_at);

        return view('user.certificate.preview', compact('userCourse', 'certificateId'));
    }
}
