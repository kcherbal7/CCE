<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">
    <h1>Contact DZ Form Submissions</h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('export_submissions'); ?>
        <p>
            <input type="submit" name="export_submissions" class="button-secondary" value="Export to Excel">
        </p>
    </form>
    
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Submission ID</th>
                <th>Date</th>
                <th>Full Name</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($submissions as $submission): ?>
            <tr>
                <td><?php echo esc_html($submission->submission_id); ?></td>
                <td><?php echo esc_html(date('Y-m-d H:i', strtotime($submission->submission_date))); ?></td>
                <td><?php echo esc_html($submission->full_name); ?></td>
                <td><?php echo esc_html($submission->phone_number); ?></td>
                <td><?php echo esc_html($submission->address); ?></td>
                <td><?php echo number_format($submission->total_price, 2) . ' DZD'; ?></td>
                <td>
                    <form method="post" action="" style="display: inline;">
                        <?php wp_nonce_field('update_submission_status'); ?>
                        <input type="hidden" name="submission_id" value="<?php echo esc_attr($submission->id); ?>">
                        <select name="status" onchange="this.form.submit()">
                            <option value="pending" <?php selected($submission->status, 'pending'); ?>>Pending</option>
                            <option value="completed" <?php selected($submission->status, 'completed'); ?>>Completed</option>
                            <option value="cancelled" <?php selected($submission->status, 'cancelled'); ?>>Cancelled</option>
                        </select>
                        <input type="hidden" name="update_status" value="1">
                    </form>
                    
                    <form method="post" action="" style="display: inline; margin-left: 5px;">
                        <?php wp_nonce_field('delete_submission'); ?>
                        <input type="hidden" name="submission_id" value="<?php echo esc_attr($submission->id); ?>">
                        <button type="submit" name="delete_submission" class="button delete" 
                                onclick="return confirm('Are you sure you want to delete this submission?')">
                            Delete
                        </button>
                    </form>
                </td>
                <td>
                    <span class="status-<?php echo esc_attr($submission->status); ?>">
                        <?php echo esc_html(ucfirst($submission->status)); ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<style>
    .status-pending { color: #f39c12; }
    .status-completed { color: #27ae60; }
    .status-cancelled { color: #c0392b; }
</style>