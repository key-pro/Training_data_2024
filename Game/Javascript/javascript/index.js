// Import the alert function from the window object
import { alert } from 'window';

// stageのIDを持つ要素を取得して、変数 stage に代入する
let stage = document.getElementById("stage");
// "square-template" というIDを持つ要素を取得して、変数 squareTemplate に代入する
let squareTemplate = document.getElementById("square-template");
// 空の配列 stoneStateList を定義する
let stoneStateList = [];
// 変数 currentColor を1で初期化する
let currentColor = 1;
// "current-turn" というIDを持つ要素を取得して、変数 currentTurnText に代入する
let currentTurnText = document.getElementById("current-turn");
// "pass" というIDを持つ要素を取得して、変数 passButton に代入する
let passButton = document.getElementById("pass");

// changeTurn 関数を定義する
const changeTurn = () => {
  // currentColor を 3 から currentColor を引いた値に設定する
  currentColor = 3 - currentColor;

  // もし currentColor が 1 と等しい場合
  if (currentColor === 1) {
    // currentTurnText のテキストコンテンツを "黒" に設定する
    currentTurnText.textContent = "黒";
  } else {
    // currentTurnText のテキストコンテンツを "白" に設定する
    currentTurnText.textContent = "白";
  }
}

// getReversibleStones 関数を定義し、引数 idx を受け取る
const getReversibleStones = (idx) => {
  //クリックしたマスから見て、各方向にマスがいくつあるかをあらかじめ計算する
  const squareNums = [
    7 - (idx % 8),
    Math.min(7 - (idx % 8), (56 + (idx % 8) - idx) / 8),
    (56 + (idx % 8) - idx) / 8,
    Math.min(idx % 8, (56 + (idx % 8) - idx) / 8),
    idx % 8,
    Math.min(idx % 8, (idx - (idx % 8)) / 8),
    (idx - (idx % 8)) / 8,
    Math.min(7 - (idx % 8), (idx - (idx % 8)) / 8),
  ];
  //for文ループの規則を定めるためのパラメータ定義
  const parameters = [1, 9, 8, 7, -1, -9, -8, -7];

  //ひっくり返せることが確定した石の情報を入れる配列
  let results = [];

  //8方向への走査のためのfor文
  for (let i = 0; i < 8; i++) {
    //ひっくり返せる可能性のある石の情報を入れる配列
    const box = [];
    //現在調べている方向にいくつマスがあるか
    const squareNum = squareNums[i];
    const param = parameters[i];
    //ひとつ隣の石の状態
    const nextStoneState = stoneStateList[idx + param];

    //フロー図の[2][3]：隣に石があるか 及び 隣の石が相手の色か -> どちらでもない場合は次のループへ
    if (nextStoneState === 0 || nextStoneState === currentColor) continue;
    //隣の石の番号を仮ボックスに格納
    box.push(idx + param);

    //フロー図[4][5]のループを実装
    for (let j = 0; j < squareNum - 1; j++) {
      const targetIdx = idx + param * 2 + param * j;
      const targetColor = stoneStateList[targetIdx];
      //フロー図の[4]：さらに隣に石があるか -> なければ次のループへ
      if (targetColor === 0) continue;
      //フロー図の[5]：さらに隣にある石が相手の色か
      if (targetColor === currentColor) {
        //自分の色なら仮ボックスの石がひっくり返せることが確定
        results = results.concat(box);
        break;
      } else {
        //相手の色なら仮ボックスにその石の番号を格納
        box.push(targetIdx);
      }
    }
  }
  //ひっくり返せると確定した石の番号を戻り値にする
  return results;
};

// ゲーム内でのターン数をカウントする変数を追加
let turnCount = 4;

