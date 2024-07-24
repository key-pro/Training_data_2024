#GUIのアプリケーションを作成するためのTkinterライブラリをインポート
import tkinter as tk
#ランダムな数字を生成するためのrandomライブラリをインポート
import random

#数宛てゲーム定義
class NumberGuessGame:

    #初期化関数
    def __init__(self, master):

        # マスターウィンドウを設定
        self.master = master

        #ウインドウのタイトル
        self.master.title("Number Guess Game")

        #ウインドウのサイズ
        self.master.geometry("300x150")

        # ウィンドウサイズを固定する
        self.master.resizable(False, False)

        #1~100からのランダム数字を生成する
        self.secret_number = random.randint(1, 100)

        # ゲームの試行回数を初期化
        self.num_guesses = 0

        # ゲームのルールを表示
        print("ゲームのルール: 1から100までの数字を当てるゲームです。")
        print("数字を入力して、ゲームを開始してください。")

        # ラベルを作成し、ウインドウに配置
        self.label = tk.Label(master, text="1から100までの数字を当てるゲームです。", font=("", 10))

        # ラベルをウインドウに配置
        self.label.pack(padx=5, pady=5)

        # エントリーを作成し、ウインドウに配置
        self.entry = tk.Entry(master)

        # テキスト入力フィールドをウィンドウに配置する
        self.entry.pack()

        # "Guess"ボタンを作成し、押されたときにself.check_guess()関数を呼び出す
        self.button = tk.Button(master, text="Guess", width=16, command=self.check_guess)

        # ボタンをウィンドウに配置する
        self.button.pack(pady=(5,5))

        # 結果を表示するためのラベルを作成する
        self.result_label = tk.Label(master, text="")

        # "Play Again"ボタンを作成し、押されたときにself.restart_game()関数を呼び出す。初めは非アクティブ状態
        self.restart_button = tk.Button(master, text="Play Again", width=16, command=self.restart_game, state=tk.DISABLED)

        # "Play Again"ボタンをウィンドウに配置する
        self.restart_button.pack()

        # 結果ラベルをウィンドウに配置する
        self.result_label.pack()

    # ユーザーの推測を検証するための関数
    def check_guess(self):
        try:
            # テキスト入力フィールドからユーザーの推測を取得し、整数に変換する
            guess = int(self.entry.get())

            # 推測の回数を1増やす
            self.num_guesses += 1

        except ValueError:

            # 数字以外が入力された場合の処理
            self.result_label.config(text="Please enter a valid number.", font=("",12))
            return

        # 推測が秘密の数字と一致するかどうかを確認する
        if guess == self.secret_number:

            # 結果ラベルのテキストを更新して、おめでとうメッセージと推測の回数を表示する
            self.result_label.config(text=f"Congratulations! You guessed the number in {self.num_guesses} guesses.", font=("",9))

            # "Play Again"ボタンをアクティブにする
            self.restart_button.config(state=tk.NORMAL)

            # "Guess"ボタンを非アクティブにする
            self.button.config(state=tk.DISABLED)

        # 推測が秘密の数字よりも小さい場合
        elif guess < self.secret_number:

            # "Too low! Try again."というテキストを結果ラベルに設定
            self.result_label.config(text="Too low! Try again.", font=("",12))
        else:

            # 推測が秘密の数字よりも大きい場合、結果ラベルに"Too high! Try again."と表示する
            self.result_label.config(text="Too high! Try again.", font=("",12))

    # ゲームをリスタートするための関数
    def restart_game(self):

        # 1から100までのランダムな数字を生成して、秘密の数字として設定する
        self.secret_number = random.randint(1, 100)

        # ユーザーの推測回数を初期化する
        self.num_guesses = 0

        # 結果ラベルのテキストを空にする
        self.result_label.config(text="")

        # テキスト入力フィールドの内容を削除する
        self.entry.delete(0, tk.END)

        # "Play Again"ボタンを非アクティブにする
        self.restart_button.config(state=tk.DISABLED)

        # "Guess"ボタンをアクティブにする
        self.button.config(state=tk.NORMAL)

def main():
    # Tkinterのルートウィンドウを作成する
    root = tk.Tk()

    # NumberGuessGameクラスのインスタンスを作成し、ルートウィンドウを渡す
    game = NumberGuessGame(root)

    # Tkinterのmainループを開始して、ウィンドウを表示する
    root.mainloop()

if __name__ == "__main__":
    main()
