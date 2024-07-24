using System;
using System.IO;

namespace Todo
{
    internal class Program
    {
        //todos.txt読み込み関数
        static string[] ReadTodos(string filename)
        {
            //todos.txtのファイル存在チェック
            if (File.Exists("todos.txt"))
            {
                //todos.txtのファイル読み込み   
                string[] todos = File.ReadAllLines(filename);

                //読み込み結果返す
                return todos;
            }
            else
            {
                //ファイルが見つからないエラー表示
                Console.WriteLine("\nファイルが見つかりません。\n");
                return new string[0];
            }
        }

        //todos.txtに新たにTodoを追加する
        static void WriteTodos(string filename, string[] todos)
        {
            //todos.txtファイル書き込み準備
            using (StreamWriter file = new StreamWriter(filename))
            {
                //Todo配列の要素に対して繰り返す
                foreach (string todo in todos)
                {
                    //todos.txtに書き込む
                    file.WriteLine(todo);
                }
            }
        }

        //todos.txtの編集機能定義
        static void EditTodo(string filename, int index, string newTodo)
        {
            //todos.txtのファイル読み込み
            string[] todos = ReadTodos(filename);

            //指定された番号が有効な数字かチェック
            if (1 <= index && index <= todos.Length)
            {
                //インデックスのズレを調整する
                todos[index - 1] = newTodo;
                //対象のTodoの変更内容を書き込み関数に渡す
                WriteTodos(filename, todos);
            }
            else
            {
                //有効な数字を入力されていない場合はエラー表示する
                Console.WriteLine("\n無効なインデックスです。\n");
            }
        }

        //Todoリストの内容を表示する
        static void DisplayTodos()
        {
            //todos.txtのファイル存在チェック
            if (File.Exists("todos.txt"))
            {
                //todos.txtの読み込む
                string[] memos = File.ReadAllLines("todos.txt");

                //Todoの数文処理を繰り返す
                for (int i = 0; i < memos.Length; i++)
                {
                    //Todo内容を表示する
                    Console.WriteLine("\n" + $"{i + 1}. Todo内容: {memos[i]}");
                }
            }
            else
            {
                //ファイルが見つからないエラー表示
                Console.WriteLine("\nファイルが見つかりません。\n");
            }
        }

        //Todoリスト追加関数
        static void AddTodo(string filename, string todo)
        {
            //todos.txtの存在チェック
            if (File.Exists("todos.txt"))
            {
                //todos.txtを読み込む
                string[] todos = ReadTodos(filename);

                //todos配列のサイズを１増やす
                Array.Resize(ref todos, todos.Length + 1);

                //配列の最後に追加したいTodoを追加
                todos[todos.Length - 1] = todo;

                //書き込む関数に渡す
                WriteTodos(filename, todos);
            }
            else
            {
                //該当のファイルを新規作成
                File.Create("todos.txt").Close();

                //ファイルの読み込む
                string[] todos = ReadTodos(filename);

                //todos配列のサイズを１増やす
                Array.Resize(ref todos, todos.Length + 1);

                //配列の最後に追加したいTodoを追加
                todos[todos.Length - 1] = todo;

                //書き込む関数に渡す
                WriteTodos(filename, todos);
            }
        }

        //Todoリスト削除関数
        static void RemoveTodo(string filename, int index)
        {
            ///todos.txtの存在チェック
            if (File.Exists("todos.txt"))
            {
                //todos.txtを読み込む
                string[] todos = ReadTodos(filename);

                //指定された番号が有効な数字かチェック
                if (1 <= index && index <= todos.Length)
                {
                    //index位置から配列の最後までの各要素を一つ前にシフト
                    Array.Copy(todos, index, todos, index - 1, todos.Length - index);

                    //todos配列からindexにある要素を削除
                    Array.Resize(ref todos, todos.Length - 1);

                    //書き込む関数に渡す
                    WriteTodos(filename, todos);
                }
                else
                {
                    //有効な数字を入力されていない場合はエラー表示する
                    Console.WriteLine("\n無効なインデックスです。\n");
                }
            }
            else
            {
                ///ファイルが見つからないエラー表示
                Console.WriteLine("\nファイルが見つかりません。\n");
            }
        }

        static void Main()
        {
            //ファイル名定義
            string filename = "todos.txt";

            //終了条件になるまで繰り返す
            while (true)
            {
                // メニューの表示
                Console.WriteLine("\n1. Todoリストを表示\n2. Todoを追加\n3. Todoを削除\n4. Todoを編集\n5. 終了");

                //メニューの入力を受け付ける
                Console.Write("選択してください: ");

                //入力された内容を格納
                string choice = Console.ReadLine();

                //Todoリスト表示を選択された処理
                if (choice == "1")
                {
                    //Todoリスト表示関数の呼び出し
                    DisplayTodos();
                }

                //Todoリストの登録を選択された処理
                else if (choice == "2")
                {
                    // Todoを追加する内容受付
                    Console.Write("追加するタスクを入力してください: ");

                    //Todoリストに追加したい内容を格納
                    string todo = Console.ReadLine();

                    //Todoリストに追加する関数に渡す
                    AddTodo(filename, todo);
                }

                //Todoリストから削除を選択された処理
                else if (choice == "3")
                {
                    //Todoリスト表示関数読み出し
                    DisplayTodos();

                    //index変数定義
                    int index;

                    while (true)
                    {
                        try
                        {
                            //Todoリストから削除したい数字受付
                            Console.Write("削除するタスクのインデックスを入力してください: ");

                            // ユーザーの入力を整数に変換しようと試みる
                            index = Convert.ToInt32(Console.ReadLine());

                            //数字を入力された場合は処理抜ける
                            break;

                            //文字列を入力された時
                        }
                        catch (FormatException)
                        {

                            //文字列が入力された場合はエラーを表示する
                            Console.WriteLine("入力が無効です。整数を入力してください。");
                        }
                    }

                    //該当のTodo内容を削除する関数に渡す
                    RemoveTodo(filename, index);
                }


                //Todoリストから編集を選択された処理
                else if (choice == "4")
                {
                    //Todoリスト表示関数読み出し
                    DisplayTodos();

                    //index変数定義
                    int index;

                    while (true)
                    {
                        try
                        {
                            //Todoリストから編集したい数字受付
                            Console.Write("編集するタスクのインデックスを入力してください: ");

                            // ユーザーの入力を整数に変換しようと試みる
                            index = Convert.ToInt32(Console.ReadLine());

                            //数字を入力された場合は処理抜ける
                            break;

                            //文字列を入力された時
                        }
                        catch (FormatException)
                        {
                            //文字列が入力された場合はエラーを表示する
                            Console.WriteLine("入力が無効です。整数を入力してください。");
                        }
                    }

                    //変更したい内容を入力受付
                    Console.Write("新しいタスクを入力してください: ");

                    //変更したい内容格納
                    string newTodo = Console.ReadLine();

                    //Todoリスト変更する関数の渡す
                    EditTodo(filename, index, newTodo);
                }

                //Todoリストの終了を選択された処理
                else if (choice == "5")
                {
                    // 終了すること表示する
                    Console.WriteLine("\n終了します...");

                    //処理を終了する
                    break;
                }
                else
                {
                    // 無効な選択
                    Console.WriteLine("\n無効な選択です。もう一度選択してください。");
                }
            }
        }
    }
}