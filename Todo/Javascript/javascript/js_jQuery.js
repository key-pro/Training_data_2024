$(document).ready(function() {
  // ページ読み込み時に保存されたリストを復元
  restoreLists();
  // 「追加」ボタンがクリックされた時の処理
  $('#add-button').click(function() {
    // add-textのIDから値を取得
    const inputText = $('#add-text').val();
    // 「追加」ボタンがクリックされた後、入力フィールドを空にする
    $('#add-text').val('');
    // 入力が空でない場合にのみリストを作成して保存
    if (inputText.trim().length > 0) {
      // DOTOリストに反映関数呼び出し
      createIncompleteList(inputText);
      // 値保持関数呼び出す
      saveLists();
    } else {
      // add-textのIDから値が未入力だった場合未入力エラーを表示する
      window.alert("未入力です。");
    }
  });

  // 未完了リストの削除ボタンと完了ボタンのイベントを委譲
  $('#incomplete-list').on('click', 'button.delete', function() {
    // 削除ボタンがクリックされた時の処理
    //該当の項目を削除クリックしたら削除する
    $(this).parent().remove();
    //保持関数から該当の項目削除
    saveLists();
  });

  $('#incomplete-list').on('click', 'button.complete', function() {
    // 完了ボタンがクリックされた時の処理
    moveToCompleteList($(this).parent());
  });

  // 完了リストの戻すボタンのイベントを委譲
  $('#complete-list').on('click', 'button', function() {
    // 戻すボタンがクリックされた時の処理
    moveToIncompleteList($(this).parent());
  });

  // 未完了リストに新しい項目を作成して追加する関数
  function createIncompleteList(text) {
    // 新しいリスト要素を作成し、"list-row"クラスを追加する
    const div = $('<div>').addClass('list-row');
    // 新しいリストアイテムを作成し、テキストを設定し、contenteditable属性をfalseに設定する
    const li = $('<li>').text(text).attr('contenteditable', 'false');
    // 編集ボタンを作成し、"edit"クラスを追加し、テキストを設定する
    const editButton = $('<button>').addClass('edit').text('編集');
    // 完了ボタンを作成し、"complete"クラスを追加し、テキストを設定する
    const completeButton = $('<button>').addClass('complete').text('完了');
    // 削除ボタンを作成し、"delete"クラスを追加し、テキストを設定する
    const deleteButton = $('<button>').addClass('delete').text('削除');
  
    // 編集ボタンがクリックされた時の処理
    editButton.on('click', function() {
      // もしリストアイテムが編集不可状態であれば
      if (li.attr('contenteditable') === 'false') {
        // リストアイテムを編集可能に設定し、フォーカスを当てる
        li.attr('contenteditable', 'true').focus();
        // 編集ボタンのテキストを「保存」に変更する
        editButton.text('保存');
      } else {
        // リストアイテムを編集不可に設定する
        li.attr('contenteditable', 'false');
        // 編集ボタンのテキストを「編集」に変更する
        editButton.text('編集');
        // リストの変更を保存する
        saveLists();
      }
    });
  
    // リスト要素に各要素を追加
    div.append(li);
    div.append(editButton);
    div.append(completeButton);
    div.append(deleteButton);
  
    // 未完了リストに追加
    $('#incomplete-list').append(div);
    // リストの変更を保存する
    saveLists();
  }
  
  // 未完了リストから完了リストへ移動する関数
  function moveToCompleteList(div) {
    // div要素の子要素（li要素）のテキストを取得する
    const text = div.children('li').text();
    // すべてのdiv要素を空にする
    div.empty();
    // 新しいli要素を作成し、テキストを設定し、contenteditable属性をfalseにする
    const newLi = $('<li>').text(text).attr('contenteditable', 'false');
    // 新しい戻るボタン要素を作成し、テキストを設定する
    const backButton = $('<button>').text('戻す');

    // 編集ボタンを追加しない
    // const editButton = $('<button>').addClass('edit').text('編集').on('click', function() {
    //   if (newLi.attr('contenteditable') === 'false') {
    //     newLi.attr('contenteditable', 'true').focus();
    //     editButton.text('保存');
    //   } else {
    //     newLi.attr('contenteditable', 'false');
    //     editButton.text('編集');
    //     saveLists();
    //   }
    // });

    // div要素に新しいli要素を追加する
    div.append(newLi);
    // div.append(editButton);
    div.append(backButton);
    // IDが"complete-list"の要素にdivを追加する
    $('#complete-list').append(div);
    // リストの変更を保存する
    saveLists();
  }
});