// onClickSquare 関数を定義し、引数 index を受け取る
const onClickSquare = (index) => {
  //ひっくり返せる石の数を取得
  const reversibleStones = getReversibleStones(index);

  //他の石があるか、置いたときにひっくり返せる石がない場合は置けないメッセージを出す
  if (stoneStateList[index] !== 0 || !reversibleStones.length) {
    // 置けない場合はアラートを表示
    alert("ここには置けないよ！");
    return;
  }

  //自分の石を置く
  stoneStateList[index] = currentColor;
  document.querySelector(`[data-index='${index}']`).setAttribute("data-state", currentColor);

  //相手の石をひっくり返す = stoneStateListおよびHTML要素の状態を現在のターンの色に変更する
  reversibleStones.forEach((key) => {
    stoneStateList[key] = currentColor;
    document.querySelector(`[data-index='${key}']`).setAttribute("data-state", currentColor);
  });

  // ターン数をインクリメント
  turnCount++;

  //もし白または黒の石が0個になった場合は勝利判定を行う
  if (stoneStateList.filter(state => state === 1).length === 0 || stoneStateList.filter(state => state === 2).length === 0) {
    // 勝者を表すテキスト
    const blackStonesNum = stoneStateList.filter(state => state === 1).length;
    const whiteStonesNum = stoneStateList.filter(state => state === 2).length;
    let winnerText = "";
    if (blackStonesNum === 0) {
      winnerText = "白の勝ちです！";
    } else if (whiteStonesNum === 0) {
      winnerText = "黒の勝ちです！";
    }
    // .judge クラスを持つ要素を取得
    const judgeElement = document.querySelector(".judge");
    // 勝者を表すテキスト
    alert(`ゲーム終了です。白${whiteStonesNum}、黒${blackStonesNum}で、${winnerText}`);
    // judgeElement のテキスト内容を設定
    judgeElement.textContent = `ゲーム終了です。白${whiteStonesNum}、黒${blackStonesNum}で、${winnerText}`;
    // "endless" というIDを持つボタン要素を取得
    const endlessButton = document.getElementById("endless");
    // endlessボタンのdisabled属性を解除
    endlessButton.removeAttribute("disabled");
  }

  // ゲームの終了条件をチェック（64回ターンが経過したかどうか）
  if (turnCount == 64) {
    // 黒石の数を数える
    const blackStonesNum = stoneStateList.filter(state => state === 1).length;
    // 白石の数を数える
    const whiteStonesNum = stoneStateList.filter(state => state === 2).length;
    // 結果を表示する要素を取得する
    const judgeElement = document.querySelector(".judge");
    // 勝者を表すテキスト
    let winnerText = "";
    // 黒石の数と白石の数を比較して勝者を決定する
    if (blackStonesNum > whiteStonesNum) {
      // 黒石の数が多い場合、黒の勝ち
      winnerText = "黒の勝ちです！";
    // 白石の数が多い場合、白の勝ち
    } else if (blackStonesNum < whiteStonesNum) {
      // 白石の数が多い場合、白の勝ち
      winnerText = "白の勝ちです！";
      // どちらでもない場合、引き分け
    } else {
      // どちらでもない場合、引き分け
      winnerText = "引き分けです";
    }

    // ゲーム終了を知らせるアラートを表示し、結果を表示する
    alert(`ゲーム終了です。白${whiteStonesNum}、黒${blackStonesNum}で、${winnerText}`);
    // 結果を表示する要素のテキストを更新する
    judgeElement.textContent = `ゲーム終了です。白${whiteStonesNum}、黒${blackStonesNum}で、${winnerText}`; 
    // ボタンの無効化を解除する
    document.getElementById("endless").removeAttribute("disabled");
    // ターンカウントを4に設定する（任意の値）
    turnCount = 4;
  }

  //ゲーム続行なら相手のターンにする
  changeTurn();
}

// ゲーム盤のマスを生成する関数を定義する
const createSquares = () => {
  // ゲーム盤のマス数分（64マス）繰り返しを行うループを開始する
  for (let i = 0; i < 64; i++) {
    // テンプレートとして定義されたマスの要素を複製する
    const square = squareTemplate.cloneNode(true);
    // 各マスのHTML要素から "id" 属性を削除する
    square.removeAttribute("id");
    // ステージ要素にマスを追加する
    stage.appendChild(square);

    // 各マス内の石を表す要素を取得する
    const stone = square.querySelector('.stone');

    // マスの初期状態を保持する変数を宣言する
    let defaultState;
    // マスのインデックスが27または36の場合、初期状態を黒石(1)に設定する
    if (i == 27 || i == 36) {
      defaultState = 1;
    }
    // マスのインデックスが28または35の場合、初期状態を白石(2)に設定する
    else if (i == 28 || i == 35) {
      defaultState = 2;
    }
    // それ以外の場合、初期状態を何も置かれていない状態(0)に設定する
    else {
      defaultState = 0;
    }

    // マスに対して、デフォルトの石の状態を設定する
    stone.setAttribute("data-state", defaultState);
    //インデックス番号をHTML要素に保持させる
    stone.setAttribute("data-index", i);
    //初期値を配列に格納
    stoneStateList.push(defaultState);

    // マスがクリックされたときに onClickSquare 関数を呼び出し
    square.addEventListener('click', () => {
      //そのマスのインデックスを渡す
      onClickSquare(i);
    });
  }
}

// endlessボタンをクリックしたときにcreateSquaresを呼び出す
document.getElementById("endless").addEventListener("click", () => {
  // 再描画する
  // 盤面をリセットするため、ステージ要素の内部の HTML を空にする
  stage.innerHTML = "";
  // 石の状態を空の配列でリセットする
  stoneStateList = [];
  // ターンを黒にリセットする
  currentColor = 1;
  // ターン表示を黒にリセットする
  currentTurnText.textContent = "黒";
  // マスを生成してゲーム盤を初期化する
  createSquares();

  // endlessボタンを再度disabledにする
  document.getElementById("endless").setAttribute("disabled", "disabled");
  // .judge クラスを持つ要素を取得
  const judgeElement = document.querySelector(".judge");
  // judgeElement のテキスト内容を削除
  judgeElement.textContent = "";
});

// ページが読み込まれたときに実行される処理を定義する
window.onload = () => {
   // マスを生成する関数を呼び出してゲーム盤を初期化する
  createSquares();
  // パスボタンがクリックされたときに、ターンを変更する処理を割り当てる
  passButton.addEventListener("click", changeTurn)
}