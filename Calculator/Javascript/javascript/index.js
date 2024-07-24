// ボタンがクリックされたときの計算を行う関数
function get_calc(btn) {
  // イコールボタンがクリックされた場合、式を評価して結果を表示
  if(btn.value == "=") {
    if(document.dentaku.display.value === "") {
      // 式が空の場合は処理を終了
      return;
    }
    document.dentaku.display.value = eval(document.dentaku.display.value);
  } 
  // クリアボタンがクリックされた場合、表示をクリア
  else if (btn.value == "C") {
    document.dentaku.display.value = "";
  } else if (btn.value === "←") {
  // 1文字削除処理
  document.dentaku.display.value = document.dentaku.display.value.slice(0, -1);
  
  // それ以外のボタンがクリックされた場合
  }else {
    // 乗算記号を正規の乗算記号に変換
    if (btn.value == "×") {
      btn.value = "*";
    } 
    // 除算記号を正規の除算記号に変換
    else if (btn.value == "÷") {
      btn.value = "/";
    } 
    // ボタンの値を表示に追加し、乗算と除算ボタンの値を更新
    document.dentaku.display.value += btn.value;
    document.dentaku.multi_btn.value = "×";
    document.dentaku.div_btn.value = "÷";
  }
}