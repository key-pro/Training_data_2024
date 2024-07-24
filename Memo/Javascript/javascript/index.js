// 追加ボタンを取得
const addButton = document.getElementById('add-button');
// 入力フィールドを取得
const inputField = document.getElementById('add-text');
// メモエリアを取得
const memoArea = document.querySelector('.input-area');

// 追加ボタンにクリックイベントを追加
addButton.addEventListener('click', function() {
  //textareaに入力された内容を取得しスペースを削除
  const memoText = inputField.value.trim();
  //スペースを削除後未入力かチェック
  if (memoText === '') {
    //未入力の場合は未入力エラーを表示する
    window.alert("メモ内容未入力です");
    return;
  }

  // メモ要素を作成
  const memoElement = document.createElement('div');
  // メモ要素のテキストコンテンツを設定
  memoElement.textContent = memoText;
  // メモエリアにメモ要素を追加
  memoArea.appendChild(memoElement);
  // 入力フィールドの値を空にする
  inputField.value = '';

  // メモ内容をローカルストレージに保存
  localStorage.setItem('memo', memoArea.innerHTML);
});

// ウィンドウの読み込み完了時にイベントを追加
window.addEventListener('load', function() {
  // ローカルストレージからメモを取得
  const savedMemo = localStorage.getItem('memo');
  // 保存されたメモがある場合
  if (savedMemo) {
    // メモエリアに保存されたメモを表示
    memoArea.innerHTML = savedMemo;
  }
});

// 削除ボタンを取得
const deleteButton = document.getElementById('delete-button');
// メモを削除するための入力エリアを選択
const deletememoArea = document.querySelector('.input-area');

// 削除ボタンがクリックされたときの処理
deleteButton.addEventListener('click', function() {
  //input-area内のメモ内容があるかチェック
  if (deletememoArea.children.length > 0) {
      //ローカルストレージからメモ内容を削除する
      localStorage.removeItem('memo');
      //input-areaからメモ内容を削除する
      deletememoArea.innerHTML = '';
  } else {
     //削除項目がない場合に削除できないエラーを表示する
     window.alert("削除する項目がありません。");
  }
});


