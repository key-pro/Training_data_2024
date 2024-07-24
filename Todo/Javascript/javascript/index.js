const onClickAdd = () => {
  // "add-text"というIDを持つinput要素から値を取得する
  const inputText = document.getElementById("add-text").value;
  // "add-text"というIDを持つinput要素の値を空に設定する
  document.getElementById("add-text").value = "";
  // inputText変数の値の前後の空白を削除し、長さが0より大きい場合に条件を満たすかどうかをチェックする
  if (inputText.trim().length > 0) {
    // inputTextの値を使用して未完了リストを作成する関数createIncompleteListを呼び出す
    createIncompleteList(inputText);
    // リストを保存
    saveLists();
  } else {
    // 未入力の場合、ブラウザのウィンドウ上にアラートメッセージ「未入力です。」を表示する
    window.alert("未入力です。");
  }
};

// 未完了リストから要素を削除する関数
const deleteFromIncompleteList = (target) => {
  // "incomplete-list"というIDを持つ要素から特定の要素（target）を削除する
  document.getElementById("incomplete-list").removeChild(target);
  // リストを保存
  saveLists();
};

// 未完了リストから完了リストへ要素を移動する関数
const moveToCompleteList = (target) => {
  // IDが"complete-list"である要素を取得する
  const completeList = document.getElementById("complete-list");
  // 特定の要素（target）内のli要素からテキストを取得する
  const itemText = target.querySelector("li").innerText;

  // 既存のdivを削除
  target.remove();

  // 新しいdivを作成
  const div = document.createElement("div");
  // 新しいdiv要素に"list-row"というクラス名を設定する
  div.className = "list-row";
  // 新しいli要素を作成する
  const li = document.createElement("li");
  // li要素のテキストコンテンツをitemText変数の値に設定する
  li.innerText = itemText;
  // 新しいbutton要素を作成する
  const backButton = document.createElement("button");
  // button要素のテキストコンテンツを"戻す"に設定する
  backButton.innerText = "戻す";
  backButton.className = "btn btn-secondary";
  // 戻るボタンがクリックされた時に実行されるイベントリスナーを設定する
  backButton.addEventListener("click", () => {
    // completeListからdiv要素を削除する
    completeList.removeChild(div);
    // itemTextを使用して未完了リストを作成する関数createIncompleteListを呼び出す
    createIncompleteList(itemText);
    // リストの状態を保存する関数saveListsを呼び出す
    saveLists();
  });
  // div要素にli要素を子要素として追加する
  div.appendChild(li);
  // div要素にbackButton要素を子要素として追加する
  div.appendChild(backButton);
  // completeListにdiv要素を子要素として追加する
  completeList.appendChild(div);
};

// 指定されたテキストを使用して未完了リストを作成する関数
const createIncompleteList = (text) => {
  // 新しいdiv要素を作成する
  const div = document.createElement("div");
  // 新しいdiv要素に"list-row"というクラス名を設定する
  div.className = "list-row";
  // 新しいdiv要素に"list-row"というクラス名を設定する
  const li = document.createElement("li");
  // li要素のテキストコンテンツを変数textの値に設定する
  li.innerText = text;
  // li要素の編集可能性を無効にする
  li.contentEditable = "false";
  // 新しいbutton要素を作成する
  const editButton = document.createElement("button");
  editButton.className = "btn btn-success";
  // editButton要素のテキストコンテンツを"編集"に設定する
  editButton.innerText = "編集";
  // 編集ボタンがクリックされた時に実行されるイベントリスナーを設定する
  editButton.addEventListener("click", () => {
    // li要素のcontentEditable属性が"false"であるかどうかをチェックする
    if (li.contentEditable === "false") {
      // li要素のcontentEditable属性を"true"に設定
      li.contentEditable = "true";
      // li要素にフォーカスを設定する
      li.focus();
      // editButton要素のテキストコンテンツを"保存"に設定する
      editButton.innerText = "保存";
      editButton.className = "btn btn-success";
    } else {
      // li要素のcontentEditable属性を"false"に設定する
      li.contentEditable = "false";
      // editButton要素のテキストコンテンツを"編集"に設定する
      editButton.innerText = "編集";
      // リストの状態を保存する関数saveListsを呼び出す
      saveLists();
    }
  });

  // 新しいbutton要素を作成する
  const completeButton = document.createElement("button");
  // completeButton要素のテキストコンテンツを"完了"に設定する
  completeButton.innerText = "完了";
  completeButton.className = "btn btn-info";
  // 完了ボタンがクリックされた時に実行されるイベントリスナーを設定する
  completeButton.addEventListener("click", () => {
    // 完了ボタンの親要素を削除する関数deleteFromIncompleteListを呼び出す
    deleteFromIncompleteList(completeButton.parentNode);
    // 完了ボタンの親要素を完了リストに移動する関数moveToCompleteListを呼び出す
    moveToCompleteList(completeButton.parentNode);
    // リストの状態を保存する関数saveListsを呼び出す
    saveLists();
  });

  // 新しいbutton要素を作成する
  const deleteButton = document.createElement("button");
  deleteButton.innerText = "削除";
  deleteButton.className = "btn btn-danger";
  deleteButton.addEventListener("click", () => {
    deleteFromIncompleteList(deleteButton.parentNode);
    // リストの状態を保存する関数saveListsを呼び出す
    saveLists();
  });

  // リスト要素に各要素を追加
  div.appendChild(li);
  div.appendChild(editButton);
  div.appendChild(completeButton);
  div.appendChild(deleteButton);
  // 未完了リストに追加
  document.getElementById("incomplete-list").appendChild(div);
};

