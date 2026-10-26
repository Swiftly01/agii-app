<?php

namespace App\Services\Tasks;

use App\Enums\TaskPriority;
use Carbon\CarbonImmutable;

// Plain input for assigning one task, so the service never touches the request
final class AssignTaskData
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly TaskPriority $priority,
        public readonly CarbonImmutable $deadline,
        public readonly ?int $targetLeads = null,
        public readonly ?float $targetConversion = null,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            title: $input['title'],
            description: $input['description'] ?? null,
            priority: TaskPriority::from($input['priority']),
            deadline: CarbonImmutable::parse($input['deadline']),
            targetLeads: isset($input['target_leads']) ? (int) $input['target_leads'] : null,
            targetConversion: isset($input['target_conversion']) ? (float) $input['target_conversion'] : null,
        );
    }
}
