<style>
    .chat-box {
        width: 300px;
        height: 350px;
        background: white;
        border: 1px solid #ccc;
        position: relative;
        margin-left: 10px;
        float: right;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .chat-header {
        background: #0073b1;
        color: white;
        padding: 10px;
        border-radius: 10px 10px 0 0;
        display: flex;
        justify-content: space-between;
    }
    .chat-body {
        height: 250px;
        overflow-y: auto;
        padding: 10px;
    }
    .chat-message {
        padding: 5px;
        margin: 5px 0;
        background: #f1f1f1;
        border-radius: 5px;
    }
    .chat-footer {
        padding: 10px;
        border-top: 1px solid #eee;
    }
</style>

<div class="chat-box" id="chat-box-<?= $user['user_id'] ?>">
    <div class="chat-header">
        <span><?= esc($user['name']) ?>  (<?= esc($user['username']) ?>)</span>
        <a href="javascript:void(0);" onclick="closeChatWindow(<?= $user['user_id'] ?>)">✖</a>
    </div>
    <div class="chat-body">
        <?php foreach ($messages as $msg): ?>
            <div class="chat-message"><strong><?= $msg['sender_id'] == session()->get('user_id') ? 'You' : esc($user['name']) ?>:</strong> <?= esc($msg['message']) ?></div>
        <?php endforeach; ?>
    </div>
    <div class="chat-footer">
        <!--<form method="post" action="<?= base_url('/chat/send') ?>">
            <input type="hidden" name="receiver_id" value="<?= $otherUserId ?>">
            <input type="text" name="message" class="form-control" placeholder="Type a message..." required>
        </form>-->

        <form onsubmit="sendMessage(event, <?= $otherUserId ?>)">
    <input type="text" name="message" id="chat-input-<?= $otherUserId ?>" class="form-control" placeholder="Type a message..." required>
</form>

    </div>
</div>