// リストの状態を保存する関数
const saveLists = () => {
  // 未完了アイテムの状態を保存するための空の配列
  const incompleteItems = [];
  // IDが"incomplete-list"であり、クラスが"list-row"である要素をすべて取得し、それぞれに対して処理を行うループを設定する
  document.querySelectorAll("#incomplete-list .list-row").forEach(row => {
    // 未完了リスト内の各行からli要素のテキストを取得し、そのテキストをincompleteItems配列に追加する
    incompleteItems.push(row.querySelector("li").innerText);
  });
  // incompleteItems配列をJSON形式の文字列に変換し、ローカルストレージに"incompleteItems"というキーで保存する
  localStorage.setItem("incompleteItems", JSON.stringify(incompleteItems));

  // 完了アイテムの状態を保存するための空の配列
  const completeItems = [];
  // IDが"complete-list"であり、クラスが"list-row"である要素をすべて取得し、それぞれに対して処理を行うループを設定する
  document.querySelectorAll("#complete-list .list-row").forEach(row => {
    // 各行のli要素からテキストを取得し、そのテキストをcompleteItems配列に追加する
    completeItems.push(row.querySelector("li").innerText);
  });
  // completeItems配列をJSON形式の文字列に変換し、ローカルストレージに"completeItems"というキーで保存する
  localStorage.setItem("completeItems", JSON.stringify(completeItems));
};

// 保存されたリストの状態を復元するための関数
const restoreLists = () => {
  // ローカルストレージから"incompleteItems"というキーで保存されたJSON形式の文字列を取得し、JavaScriptオブジェクトに変換する
  const incompleteItems = JSON.parse(localStorage.getItem("incompleteItems"));
  // incompleteItemsがnullまたはundefinedでない場合に条件を満たすかどうかをチェックする
  if (incompleteItems) {
    // incompleteItems配列内の各要素に対して処理を行うループを設定する
    incompleteItems.forEach(item => {
      // incompleteItems配列内の各要素を使用して未完了リストを作成する関数createIncompleteListを呼び出す
      createIncompleteList(item);
    });
  }

  // ローカルストレージから"completeItems"というキーで保存されたJSON形式の文字列を取得し、JavaScriptオブジェクトに変換する
  const completeItems = JSON.parse(localStorage.getItem("completeItems"));
  // completeItemsがnullまたはundefinedでない場合に条件を満たすかどうかをチェックする
  if (completeItems) {
    // completeItems配列内の各要素に対して処理を行うループを設定する
    completeItems.forEach(item => {
      // completeItems配列内の各要素を使用して完了リストを作成する関数createCompleteListを呼び出す
      createCompleteList(item);
    });
  }
};

// 指定されたテキストを使用して完了リストを作成する関数
const createCompleteList = (text) => {
  // IDが"complete-list"である要素を取得する
  const completeList = document.getElementById("complete-list");
  // 新しいdiv要素を作成する
  const div = document.createElement("div");
  // 新しいdiv要素に"list-row"というクラス名を設定する
  div.className = "list-row";

  // 新しいli要素を作成する
  const li = document.createElement("li");
  // li要素のテキストコンテンツを変数textの値に設定する
  li.innerText = text;

  // 新しいbutton要素を作成する
  const backButton = document.createElement("button");
  // backButton要素のテキストコンテンツを"戻す"に設定する
  backButton.innerText = "戻す";
  backButton.className = "btn btn-secondary";
  // 戻るボタンがクリックされた時に実行されるイベントリスナーを設定する
  backButton.addEventListener("click", () => {
    // completeListからdiv要素を削除する
    completeList.removeChild(div);
    // 引数として与えられたtextを使用して未完了リストを作成する関数createIncompleteListを呼び出す
    createIncompleteList(text);
    // リストの状態を保存する関数saveListsを呼び出す
    saveLists();
  });

  // リスト要素に各要素を追加
  div.appendChild(li);
  div.appendChild(backButton);
  // completeListにdiv要素を子要素として追加する
  completeList.appendChild(div);
};

// DOMの読み込みが完了した時点で実行されるイベントリスナーを設定する
document.addEventListener("DOMContentLoaded", () => {
  // 保存されたリストの状態を復元するための関数restoreListsを呼び出す
  restoreLists();
  // "add-button"というIDを持つ要素にクリックイベントリスナーを追加する
  document.getElementById("add-button").addEventListener("click", onClickAdd);
});