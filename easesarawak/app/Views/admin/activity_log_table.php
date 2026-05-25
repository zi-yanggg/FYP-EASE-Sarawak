<?php
function alogDetectType(string $action): string {
    $a = strtolower($action);
    if (str_contains($a, 'status') || str_contains($a, 'pending') || str_contains($a, 'in progress') || str_contains($a, 'completed')) {
        return 'Status Changes';
    }
    if (str_contains($a, 'note') || str_contains($a, 'comment')) {
        return 'Notes';
    }
    return 'Other Activity';
}

function alogReplaceStatuses(string $action): string {
    $map = [
        'Pending'     => '<span class="ord-status-badge ord-status--pending">Pending</span>',
        'In Progress' => '<span class="ord-status-badge ord-status--progress">In Progress</span>',
        'Completed'   => '<span class="ord-status-badge ord-status--done">Completed</span>',
    ];
    return str_replace(array_keys($map), array_values($map), esc($action));
}

// Returns 'added' | 'edited' | 'deleted' | 'updated' (legacy fallback)
function alogDetectNoteAction(string $action): string {
    $a = strtolower($action);
    if (str_starts_with($a, 'added note'))   return 'added';
    if (str_starts_with($a, 'edited note'))  return 'edited';
    if (str_starts_with($a, 'deleted note')) return 'deleted';
    return 'updated';
}

// Extracts note content from action string; returns '' for delete or unrecognised
function alogExtractNote(string $action): string {
    // New formats: 'Added note: "..."'  /  'Edited note: "..."'
    if (preg_match('/(?:Added|Edited) note: "(.*)"$/si', $action, $m)) {
        return trim($m[1]);
    }
    // Legacy format: 'Changed note to "..."'
    if (preg_match('/Changed note to "(.*)"$/si', $action, $m)) {
        return trim($m[1]);
    }
    return '';
}

$typeOrder = ['Status Changes', 'Notes', 'Other Activity'];
$typeIcons = [
    'Status Changes' => 'fa-sync-alt',
    'Notes'          => 'fa-sticky-note',
    'Other Activity' => 'fa-ellipsis-h',
];
$noteActionLabels = [
    'added'   => 'Added note',
    'edited'  => 'Updated note',
    'deleted' => 'Deleted note',
    'updated' => 'Updated note',
];
?>
<?php if (!empty($logs)): ?>
    <?php
    $groups = [];
    foreach ($logs as $log) {
        $type            = alogDetectType($log['action']);
        $groups[$type][] = $log;
    }
    ?>
    <div class="ord-log-wrap">
        <?php foreach ($typeOrder as $type): ?>
            <?php if (empty($groups[$type])): continue; endif; ?>
            <?php $items = $groups[$type]; $icon = $typeIcons[$type]; ?>
            <div class="ord-detail-section">
                <div class="ord-log-group-head">
                    <i class="fas <?= $icon ?>"></i>
                    <?= esc($type) ?>
                    <span class="ord-log-group-count"><?= count($items) ?></span>
                </div>
                <div class="ord-log-list">
                    <?php foreach ($items as $log): ?>
                        <?php
                        $noteAct  = $type === 'Notes' ? alogDetectNoteAction($log['action']) : '';
                        $noteText = $type === 'Notes' ? alogExtractNote($log['action'])      : '';
                        ?>
                        <div class="ord-log-row<?= $noteAct === 'deleted' ? ' ord-log-row--deleted' : '' ?>">
                            <div class="ord-log-icon<?= $noteAct === 'deleted' ? ' ord-log-icon--deleted' : '' ?>">
                                <i class="fas <?= $noteAct === 'deleted' ? 'fa-trash-alt' : $icon ?>"></i>
                            </div>
                            <div class="ord-log-body">
                                <div class="ord-log-action">
                                    <b><?= esc($log['username']); ?></b>
                                    &mdash;
                                    <?php if ($type === 'Notes'): ?>
                                        <?= esc($noteActionLabels[$noteAct]) ?>
                                    <?php else: ?>
                                        <?= alogReplaceStatuses($log['action']); ?>
                                    <?php endif; ?>
                                </div>
                                <?php if ($type === 'Notes' && $noteAct === 'deleted'): ?>
                                    <div class="ord-log-note-deleted">Note removed</div>
                                <?php elseif ($type === 'Notes' && $noteText !== ''): ?>
                                    <div class="ord-log-note-content"><?= esc($noteText) ?></div>
                                <?php endif; ?>
                                <div class="ord-log-meta">
                                    <i class="fas fa-clock"></i>
                                    <?= date('d M Y, h:i A', strtotime($log['modified_date'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="ord-log-empty">No activity found for this order.</div>
<?php endif; ?>
