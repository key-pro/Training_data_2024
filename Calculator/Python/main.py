# GUIアプリケーションのライブラリをインポート
import tkinter as tk

#ボタン定義
BUTTON = [
    ['', 'B', 'C', '/'],
    ['7', '8', '9', '*'],
    ['4', '5', '6', '-'],
    ['1', '2', '3', '+'],
    ['00', '0', '.', '=']
]

#演算子定義
SYMBOL = ['+', '-', '*', '/']

#電卓画面実装及び計算機能実装
class CaluGui(object):
    def __init__(self, app=None):

        # 計算用の文字列
        self.calc_str = ''

        # ウインドウ設定

        # ウインドウのタイトル
        app.title('簡易的電卓')

        # ウインドウのサイズ
        app.geometry('450x500')

        # フレーム設定

        # 計算式と結果用のFrame
        calc_frame = tk.Frame(app, width=300, height=100)

        # サイズが固定
        calc_frame.propagate(False)

        # 余白の設定
        calc_frame.pack(side=tk.TOP, padx=0, pady=0)

        # 計算ボタン用のFrame
        button_frame = tk.Frame(app, width=300, height=500)

        # サイズが固定
        button_frame.propagate(False)

        # 余白の設定
        button_frame.pack(side=tk.BOTTOM, padx=0, pady=0)

        #パーツ設定

        # 計算式用の動的変数
        self.calc_var = tk.StringVar()

        # 結果用の動的変数
        self.ans_var = tk.StringVar()

        # 計算式用のLabel
        calc_label = tk.Label(calc_frame, textvariable=self.calc_var, font=("",30))

        # 結果用のLabel
        ans_label = tk.Label(calc_frame, textvariable=self.ans_var, font=("",30))

        # 右揃えで配置
        calc_label.pack(anchor=tk.E)

        # 右揃えで配置
        ans_label.pack(anchor=tk.E)

        # BUTTON の要素を順番に取り出し、その要素とそのインデックスを y, row にそれぞれ代入する
        for y, row in enumerate(BUTTON, 1):

            # row の要素を順番に取り出し、その要素とそのインデックスを x, num にそれぞれ代入する
            for x, num in enumerate(row):

                # TkinterのButtonを作成し、button_frameに配置する
                # text: ボタンに表示するテキスト
                # font: ボタンのフォント設定 (フォント名, フォントサイズ)
                # width: ボタンの幅 (文字数単位)
                # height: ボタンの高さ (行数単位)
                button = tk.Button(button_frame, text=num, font=('', 15), width=9, height=3)

                # 列や行を指定して配置
                button.grid(row=y, column=x)

                # Buttonが押された場合
                button.bind('<Button-1>', self.click_button)

    #ボタンをチェック関数定義
    def click_button(self, event):

        # 押したボタンのCheck
        check = event.widget['text']

        # イコールの場合
        if check == '=':

            # self.calc_str の最後の文字が SYMBOL リストに含まれる演算子かどうかをチェックする
            if self.calc_str[-1:] in SYMBOL:

                # 記号の場合、記号よりも前で計算
                self.calc_str = self.calc_str[:-1]

            # eval関数の利用
            res = '= ' + str(eval(self.calc_str))

            self.ans_var.set(res)

        # クリアの場合
        elif check == 'C':

            #クリア処理
            self.calc_str = ''
            self.ans_var.set('')

        # バックの場合
        elif check == 'B':
            self.calc_str = self.calc_str[:-1]

        # 記号の場合
        elif check in SYMBOL:

            # self.calc_str の最後の文字が SYMBOL リストに含まれない演算子であり、かつ空文字列でない場合
            if self.calc_str[-1:] not in SYMBOL and self.calc_str[-1:] != '':

                self.calc_str += check

            # self.calc_str の最後の文字が SYMBOL リストに含まれる演算子の場合
            elif self.calc_str[-1:] in SYMBOL:

                # 記号の場合、入れ替える
                self.calc_str = self.calc_str[:-1] + check
        else:
            # 数字などの場合
            self.calc_str += check

        # Tkinterの変数に計算結果を設定して表示する
        self.calc_var.set(self.calc_str)


#メイン関数から
def main():

    #ウインドウ設定
    app = tk.Tk()

    # ウィンドウのサイズを変更不可能にする
    app.resizable(width=False, height=False)

    #CaluGui関数にapp渡し呼び出す
    CaluGui(app)

    # ディスプレイ

    #ウインドウをループで回すことで Widgit に対応できるようになる
    app.mainloop()


if __name__ == '__main__':
    main()