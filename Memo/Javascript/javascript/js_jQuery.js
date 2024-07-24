// 追加ボタンを取得
const addButton = $('#add-button');
// 入力フィールドを取得
const inputField = $('#add-text');
// メモエリアを取得
const memoArea = $('.input-area');

// 追加ボタンにクリックイベントを追加
addButton.on('click', function() {
  //textareaに入力された内容を取得しスペースを削除
  const memoText = inputField.val().trim();
  //スペースを削除後未入力かチェック
  if (memoText === '') {
    //未入力の場合は未入力エラーを表示する
    window.alert("メモ内容未入力です");
    return;
  }

  // メモ要素を作成
  const memoElement = $('<div></div>');
  // メモ要素のテキストコンテンツを設定
  memoElement.text(memoText);
  // メモエリアにメモ要素を追加
  memoArea.append(memoElement);
  // 入力フィールドの値を空にする
  inputField.val('');

  // メモ内容をローカルストレージに保存
  localStorage.setItem('memo', memoArea.html());
});

// ウィンドウの読み込み完了時にイベントを追加
$(window).on('load', function() {
  // ローカルストレージからメモを取得
  const savedMemo = localStorage.getItem('memo');
  // 保存されたメモがある場合
  if (savedMemo) {
    // メモエリアに保存されたメモを表示
    memoArea.html(savedMemo);
  }
});

// 削除ボタンを取得
const deleteButton = $('#delete-button');
// メモを削除するための入力エリアを選択
const deletememoArea = $('.input-area');

// 削除ボタンがクリックされたときの処理
deleteButton.on('click', function() {
  //input-area内のメモ内容があるかチェック
  if (deletememoArea.children().length > 0) {
      //ローカルストレージからメモ内容を削除する
      localStorage.removeItem('memo');
      //input-areaからメモ内容を削除する
      deletememoArea.html('');
  } else {
     //削除項目がない場合に削除できないエラーを表示する
     window.alert("削除する項目がありません。");
  }
});