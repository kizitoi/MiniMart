<style>
    #chat-toggle-btn {
        position: fixed;
        bottom: 60px;
        right: 20px;
        z-index: 9999;
        background-color: #0073b1;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }

    #chat-popup {
        position: fixed;
        bottom: 100px;
        right: 20px;
        width: 300px;
        max-height: 400px;
        overflow-y: auto;
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 10px;
        display: none;
        z-index: 10000;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
    }

    #chat-popup-header {
        background-color: #0073b1;
        color: white;
        padding: 10px;
        border-radius: 10px 10px 0 0;
        font-weight: bold;
    }

    .chat-user {
        padding: 10px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .chat-user:hover {
        background-color: #f0f0f0;
    }

    .chat-user img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-left: 5px;
    }

    .online { background-color: green; }
    .offline { background-color: red; }

    .chat-info {
        flex: 1;
    }

    .chat-count {
        background: #0073b1;
        color: #fff;
        padding: 2px 6px;
        font-size: 12px;
        border-radius: 10px;
        margin-left: auto;
    }
</style>




<script>
function sendMessage(event, receiverId) {
    event.preventDefault(); // prevent form from reloading the page

    const input = document.getElementById('chat-input-' + receiverId);
    const message = input.value.trim();
    if (!message) return;

    const formData = new FormData();
    formData.append('receiver_id', receiverId);
    formData.append('message', message);

    fetch('/chat/send', {
        method: 'POST',
        body: formData
    }).then(response => response.text())
      .then(() => {
          input.value = '';
          reloadChatBody(receiverId);
      });
}

function reloadChatBody(userId) {
    fetch(`/chat/load/${userId}`)
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newBody = doc.querySelector('.chat-body');
            const newFooter = doc.querySelector('.chat-footer');

            const chatBox = document.getElementById('chat-box-' + userId);
            chatBox.querySelector('.chat-body').innerHTML = newBody.innerHTML;
            chatBox.querySelector('.chat-footer').innerHTML = newFooter.innerHTML;
        });
}
</script>



<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='company')
      { ?>
<!-- Toggle Button -->
<div id="chat-toggle-btn">Messaging</div>

<!-- Chat Popup -->
<div id="chat-popup">
    <div id="chat-popup-header">Messaging</div>
    <div id="chat-popup-body">
        <?php foreach ($users as $user): ?>
            <div class="chat-user" onclick="openChatWindow(<?= $user['user_id'] ?>)">
              <!--  <img src="<? //= base_url('public/uploads/' . $user['profile_image']) ?>" alt="user image">-->

                <img src="<?= $user['profile_image'] ?>" alt="user image">

                  <div class="chat-count"><?= $user['chat_count'] ?? 0 ?></div>
                        <span class="status-dot <?= $user['logged_in'] ? 'online' : 'offline' ?>"></span>
                <div class="chat-info">
                    <?=  esc($user['name'])?>    <?= esc($user['username'])?>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
</div>


  <?php } ?>
  <?php endforeach; ?>
  <?php endif; ?>

<script>
    const toggleBtn = document.getElementById('chat-toggle-btn');
    const chatPopup = document.getElementById('chat-popup');

    toggleBtn.addEventListener('click', () => {
        chatPopup.style.display = (chatPopup.style.display === 'none' || chatPopup.style.display === '') ? 'block' : 'none';
    });

</script>



<!--<script>
function openChatWindow(userId) {
    // TODO: You can open an actual chat window here or load the conversation via AJAX
    alert('Open chat with user ID: ' + userId);
}
</script> -->

<div id="chat-windows-container" style="position: fixed; bottom: 60px; right: 340px; z-index: 9999;"></div>

<script>
    const chatWindows = {};

    function openChatWindow(userId) {
        if (chatWindows[userId]) return; // Already open

        fetch(`/chat/load/${userId}`)
            .then(response => response.text())
            .then(html => {
                const chatBox = document.createElement('div');
                chatBox.innerHTML = html;
                document.getElementById('chat-windows-container').appendChild(chatBox);
                chatWindows[userId] = chatBox;
            });
    }

    function closeChatWindow(userId) {
        const box = chatWindows[userId];
        if (box) {
            box.remove();
            delete chatWindows[userId];
        }
    }
</script>
