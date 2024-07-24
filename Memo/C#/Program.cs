using System;
using System.IO;
class Memo
{
    //メモ内容をmemo.txtに書き込む
    static void WriteMemo(string memo)
    {
        //memo.txtを開く
        using (StreamWriter file = File.AppendText("memo.txt"))
        {
            //memo.txtに書き込む
            file.WriteLine(memo);
        }
    }

    //memo.txtからメモ内容を読み込む
    static void ReadMemo()
    {
        //ファイルの存在を確認する
        if (File.Exists("memo.txt"))
        {
            //memo.txtを開く
            using (StreamReader file = new StreamReader("memo.txt"))
            {
                //line変数定義
                string line;

                //メモ内容文繰り返す
                while ((line = file.ReadLine()) != null)
                {

                    //メモ内容を表示する
                    Console.WriteLine("\nメモ内容: " + line + "\n");
                }
            }
        }
        else
        {
            //ファイルが見つからない場合はエラーを表示する
            Console.WriteLine("\nファイルが見つかりません。");
        }
    }

    //memo.txtからメモ内容を削除
    static void DeleteMemo()
    {
        //ファイルの存在を確認する
        if (File.Exists("memo.txt"))
        {

            //memo.txtを開いて中身を削除する
            File.WriteAllText("memo.txt", string.Empty);
        }
        else
        {

            //ファイルが見つからない場合はエラーを表示する
            Console.WriteLine("\nファイルが見つかりません。");
        }
    }

    //メモを登録、表示、削除
    static void Main()
    {
        //終了条件になるまで繰り返す
        while (true)
        {

            //メモアプリの受け受け
            Console.Write("メモを追加する場合は '1' を入力してください。\nメモを表示する場合は '2' を入力してください。\nメモを削除する場合は '3' を入力してください。\n終了する場合は 'q' を入力してください: ");
            
            //メニューの入力を受け付ける
            string choice = Console.ReadLine();

            //メモを追加選択処理
            if (choice == "1")
            {
                //メモ内容を入力受付
                Console.Write("\n新しいメモを入力してください: ");
                string newMemo = Console.ReadLine();

                //メモ内容を書き込み関数に渡す
                WriteMemo(newMemo);
            }

            //メモ内容を表示選択処理
            else if (choice == "2")
            {

                //メモ内容を表示関数呼び出し
                ReadMemo();

            }

            //メモ内容を削除選択処理
            else if (choice == "3")
            {

                //メモ内容を削除関数呼び出し
                DeleteMemo();

                //メモ内容の削除結果を表示する
                Console.WriteLine("\nメモを削除しました。");

            }
            
            //終了処理の選択処理
            else if (choice == "q")
            {

                //処理を終了する
                break;

            }

            //1~4以外の入力された場合は無効な入力されましたエラー対応
            else
            {

                //無効な値が入力された時にエラーを表示する
                Console.WriteLine("\n無効な選択です。");

            }
        }
    }
}