<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParticipantsImport implements ToCollection, WithHeadingRow
{
    protected Event $event;

    public array $results = [
        'added'         => 0,
        'already_exists' => 0,
        'not_found'     => [],
    ];

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function collection(Collection $rows)
    {
        $existingIds = $this->event->participants->pluck('id')->toArray();

        foreach ($rows as $row) {
            $email = trim($row['email'] ?? '');
            if (empty($email)) {
                continue;
            }

            $user = User::where('email', $email)->role('participant')->first();

            if (!$user) {
                $this->results['not_found'][] = $email;
                continue;
            }

            if (in_array($user->id, $existingIds)) {
                $this->results['already_exists']++;
                continue;
            }

            $this->event->participants()->syncWithoutDetaching([$user->id]);
            $existingIds[] = $user->id;
            $this->results['added']++;
        }
    }
}
