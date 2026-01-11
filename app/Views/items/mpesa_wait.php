<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="modal" id="waitModal" tabindex="-1" style="display:block">
  <div class="modal-dialog">
    <div class="modal-content p-4">
      <h5>Awaiting MPESA Payment...</h5>
      <?php if(isset($stk_res['ResponseDescription'])): ?>
        <p><?=esc($stk_res['ResponseDescription'])?></p>
      <?php endif; ?>
      <div id="live_status" class="mt-3 text-info">Waiting for user to complete STK dialog...</div>
    </div>
  </div>
</div>

<script>
  // Poll server for transaction confirmation
  let chk = setInterval(()=>{
    $.getJSON('<?= site_url("mpesa_transactions?phone=").$phone ?>', data=>{
      if(data.length){
        clearInterval(chk);
        let t = data[0];
        $('#live_status').html(`<strong>✅ Paid:</strong> ${t.Amount} by ${t.PhoneNumber}`);
        setTimeout(()=>window.location='<?= site_url("salesorder/receipt/{$order->id}")?>',2000);
      }
    });
  },5000);
</script>
<?= $this->endSection() ?>
