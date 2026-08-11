<?php

namespace App\Services;

use App\Models\Animal;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CodlabGenerator
{
    public const DIGITS = 6;

    /** Faixa padrão para novos codlabs (ex.: EQU300001). */
    public const RANGE_MIN = 300000;

    public const RANGE_MAX = 499999;

    public const DEFAULT_START = self::RANGE_MIN;

    /** Faixa para conversão de codlabs antigos (ex.: EQU501351). */
    public const CONVERTED_RANGE_MIN = 500000;

    public const CONVERTED_RANGE_MAX = 599999;

    /**
     * Gera codlab no formato SIGLA + 6 dígitos na faixa 300000–499999.
     */
    public static function generate(string $sigla = 'EQU'): string
    {
        $normalizedSigla = self::normalizeSigla($sigla);
        $number = self::nextAvailableNumber($normalizedSigla, self::RANGE_MIN, self::RANGE_MAX);

        return self::format($normalizedSigla, $number);
    }

    /**
     * Gera codlab a partir de um número base, incrementando até achar um livre.
     */
    public static function generateFromBase(string $sigla, int $baseNumber, ?int $excludeAnimalId = null): string
    {
        $normalizedSigla = self::normalizeSigla($sigla);

        if ($baseNumber >= self::CONVERTED_RANGE_MIN && $baseNumber <= self::CONVERTED_RANGE_MAX) {
            $rangeMin = self::CONVERTED_RANGE_MIN;
            $rangeMax = self::CONVERTED_RANGE_MAX;
        } else {
            $rangeMin = self::RANGE_MIN;
            $rangeMax = self::RANGE_MAX;
        }

        $nextNumber = max($rangeMin, min($rangeMax, (int) $baseNumber));
        $maxAttempts = 5000;

        for ($attempts = 0; $attempts < $maxAttempts; $attempts++) {
            $candidate = self::format($normalizedSigla, $nextNumber);
            $exists = Animal::where('codlab', $candidate)
                ->when($excludeAnimalId, fn ($query) => $query->where('id', '!=', $excludeAnimalId))
                ->exists();

            if (! $exists) {
                return $candidate;
            }

            $nextNumber++;

            if ($nextNumber > $rangeMax) {
                throw new RuntimeException("Limite de codlab numerico atingido para a sigla {$normalizedSigla} na faixa {$rangeMin}-{$rangeMax}");
            }
        }

        throw new RuntimeException("Nao foi possivel gerar codlab unico para a sigla {$normalizedSigla}");
    }

    public static function format(string $sigla, int $number): string
    {
        $normalizedSigla = self::normalizeSigla($sigla);

        if ($number < 0 || $number > 999999) {
            throw new RuntimeException('Numero de codlab fora do intervalo permitido (6 digitos).');
        }

        return $normalizedSigla . str_pad((string) $number, self::DIGITS, '0', STR_PAD_LEFT);
    }

    private static function normalizeSigla(string $sigla): string
    {
        return strtoupper(substr(trim($sigla), 0, 3));
    }

    private static function nextAvailableNumber(string $sigla, int $rangeMin, int $rangeMax): int
    {
        $totalLength = strlen($sigla) + self::DIGITS;

        $maxNumber = self::codlabNumberQuery($sigla, $totalLength, $rangeMin, $rangeMax)
            ->max(DB::raw('CAST(SUBSTRING(TRIM(codlab), 4) AS UNSIGNED)'));

        $next = max($rangeMin, ((int) $maxNumber) + 1);

        // Caminho rápido: continua a partir do maior número usado
        while ($next <= $rangeMax && Animal::where('codlab', self::format($sigla, $next))->exists()) {
            $next++;
        }

        if ($next <= $rangeMax) {
            return $next;
        }

        // Teto atingido, mas a faixa ainda tem buracos (ex.: max=399983 com só ~900 usados)
        $used = self::codlabNumberQuery($sigla, $totalLength, $rangeMin, $rangeMax)
            ->selectRaw('CAST(SUBSTRING(TRIM(codlab), 4) AS UNSIGNED) as num')
            ->pluck('num')
            ->map(fn ($n) => (int) $n)
            ->flip();

        for ($candidate = $rangeMin; $candidate <= $rangeMax; $candidate++) {
            if (!$used->has($candidate)) {
                return $candidate;
            }
        }

        throw new RuntimeException("Limite de codlab numerico atingido para a sigla {$sigla} na faixa {$rangeMin}-{$rangeMax}");
    }

    private static function codlabNumberQuery(string $sigla, int $totalLength, int $rangeMin, int $rangeMax)
    {
        return Animal::query()
            ->whereNotNull('codlab')
            ->whereRaw('UPPER(TRIM(codlab)) LIKE ?', [$sigla . '%'])
            ->whereRaw('LENGTH(TRIM(codlab)) = ?', [$totalLength])
            ->whereRaw('SUBSTRING(TRIM(codlab), 4) REGEXP ?', ['^[0-9]{' . self::DIGITS . '}$'])
            ->whereRaw('CAST(SUBSTRING(TRIM(codlab), 4) AS UNSIGNED) >= ?', [$rangeMin])
            ->whereRaw('CAST(SUBSTRING(TRIM(codlab), 4) AS UNSIGNED) <= ?', [$rangeMax]);
    }
}
