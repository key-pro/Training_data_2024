// 数字を変更したら進捗度(progress)を連動させる
$('#progress_number').on('input', function() {
  var progressValue = $(this).val();
  $('#progress').val(progressValue);
});