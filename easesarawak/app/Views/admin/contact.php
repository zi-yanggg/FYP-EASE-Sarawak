<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/contact.css') ?>">

<?php
function contactRowClass(bool $isRead): string
{
    return $isRead ? 'contact-row--read' : 'contact-row--new';
}
?>

<div class="contact-page">
    <div class="ease-page-head d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="ease-crumb">EASE Admin &middot; <b>Contact</b></div>
            <h1 class="ease-page-title">Contacts Ledger</h1>
        </div>
    </div>

    <div class="contact-card">
        <!-- Card bar: title + filter tabs -->
        <div class="contact-card__bar">
            <div class="contact-tabs">
                <a href="<?= base_url('admin/contact') ?>"
                   class="contact-tab <?= (request()->getGet('filter') === null || request()->getGet('filter') === 'all') ? 'active' : '' ?>">
                    All
                </a>
                <a href="<?= base_url('admin/contact?filter=new') ?>"
                   class="contact-tab <?= request()->getGet('filter') === 'new' ? 'active' : '' ?>">
                    New
                </a>
                <a href="<?= base_url('admin/contact?filter=read') ?>"
                   class="contact-tab <?= request()->getGet('filter') === 'read' ? 'active' : '' ?>">
                    Read
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="contact-tbl">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $message): ?>
                            <?php
                                $msgStatus = trim((string) ($message['status'] ?? ''));
                                $isRead    = $msgStatus === 'read';

                                $replyEmail   = trim((string) ($message['email'] ?? ''));
                                $replySubject = 'Re: ' . ($message['subject'] ?? '');
                                $replyBody    = "Hi,\n\nRegarding your message:\n\n" . ($message['msg'] ?? '') . "\n\nBest regards,\nEASE SARAWAK";
                                $replyUrl = $replyEmail !== ''
                                    ? 'https://mail.google.com/mail/?view=cm&fs=1'
                                        . '&to=' . rawurlencode($replyEmail)
                                        . '&su=' . rawurlencode($replySubject)
                                        . '&body=' . rawurlencode($replyBody)
                                    : '';
                            ?>
                            <tr class="<?= esc(contactRowClass($isRead)) ?>" id="message-row-<?= $message['msg_id'] ?>">
                                <td><span class="contact-email"><?= esc($message['email']) ?></span></td>
                                <td><?= esc($message['phone']) ?></td>
                                <td><?= esc($message['subject']) ?></td>
                                <td>
                                    <span class="contact-msg-preview"><?= esc($message['msg']) ?></span>
                                </td>
                                <td>
                                    <span class="contact-status <?= $isRead ? 'contact-status--read' : 'contact-status--new' ?>">
                                        <?= $isRead ? 'Read' : ($msgStatus === '' ? 'New' : ucfirst($msgStatus)) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="contact-actions">
                                        <button type="button"
                                            class="contact-act-btn view-message-btn"
                                            title="View Message"
                                            data-email="<?= esc($message['email'], 'attr') ?>"
                                            data-phone="<?= esc($message['phone'], 'attr') ?>"
                                            data-subject="<?= esc($message['subject'], 'attr') ?>"
                                            data-message="<?= esc($message['msg'], 'attr') ?>"
                                            data-created="<?= esc($message['created_date'], 'attr') ?>"
                                            data-msg-id="<?= esc($message['msg_id'], 'attr') ?>"
                                            data-reply-url="<?= esc($replyUrl, 'attr') ?>">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                        <?php if ($replyUrl !== ''): ?>
                                            <a href="<?= esc($replyUrl, 'attr') ?>"
                                               class="contact-act-btn"
                                               title="Reply in Gmail"
                                               target="_blank"
                                               rel="noopener noreferrer">
                                                <i class="fas fa-reply"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="contact-act-btn disabled" title="No email address" aria-disabled="true">
                                                <i class="fas fa-reply"></i>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="contact-empty">No messages found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (isset($pager)): ?>
            <div class="contact-pager">
                <?= $pager->links('group1', 'pagination') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Message Details Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content contact-modal-content">
            <div class="modal-header contact-modal-head">
                <h5 class="modal-title" id="messageModalLabel">
                    <i class="fa fa-envelope-open-text me-2"></i>Message Details
                </h5>
                <button type="button" class="ease-modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body px-3 py-3">
                <div class="contact-detail-wrap">
                    <!-- Sender Information -->
                    <div class="contact-detail-section">
                        <div class="contact-detail-section__head">
                            <i class="fas fa-user me-2"></i>Sender Information
                        </div>
                        <div class="contact-detail-section__body">
                            <div class="contact-kv-grid">
                                <div class="contact-kv">
                                    <div class="contact-kv__k">Email</div>
                                    <div class="contact-kv__v" id="messageModalEmail"></div>
                                </div>
                                <div class="contact-kv">
                                    <div class="contact-kv__k">Contact</div>
                                    <div class="contact-kv__v" id="messageModalPhone"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Message -->
                    <div class="contact-detail-section">
                        <div class="contact-detail-section__head">
                            <i class="fas fa-envelope me-2"></i>Message
                        </div>
                        <div class="contact-detail-section__body">
                            <div class="contact-kv-grid">
                                <div class="contact-kv">
                                    <div class="contact-kv__k">Subject</div>
                                    <div class="contact-kv__v" id="messageModalSubject"></div>
                                </div>
                                <div class="contact-kv">
                                    <div class="contact-kv__k">Received</div>
                                    <div class="contact-kv__v" id="messageModalCreated"></div>
                                </div>
                                <div class="contact-kv contact-kv--full">
                                    <div class="contact-kv__k">Message</div>
                                    <pre class="contact-msg-body" id="messageModalBody"></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('admin/footer'); ?>

