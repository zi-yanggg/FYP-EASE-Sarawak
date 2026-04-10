<?= $this->include('admin/header'); ?>

<div class="container">
    <div class="page-inner">
        <div class="container-fluid">

            <!-- Page Header -->
            <div class="row">
                <div class="col-12">
                    <h2 class="page-title">Contacts Ledger</h2>
                </div>
            </div>

            <!-- Metrics Cards -->
            <!-- <div class="row">
                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center icon-warning">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <p class="card-category">Pending Response</p>
                                    <h4 class="card-title">42</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats"><i class="fa fa-arrow-up text-success"></i> +12%</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center icon-success">
                                        <i class="fas fa-smile"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <p class="card-category">Public Sentiment</p>
                                    <h4 class="card-title">88%</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats"><i class="fas fa-smile text-success"></i> Positive</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center icon-info">
                                        <i class="fas fa-bolt"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <p class="card-category">Avg Resolution</p>
                                    <h4 class="card-title">4.2h</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats"><i class="fas fa-bolt text-info"></i> Record Low</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center icon-primary">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <p class="card-category">Daily Inflow</p>
                                    <h4 class="card-title">156</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats">Last 24h</div>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Filters -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <ul class="nav nav-pills nav-secondary">
                                <li class="nav-item"><a class="nav-link <?= request()->getGet('filter') === null || request()->getGet('filter') === 'all' ? 'active' : '' ?>" href="<?= base_url('admin/contact') ?>">All Entries</a></li>
                                <li class="nav-item"><a class="nav-link <?= request()->getGet('filter') === 'new' ? 'active' : '' ?>" href="<?= base_url('admin/contact?filter=new') ?>">New</a></li>
                                <li class="nav-item"><a class="nav-link <?= request()->getGet('filter') === 'read' ? 'active' : '' ?>" href="<?= base_url('admin/contact?filter=read') ?>">Read</a></li>
                            </ul>
                            <!-- <button class="btn btn-secondary btn-sm">
                                <i class="fas fa-filter"></i> Advanced Filters
                            </button> -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contacts Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Subject</th>
                                            <th>Message</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($messages)): ?>
                                            <?php foreach ($messages as $message): ?>
                                                <tr id="message-row-<?= $message['msg_id'] ?>">
                                                    <td>
                                                        <strong><?= esc($message['email']) ?></strong><br>
                                                        <!-- <small class="text-muted">UID: 8820-K</small> -->
                                                    </td>
                                                    <td>
                                                        <?= esc($message['phone']) ?><br>
                                                        <!-- <small>+60 12-888 2394</small> -->
                                                    </td>
                                                    <td><?= esc($message['subject']) ?></td>
                                                    <td class="text-truncate" style="max-width: 280px;">
                                                        <?= esc($message['msg']) ?>
                                                    </td>
                                                    <?php $messageStatus = trim((string) ($message['status'] ?? '')); ?>
                                                    <td><span class="badge <?= $messageStatus === 'read' ? 'badge-completed' : 'badge-pending' ?>"><?= ucfirst($messageStatus === '' ? 'new' : $messageStatus) ?></span></td>
                                                    <td class="text-end">
                                                        <button
                                                            type="button"
                                                            class="btn btn-icon btn-round btn-progress btn-sm view-message-btn"
                                                            data-email="<?= esc($message['email'], 'attr') ?>"
                                                            data-phone="<?= esc($message['phone'], 'attr') ?>"
                                                            data-subject="<?= esc($message['subject'], 'attr') ?>"
                                                            data-message="<?= esc($message['msg'], 'attr') ?>"
                                                            data-created="<?= esc($message['created_date'], 'attr') ?>"
                                                            data-msg-id="<?= esc($message['msg_id'], 'attr') ?>">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No messages found.</td>
                                            </tr>
                                        <?php endif; ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <?= isset($pager) ? $pager->links('group1', 'pagination') : '' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section -->
            <!-- <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Regional Distribution Abstract</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-1"><span>Kuching Division</span><span>45%</span></div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" style="width:45%"></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-1"><span>Miri Sector</span><span>28%</span></div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width:28%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between mb-1"><span>Sibu Regional</span><span>12%</span></div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-secondary" style="width:12%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5>Administrator Insight</h5>
                            <p class="mt-3 mb-4">Response efficiency has increased by 14.2% since the new ledger implementation.</p>
                            <a href="#" class="btn btn-light btn-sm">View Performance Data →</a>
                        </div>
                    </div>
                </div>
            </div> -->

        </div>
    </div>
