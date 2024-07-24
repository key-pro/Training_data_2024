import os

#メモ内容をmemo.txt書き込む
def write_memo(memo):

    #memo.txtを追記モード開く
    with open('memo.txt', 'a') as file:

        #memo.txtに追記する
        file.write(memo + '\n')

#memo.txtからメモ内容を読み込み
def read_memo():

    #ファイルの存在確認する
    if os.path.exists('memo.txt'):
        #memo.txtを開く
        with open('memo.txt', 'r') as file:
            #memo.txtを読み込む
            memos = file.readlines()

            #メモ内容を表示する
            for memo in memos:
                print("\nメモ内容:", memo)
    else:
        #ファイルが見つからない場合はエラー表示する
        print("\nファイルが見つかりません。\n")

#memo.txtからメモ内容を削除
def delete_memo():
    #ファイルの存在確認する
    if os.path.exists('memo.txt'):
        #memo.txtを開いて、中身を削除する
        with open('memo.txt', 'w') as file:
            #削除する
            file.write('')
    else:
        #ファイルが見つからない場合はエラー表示する
        print("\nファイルが見つかりません。")

#メモを登録、表示、削除
while True:

    #メモ内容を登録、表示、削除のメニューの内容定義
    choice = input("メモを追加する場合は '1' を入力してください。\nメモを表示する場合は '2' を入力してください。\nメモを削除する場合は '3' を入力してください。\n終了する場合は 'q' を入力してください: ")

    #メモを追加選択処理
    if choice == '1':

        #メモ内容を入力受付
        new_memo = input("\n新しいメモを入力してください: ")

        #メモ内容を書き込み関数に渡す
        write_memo(new_memo)

    #メモ内容を表示選択処理
    elif choice == '2':

        #メモ内容を表示関数呼び出し
        read_memo()

    #メモ内容を削除選択処理
    elif choice == '3':

        #メモ内容内容を削除関数呼び出し
        delete_memo()

        #メモ内容の削除結果を表示する
        print("\nメモを削除しました。")

    #終了処理の選択処理
    elif choice == 'q':

        #処理を処理を終了する
        break

    #1~4以外の入力された場合は無効な入力されましたエラー対応
    else:

        #無効な値が入力された時にエラー表示す
        print("\n無効な選択です。\n")