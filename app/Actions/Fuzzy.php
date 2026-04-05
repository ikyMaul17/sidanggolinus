<?php

namespace App\Actions;

class Fuzzy
{
    /**
     * Fungsi keanggotaan segitiga
     * a = batas bawah
     * b = titik puncak
     * c = batas ata
     */
    private function triMu(float $x, float $a, float $b, float $c): float
    {
        if ($x <= $a || $x >= $c) {
            return 0.0;
        }

        if ($x == $b) {
            return 1.0;
        }

        if ($x > $a && $x < $b) {
            return ($x - $a) / ($b - $a);
        }

        return ($c - $x) / ($c - $b);
    }

    /**
     * 1. FUZZIFIKASI
     * Mengubah nilai crisp menjadi derajat keanggotaan
     */
    private function fuzzification(float $x): array
    {
        return [
            'rendah' => $this->triMu($x, 1.0, 2.0, 3.0),
            'sedang' => $this->triMu($x, 2.0, 3.0, 4.0),
            'tinggi' => $this->triMu($x, 3.0, 4.0, 5.0),
        ];
    }

    /**
     * 2. INFERENSI / RULE EVALUATION
     * IF input Rendah THEN output Rendah
     * IF input Sedang THEN output Sedang
     * IF input Tinggi THEN output Tinggi
     */
    private function inference(array $mu): array
    {
        return [
            'rendah' => $mu['rendah'],
            'sedang' => $mu['sedang'],
            'tinggi' => $mu['tinggi'],
        ];
    }

    /**
     * 3. AGREGASI
     * Menggabungkan hasil semua aturan fuzzy
     */
    private function aggregation(array $alpha): array
    {
        return [
            'rendah' => $alpha['rendah'],
            'sedang' => $alpha['sedang'],
            'tinggi' => $alpha['tinggi'],
        ];
    }

    /**
     * 4. DEFUZZIFIKASI
     * Metode centroid diskrit
     */
    private function defuzzification(array $agg): float
    {
        $z = [
            'rendah' => 2.0,
            'sedang' => 3.0,
            'tinggi' => 4.0
        ];

        $numerator =
            ($agg['rendah'] * $z['rendah']) +
            ($agg['sedang'] * $z['sedang']) +
            ($agg['tinggi'] * $z['tinggi']);

        $denominator =
            $agg['rendah'] +
            $agg['sedang'] +
            $agg['tinggi'];

        if ($denominator == 0) {
            return 0;
        }

        return $numerator / $denominator;
    }

    /**
     * 5. INTERPRETASI OUTPUT
     */
    private function labelOutput(float $score): string
    {
        if ($score <= 2.0) {
            return 'Rendah';
        }

        if ($score <= 3.5) {
            return 'Sedang';
        }

        return 'Tinggi';
    }

    /**
     * Menghitung rata-rata jawaban Likert
     */
    private function averageLikert(array $answers): float
    {
        $n = count($answers);

        if ($n === 0) {
            return 0.0;
        }

        $sum = 0;

        foreach ($answers as $value) {
            $sum += (float) $value;
        }

        return $sum / $n;
    }

    /**
     * Fungsi utama yang dipanggil sistem
     */
    public function execute(array $answers)
    {
        // Hitung rata-rata
        $average = $this->averageLikert($answers);

        // 1 Fuzzifikasi
        $mu = $this->fuzzification($average);

        // 2 Inferensi
        $alpha = $this->inference($mu);

        // 3 Agregasi
        $agg = $this->aggregation($alpha);

        // 4 Defuzzifikasi
        $score = $this->defuzzification($agg);

        // 5 Interpretasi
        $label = $this->labelOutput($score);

        return [
            'input' => round($average, 4),

            'mu' => [
                'rendah' => round($mu['rendah'], 4),
                'sedang' => round($mu['sedang'], 4),
                'tinggi' => round($mu['tinggi'], 4),
            ],

            'alpha' => [
                'rendah' => round($alpha['rendah'], 4),
                'sedang' => round($alpha['sedang'], 4),
                'tinggi' => round($alpha['tinggi'], 4),
            ],

            'aggregasi' => [
                'rendah' => round($agg['rendah'], 4),
                'sedang' => round($agg['sedang'], 4),
                'tinggi' => round($agg['tinggi'], 4),
            ],

            'score' => round($score, 4),
            'label' => $label
        ];
    }
}