// jQueryを使用してドキュメントが読み込まれたときに実行
$(document).ready(function() {
  // ボタンがクリックされたときにget_calc関数を呼び出す
  $("button").click(function() {
    var btn = this;
    if(btn.value === "=") {
      if($("#dentaku display").val() === "") {
        return;
      }
      $("#dentaku display").val(eval($("#dentaku display").val()));
    } else if (btn.value === "C") {
      $("#dentaku display").val("");
    } else {
      if (btn.value === "×") {
        btn.value = "*";
      } else if (btn.value === "÷") {
        btn.value = "/";
      }
      $("#dentaku display").val($("#dentaku display").val() + btn.value);
      $("#dentaku multi_btn").val("×");
      $("#dentaku div_btn").val("÷");
    }
  });
});