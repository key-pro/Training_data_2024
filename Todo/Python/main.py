import os

#Todoリストを読み取り専用で開く関数
def read_todos(filename):
    if os.path.exists('todos.txt'):
        #todos.txtを読み取り専用で開く
        with open(filename, 'r') as file:

            #ファイルの内容を取得する
            todos = file.readlines()

        #ファイルの内容を呼び出し元に返す
        return [todo.strip() for todo in todos]

    #todos.txtが見つからないエラー
    else:
        print("ファイルが見つかりません。\n")

#書き込む関数定義
def write_todos(filename, todos):

    #todos.txtを書き込み（上書き）モードで開く
    with open(filename, 'w') as file:

        #Todoのタスク分繰り返す
        for todo in todos:

            #todos.txtに書き込む
            file.write(todo + '\n')

#Todoリストを変更する関数定義
def edit_todo(filename, index, new_todo):

    #ファイルを読み込む
    todos = read_todos(filename)

    #変更したい数値が範囲内かチェックする
    if 1 <= index <= len(todos):

        #インデックスのズレを調整する
        todos[index - 1] = new_todo

        #変更内容を書き込み関数に渡す
        write_todos(filename, todos)
    else:

        #有効な数字を入力されていない場合はエラー表示するs
        print("\n無効なインデックスです。")

#todos.txtからメモ内容を読み込み
def display_todos():
    if os.path.exists('todos.txt'):
        #todos.txtを読み取り専用で開く
        with open('todos.txt', 'r') as file:

            #todos.txtを読み込む
            memos = file.readlines()

            #todos.txtを繰り返し行う
            for i, memo in enumerate(memos, 1):

                #todos.txtをメモ内容を表示する
                print(f"\ns{i}. Todo内容:", memo)

    #ファイルが見つからない場合
    else:
        #ファイルが見つからない場合はエラー表示する
        print("\nファイルが見つかりません。")


#Todoリスト追加関数定義
def add_todo(filename, todo):
    if os.path.exists('todos.txt'):

        #todo.txt読み込む
        todos = read_todos(filename)

        #読み込んだ内容の最後に追加する
        todos.append(todo)

        #追加した内容を書き込み関数に渡す
        write_todos(filename, todos)

    else:

        #todo.txtが見つからない場合は新規作成する
        with open('todos.txt', 'w'):
            pass

        #todos.txt読み込む
        todos = read_todos(filename)

        #読み込んだ内容の最後に追加する
        todos.append(todo)

        #追加した内容を書き込み関数に渡す
        write_todos(filename, todos)

def remove_todo(filename, index):

    #todo.txtのチェック
    if os.path.exists('todos.txt'):

        #todo.txtを読み込む
        todos = read_todos(filename)

        #有効な数字を入力されているかチェックする
        if 1 <= index <= len(todos):

            #インデックスのズレを調整する
            del todos[index - 1]

            #書き込む関書き込む
            write_todos(filename, todos)

        else:

            #有効な数字を入力されていない場合はエラー表示する
            print("\n無効なインデックスです。")
    else:

        #ファイルが見つからない場合はエラー表示する
        print("\nファイルが見つかりません。")

def main():

    #todos.txtを指定
    filename = 'todos.txt'

    #read_todos関数にファイル名渡す
    todos = read_todos(filename)

    #終了処理するまで無限ループ
    while True:

        #Todoリススのメニューを表示
        print("\n1. Todoリストを表示\n2. Todoを追加\n3. Todoを削除\n4. Todoを編集\n5. 終了")

        #ユーザーからメニューの内容を受け取る
        choice = input("選択してください: ")

        #Todoリスト表示選択処理
        if choice == '1':

            #Todoリスト表示関数呼び出し
            display_todos()

        #Todoリストに追加選択処理
        elif choice == '2':

            #Todoのタスク入力受付
            todo = input("追加するタスクを入力してください: ")

            #Todoリストに追加関数に渡す
            add_todo(filename, todo)

        #Todoリストに削除選択処理
        elif choice == '3':

            #Todoリストの内容表示
            display_todos()

            #Todoリストの削除対象選択受付
            while True:

                try:
                    #Todoリストの削除対象選択受付
                    index = int(input("削除するタスクのインデックスを入力してください: "))

                    #数字を入力した場合は無限ループを抜ける
                    break

                #数字以外を入力した場合はエラー処理する
                except ValueError:

                    #数字を入力してくださいエラー表示する
                    print("\n数字を入力してください\n")

                    #処理を継続する
                    continue

            #Todoリストの対象のタスク削除
            remove_todo(filename, index)

        #Todoリストの編集機能選択
        elif choice == '4':

            #Todoリストの内容表示
            display_todos()

            #Todoリストの削除対象選択受付
            while True:
                try:

                    #Todoリスト編集したいタスク選択受付
                    index = int(input("編集するタスクのインデックスを入力してください: "))

                    #数字を入力した場合は無限ループを抜ける
                    break
                #数字以外を入力した場合はエラー処理する
                except ValueError:

                    #数字を入力してくださいエラー表示する
                    print("\n数字を入力してください\n")

                    #処理を継続する
                    continue

            #Todoリスト編集したい内容入力受付
            new_todo = input("新しいタスクを入力してください: ")
            #編集内容を確定する
            edit_todo(filename, index, new_todo)

        #Todoリスト終了処理選択
        elif choice == '5':

            #終了メッセージ表示
            print("\n終了します...")

            #処理を終了する
            break

        #1~5以外の入力した場合はエラー処理する
        else:

            #1~5以外の入力した場合はエラー表示する
            print("\n無効な選択です。もう一度選択してください。")

if __name__ == "__main__":
    main()