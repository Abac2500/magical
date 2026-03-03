<?php

namespace App\Services;

use App\Models\Animal;
use App\Models\Friendship;
use Illuminate\Database\Eloquent\Builder;
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
        $excludedIds = Friendship::where('animal_id', $animal->id)
            ->pluck('friend_id')
            ->push($animal->id)
            ->all();

        $baseQuery = fn(): Builder => (new Animal)
            ->newQuery()
            ->whereNotIn('id', $excludedIds);

        $bestFriendName = $animal->best_friend_name;

        $byNameIds = $this->findSimilarIds(
            $baseQuery()->select(['id', 'name']),
            $bestFriendName,
            'name',
            $limit
        );
        if ($byNameIds !== []) {
            return $this->loadAnimalsWithSpeciesByOrderedIds($byNameIds);
        }

        $byNicknameIds = $this->findSimilarIds(
            $baseQuery()
                ->whereNotNull('nickname')
                ->select(['id', 'nickname']),
            $bestFriendName,
            'nickname',
            $limit
        );
        if ($byNicknameIds !== []) {
            return $this->loadAnimalsWithSpeciesByOrderedIds($byNicknameIds);
        }

        $sameSpeciesOppositeGender = $baseQuery()
            ->where('species_id', $animal->species_id)
            ->where('gender', '!=', $animal->gender->value)
            ->orderBy('id')
            ->limit($limit)
            ->get()
            ->load('species');
        if ($sameSpeciesOppositeGender->isNotEmpty()) {
            return $sameSpeciesOppositeGender;
        }

        return $baseQuery()
            ->where('species_id', $animal->species_id)
            ->orderBy('id')
            ->limit($limit)
            ->get()
            ->load('species');
    }

    /**
     * @return array<int, int>
     */
    private function findSimilarIds(
        Builder $candidateQuery,
        string  $needle,
        string  $column,
        int     $limit
    ): array
    {
        $normalizedNeedle = $this->normalize($needle);
        if ($normalizedNeedle === '' || $limit < 1) {
            return [];
        }

        $bestMatches = [];

        /** @var Animal $candidate */
        foreach ($candidateQuery->orderBy('id')->cursor() as $candidate) {
            $value = $this->normalize((string)($candidate->{$column} ?? ''));
            if ($value === '') {
                continue;
            }

            $distance = $this->unicodeLevenshtein($normalizedNeedle, $value);
            $threshold = max(
                1,
                intdiv(max(mb_strlen($normalizedNeedle), mb_strlen($value)), 3)
            );

            if ($distance > $threshold) {
                continue;
            }

            $this->pushMatch($bestMatches, (int)$candidate->id, $distance, $limit);
        }

        usort($bestMatches, [$this, 'compareMatches']);

        return array_map(
            static fn(array $match): int => $match['id'],
            $bestMatches
        );
    }

    private function pushMatch(array &$bestMatches, int $id, int $distance, int $limit): void
    {
        $currentMatch = ['id' => $id, 'distance' => $distance];

        if (count($bestMatches) < $limit) {
            $bestMatches[] = $currentMatch;

            return;
        }

        $worstMatchIndex = 0;
        foreach ($bestMatches as $index => $match) {
            if ($this->compareMatches($bestMatches[$worstMatchIndex], $match) < 0) {
                $worstMatchIndex = $index;
            }
        }

        if ($this->compareMatches($currentMatch, $bestMatches[$worstMatchIndex]) < 0) {
            $bestMatches[$worstMatchIndex] = $currentMatch;
        }
    }

    private function compareMatches(array $left, array $right): int
    {
        return $left['distance'] <=> $right['distance']
            ?: $left['id'] <=> $right['id'];
    }

    private function loadAnimalsWithSpeciesByOrderedIds(array $orderedIds): Collection
    {
        $animalsById = Animal::with('species')
            ->whereIn('id', $orderedIds)
            ->get()
            ->keyBy('id');

        return collect($orderedIds)
            ->map(static function (int $id) use ($animalsById): ?Animal {
                $animal = $animalsById->get($id);

                return $animal instanceof Animal ? $animal : null;
            })
            ->filter()
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
