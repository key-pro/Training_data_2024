using System;


namespace Calculator
{
    internal class Program
    {
        static void Main(string[] args)
        {
            bool endApp = false;
            // 終了条件を満たすまで繰り返し
            while (!endApp)
            {
                int left = 0;
                int right = 0;

                //電卓機能開始文字表示
                Console.WriteLine("Console Calculator in C#\r");
                Console.WriteLine("------------------------\n");

                // 数値の入力受付
                Console.Write("数字を入力して Enter キーを押してください:");

                //数字が入力されるまで繰り返し
                while (!int.TryParse(Console.ReadLine(), out left))
                {
                    //文字が入力された場合は再度入力を受付
                    Console.Write("無効な入力です。整数を入力してください: ");
                }

                //数字が入力されるまで繰り返し
                Console.Write("別の数字を入力して Enter キーを押してください:");
                while (!int.TryParse(Console.ReadLine(), out right))
                {
                    //文字が入力された場合は再度入力を受付
                    Console.Write("\n無効な入力です。整数を入力してください: ");
                }

                //リストの選択を受ける
                Console.WriteLine("以下のリストからオプションを選択してください:");
                Console.WriteLine("\ta - 足し算");
                Console.WriteLine("\ts - 引き算");
                Console.WriteLine("\tm - 掛け算");
                Console.WriteLine("\td - 割り算");
                Console.WriteLine("\te - 終了");
                while (true)
                {
                    Console.Write("\n選択: ");
                    if (Console.ReadLine() != "a" || Console.ReadLine() != "s" || Console.ReadLine() != "m" || Console.ReadLine() != "d" || Console.ReadLine() != "e")
                    {

                        //リスト外の入力された時のエラー表示
                        Console.WriteLine("\nエラー: 無効なオプション");

                        //リストの選択を受ける
                        Console.WriteLine("以下のリストからオプションを選択してください:");
                        Console.WriteLine("\ta - 足し算");
                        Console.WriteLine("\ts - 引き算");
                        Console.WriteLine("\tm - 掛け算");
                        Console.WriteLine("\td - 割り算");
                        Console.WriteLine("\te - 終了");
                    }
                    else
                    {
                        break;
                    }
                }

                //リストの選択によって処理を分ける
                switch (Console.ReadLine())
                {
                    //加算の選択された場合
                    case "a":

                        //加算の処理し結果を表示する
                        Console.WriteLine($"\n結果: {left} + {right} = " + (left + right));

                        //終了するか継続するか確認
                        Console.WriteLine("\nアプリを終了するには 'n' を入力して Enter キーを押してください。続行するには他のキーを入力して Enter キーを押してください...");
                        break;

                    //引き算の選択された場合
                    case "s":

                        //引き算の処理し結果を表示する
                        Console.WriteLine($"\n結果: {left} - {right} = " + (left - right));

                        //終了するか継続するか確認
                        Console.WriteLine("\nアプリを終了するには 'n' を入力して Enter キーを押してください。続行するには他のキーを入力して Enter キーを押してください...");
                        break;

                    //掛け算の選択された場合
                    case "m":

                        //掛け算の処理し結果を表示する
                        Console.WriteLine($"\n結果: {left} * {right} = " + (left * right));

                        //終了するか継続するか確認
                        Console.WriteLine("\nアプリを終了するには 'n' を入力して Enter キーを押してください。続行するには他のキーを入力して Enter キーを押してください...");
                        break;

                    //割り算の選択された場合
                    case "d":

                        // 0で割る場合のエラーチェックを追加
                        if (right != 0)
                        {
                            //割り算の処理し結果を表示する
                            Console.WriteLine($"\n結果: {left} / {right} = " + (left / right));

                            //終了するか継続するか確認
                            Console.WriteLine("\nアプリを終了するには 'n' を入力して Enter キーを押してください。続行するには他のキーを入力して Enter キーを押してください...");
                        }
                        else
                        {
                            //割り算した時に表示する
                            Console.WriteLine("\n" + 0);

                            //終了するか継続するか確認
                            Console.WriteLine("\nアプリを終了するには 'n' を入力して Enter キーを押してください。続行するには他のキーを入力して Enter キーを押してください...");
                        }
                        break;

                    //終了の選択された場合
                    case "e":

                        //終了のフラグをTrueに終了する
                        endApp = true;
                        break;

                }

                //終了選択のされた時
                if (Console.ReadLine() == "e")
                {
                    //終了表示
                    Console.WriteLine("\n終了します。");

                    //終了のフラグをTrueに終了する
                    endApp = true;
                }

                Console.WriteLine("\n");
            }
        }
    }
}