</div>

<!-- Message Details Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-softblue text-white">
                <h5 class="modal-title fw-semibold" id="messageModalLabel">
                    <i class="fa fa-envelope-open-text me-2"></i>Message Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3 text-muted">Email</dt>
                    <dd class="col-sm-9" id="messageModalEmail"></dd>

                    <dt class="col-sm-3 text-muted">Contact</dt>
                    <dd class="col-sm-9" id="messageModalPhone"></dd>

                    <dt class="col-sm-3 text-muted">Subject</dt>
                    <dd class="col-sm-9" id="messageModalSubject"></dd>

                    <dt class="col-sm-3 text-muted">Received</dt>
                    <dd class="col-sm-9" id="messageModalCreated"></dd>

                    <dt class="col-sm-3 text-muted">Message</dt>
                    <dd class="col-sm-9 mb-0">
                        <pre id="messageModalBody" class="mb-0 text-break" style="white-space: pre-wrap;"></pre>
                    </dd>
                </dl>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageModalEl = document.getElementById('messageModal');
        const messageModal = new bootstrap.Modal(messageModalEl);

        function openMessageModal(messageData, removeHeaderWhenRead = true) {
            document.getElementById('messageModalEmail').textContent = messageData.email || '-';
            document.getElementById('messageModalPhone').textContent = messageData.phone || '-';
            document.getElementById('messageModalSubject').textContent = messageData.subject || '-';
            document.getElementById('messageModalCreated').textContent = messageData.created || '-';
            document.getElementById('messageModalBody').textContent = messageData.message || '-';

            messageModal.show();

            // Mark as read
            if (messageData.msg_id) {
                fetch('<?= base_url('admin/markMessageRead') ?>/' + messageData.msg_id, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(() => {
                    // Update the badge in the table
                    const row = document.getElementById('message-row-' + messageData.msg_id);
                    if (row) {
                        const badge = row.querySelector('.badge');
                        if (badge) {
                            badge.className = 'badge badge-completed';
                            badge.textContent = 'Read';
                        }
                    }
                    if (removeHeaderWhenRead) {
                        removeHeaderNotificationDot(messageData.msg_id);
                    }
                });
            }
        }

        document.querySelectorAll('.view-message-btn').forEach(button => {
            button.addEventListener('click', function() {
                const messageData = {
                    email: this.dataset.email,
                    phone: this.dataset.phone,
                    subject: this.dataset.subject,
                    message: this.dataset.message,
                    created: this.dataset.created,
                    msg_id: this.dataset.msgId
                };
                openMessageModal(messageData, true);
            });
        });

        // Check for message_id in URL
        const urlParams = new URLSearchParams(window.location.search);
        const messageId = urlParams.get('message_id');
        if (messageId) {
            // Fetch message data and open modal
            fetch('<?= base_url('admin/getMessage') ?>/' + messageId)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.message) {
                        openMessageModal({
                            email: data.message.email,
                            phone: data.message.phone,
                            subject: data.message.subject,
                            message: data.message.msg,
                            created: data.message.created_date,
                            msg_id: data.message.msg_id,
                        }, false);
                    }
                });
        }

        function removeHeaderNotificationDot(messageId) {
            const matchingLinks = document.querySelectorAll('a[href*="message_id=' + messageId + '"] .status-indicator');
            matchingLinks.forEach(dot => dot.remove());

            // Also decrement the notification count
            const notificationSpan = document.querySelector('#messageDropdown .notification');
            if (notificationSpan) {
                let count = parseInt(notificationSpan.textContent) || 0;
                count = Math.max(0, count - 1);
                if (count > 0) {
                    notificationSpan.textContent = count;
                } else {
                    notificationSpan.remove();
                }
            }
        }
    });
</script>

<!-- Optional: Include footer if you have one -->
<?= $this->include('admin/footer'); ?>