<script>
(function () {
    'use strict';

    // ── Mount modal on document.body to fix modal-backdrop stacking ────
    function mountContactOverlays() {
        var modal = document.getElementById('messageModal');
        if (modal && modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', mountContactOverlays);
    } else {
        mountContactOverlays();
    }

    var messageModalEl = document.getElementById('messageModal');

    // ── Clean up backdrop and body scroll-lock on hide ─────────────────
    messageModalEl.addEventListener('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('padding-right');
        document.body.style.removeProperty('overflow');
        document.querySelectorAll('.modal-backdrop').forEach(function (b) { b.remove(); });
    });

    var messageModal = new bootstrap.Modal(messageModalEl);

    function openMessageModal(messageData) {
        document.getElementById('messageModalEmail').textContent   = messageData.email   || '—';
        document.getElementById('messageModalPhone').textContent   = messageData.phone   || '—';
        document.getElementById('messageModalSubject').textContent = messageData.subject || '—';
        document.getElementById('messageModalCreated').textContent = messageData.created || '—';
        document.getElementById('messageModalBody').textContent    = messageData.message || '—';

        messageModal.show();

        if (messageData.msg_id) {
            fetch('<?= base_url('admin/markMessageRead') ?>/' + messageData.msg_id, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': getCsrfToken() }
            }).then(function () {
                var row = document.getElementById('message-row-' + messageData.msg_id);
                    if (row) {
                    row.classList.remove('contact-row--new');
                    row.classList.add('contact-row--read');
                    var badge = row.querySelector('.contact-status');
                    if (badge) {
                        badge.className = 'contact-status contact-status--read';
                        badge.textContent = 'Read';
                    }
                }
                if (messageData.removeHeaderDot) {
                    removeHeaderNotificationDot(messageData.msg_id);
                }
            });
        }
    }

    document.querySelectorAll('.view-message-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            openMessageModal({
                email:           this.dataset.email,
                phone:           this.dataset.phone,
                subject:         this.dataset.subject,
                message:         this.dataset.message,
                created:         this.dataset.created,
                msg_id:          this.dataset.msgId,
                removeHeaderDot: true
            });
        });
    });

    // ── Auto-open from URL param (notification dropdown link) ──────────
    var urlParams = new URLSearchParams(window.location.search);
    var messageId = urlParams.get('message_id');
    if (messageId) {
        fetch('<?= base_url('admin/getMessage') ?>/' + messageId)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success && data.message) {
                    openMessageModal({
                        email:           data.message.email,
                        phone:           data.message.phone,
                        subject:         data.message.subject,
                        message:         data.message.msg,
                        created:         data.message.created_date,
                        msg_id:          data.message.msg_id,
                        removeHeaderDot: false
                    });
                }
            });
    }

    function removeHeaderNotificationDot(messageId) {
        document.querySelectorAll('a[href*="message_id=' + messageId + '"] .status-indicator')
            .forEach(function (dot) { dot.remove(); });

        var notificationSpan = document.querySelector('#messageDropdown .notification');
        if (notificationSpan) {
            var count = Math.max(0, (parseInt(notificationSpan.textContent) || 0) - 1);
            if (count > 0) {
                notificationSpan.textContent = count;
            } else {
                notificationSpan.remove();
            }
        }
    }
}());
</script>
