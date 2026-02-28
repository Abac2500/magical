<?php

namespace App\Services;

use App\Models\Animal;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FriendRecommendationService
{
    /**
     * @param Animal $animal
     * @param int $limit
     * @return Collection
     */
    public function for(Animal $animal, int $limit = 10): Collection
    {
        $excludedIds = $animal->friends()
            ->pluck('animals.id')
            ->push($animal->id)
            ->all();

        $baseQuery = fn() => Animal::with('species')
            ->whereNotIn('id', $excludedIds);

        $bestFriendName = $animal->best_friend_name;

        $byName = $this->filterBySimilarity(
            $baseQuery()->get(),
            $bestFriendName,
            fn(Animal $candidate) => $candidate->name,
            $limit
        );
        if ($byName->isNotEmpty()) {
            return $byName;
        }

        $byNickname = $this->filterBySimilarity(
            $baseQuery()->whereNotNull('nickname')->get(),
            $bestFriendName,
            fn(Animal $candidate) => $candidate->nickname ?? '',
            $limit
        );
        if ($byNickname->isNotEmpty()) {
            return $byNickname;
        }

        $sameSpeciesOppositeGender = $baseQuery()
            ->where('species_id', $animal->species_id)
            ->where('gender', '!=', $animal->gender->value)
            ->limit($limit)
            ->get();
        if ($sameSpeciesOppositeGender->isNotEmpty()) {
            return $sameSpeciesOppositeGender;
        }

        return $baseQuery()
            ->where('species_id', $animal->species_id)
            ->limit($limit)
            ->get();
    }

    /**
     * @param Collection $candidates
     * @param string $needle
     * @param callable $fieldResolver
     * @param int $limit
     * @return Collection
     */
    private function filterBySimilarity(
        Collection $candidates,
        string     $needle,
        callable   $fieldResolver,
        int        $limit
    ): Collection
    {
        $normalizedNeedle = $this->normalize($needle);

        return $candidates
            ->map(function (Animal $candidate) use ($fieldResolver, $normalizedNeedle): ?array {
                $value = $this->normalize($fieldResolver($candidate));
                if ($value === '' || $normalizedNeedle === '') {
                    return null;
                }

                $distance = $this->unicodeLevenshtein($normalizedNeedle, $value);
                $threshold = max(
                    1,
                    intdiv(max(mb_strlen($normalizedNeedle), mb_strlen($value)), 3)
                );

                return $distance <= $threshold
                    ? ['candidate' => $candidate, 'distance' => $distance]
                    : null;
            })
            ->filter()
            ->sortBy('distance')
            ->take($limit)
            ->pluck('candidate')
            ->values();
    }

    /**
     * @param string $value
     * @return string
     */
    private function normalize(string $value): string
    {
        return Str::of($value)
            ->squish()
            ->lower()
            ->toString();
    }

    /**
     * @param string $first
     * @param string $second
     * @return int
     */
    private function unicodeLevenshtein(string $first, string $second): int
    {
        $firstChars = mb_str_split($first) ?: [];
        $secondChars = mb_str_split($second) ?: [];

        $firstLength = count($firstChars);
        $secondLength = count($secondChars);

        if ($firstLength === 0) {
            return $secondLength;
        }
        if ($secondLength === 0) {
            return $firstLength;
        }

        $previousRow = range(0, $secondLength);

        for ($i = 1; $i <= $firstLength; $i++) {
            $currentRow = [$i];

            for ($j = 1; $j <= $secondLength; $j++) {
                $insertions = $currentRow[$j - 1] + 1;
                $deletions = $previousRow[$j] + 1;
                $substitutions = $previousRow[$j - 1] + ($firstChars[$i - 1] === $secondChars[$j - 1] ? 0 : 1);

                $currentRow[$j] = min($insertions, $deletions, $substitutions);
            }

            $previousRow = $currentRow;
        }

        return $previousRow[$secondLength];
    }
}
