<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CaptchaController extends Controller
{
    /**
     * Karakter yang dipakai buat kode captcha.
     * Huruf/angka yang gampang ketuker (0/O, 1/I, dst) sengaja dibuang.
     */
    private const CHARSET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    private const LENGTH = 5;

    /**
     * Generate captcha baru: simpan jawabannya di session,
     * lalu balikin gambar SVG-nya ke frontend.
     */
    public function generate(Request $request): Response
    {
        $code = collect(str_split(self::CHARSET))
            ->shuffle()
            ->take(self::LENGTH)
            ->implode('');

        $request->session()->put('captcha_code', $code);
        $request->session()->put('captcha_expires_at', now()->addMinutes(5));

        return response($this->renderSvg($code), 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Cek input captcha terhadap kode yang tersimpan di session.
     * Kode langsung dihapus setelah dicek sekali (dipakai habis),
     * biar gak bisa di-brute-force pakai kode yang sama berulang-ulang.
     */
    public static function verify(Request $request, ?string $input): bool
    {
        $stored = $request->session()->pull('captcha_code');
        $expiresAt = $request->session()->pull('captcha_expires_at');

        if (! $stored || ! $input) {
            return false;
        }

        if ($expiresAt && now()->greaterThan($expiresAt)) {
            return false;
        }

        return hash_equals(strtoupper($stored), strtoupper($input));
    }

    /**
     * Render kode captcha jadi SVG dengan noise garis, titik,
     * dan rotasi tiap karakter biar gak gampang dibaca mesin OCR sederhana.
     */
    private function renderSvg(string $code): string
    {
        $width = 160;
        $height = 60;
        $colors = ['#111827', '#1f2937', '#374151'];

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 '.$width.' '.$height.'" role="img" aria-label="Captcha">';
        $svg .= '<rect width="100%" height="100%" fill="#ffffff"/>';

        // Garis-garis noise di background
        for ($i = 0; $i < 6; $i++) {
            $x1 = random_int(0, $width);
            $y1 = random_int(0, $height);
            $x2 = random_int(0, $width);
            $y2 = random_int(0, $height);
            $color = $colors[array_rand($colors)];

            $svg .= '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" stroke="'.$color.'" stroke-width="1" opacity="0.35"/>';
        }

        // Titik-titik noise
        for ($i = 0; $i < 40; $i++) {
            $cx = random_int(0, $width);
            $cy = random_int(0, $height);

            $svg .= '<circle cx="'.$cx.'" cy="'.$cy.'" r="1" fill="#9ca3af" opacity="0.5"/>';
        }

        // Karakter captcha, tiap huruf diputar & digeser dikit-dikit
        $chars = str_split($code);
        $spacing = intdiv($width, count($chars) + 1);

        foreach ($chars as $i => $char) {
            $x = $spacing * ($i + 1);
            $y = random_int(36, 44);
            $rotate = random_int(-25, 25);
            $size = random_int(24, 30);
            $color = $colors[array_rand($colors)];

            $svg .= '<text x="'.$x.'" y="'.$y.'" font-size="'.$size.'" font-family="monospace" font-weight="bold" fill="'.$color.'" transform="rotate('.$rotate.' '.$x.' '.$y.')" text-anchor="middle">'.htmlspecialchars($char).'</text>';
        }

        $svg .= '</svg>';

        return $svg;
    }
}