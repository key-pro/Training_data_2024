using System;

namespace MazeGame
{
    class Program
    {
        //迷路定義配列定義
        static char[,] maze;

        // 迷路の幅
        static int width = 21;

        // 迷路の高さ
        static int height = 21;

        //playerの横の位置
        static int playerX;
        //playerの縦の位置
        static int playerY;

        static void Main(string[] args)
        {
            
            //カーソル非表示
            Console.CursorVisible = false;

            //無限ループ
            while (true)
            {
                // 迷路の生成
                maze = new char[height, width];

                //迷路の壁設定
                InitializeMaze();

                //迷路の生成
                GenerateMaze();

                // スタート地点とゴール地点を設定
                SetStartAndGoal();

                // プレイヤーの初期位置を設定

                //player横の位置初期値設定
                playerX = 1;

                //player縦の位置初期値設定
                playerY = 1;

                // ゲームループ
                while (true)
                {
                    //迷路とplayerを表示
                    DrawMaze();

                    // プレイヤーの移動
                    ConsoleKeyInfo keyInfo = Console.ReadKey(true);
                    MovePlayer(keyInfo.Key);

                    // ゴールに到達したかどうかをチェック
                    if (maze[playerY, playerX] == 'G')
                    {
                        //ゴールした時のメッセージ表示
                        Console.WriteLine("\nおめでとうございます！ ゴールに到達しました！!!!!!!!!!!!!!!!!!!!!!!!!!!");
                        break;
                    }
                }

                // 続行確認
                Console.WriteLine("続行しますか？ (y/n)");

                //終了条件判定
                if (Console.ReadLine().ToLower() != "y")
                {
                    //終了処理
                    break;
                }
                else
                {
                    //画面クリア
                    Console.Clear();
                }
            }
        }

        // 迷路の初期化
        static void InitializeMaze()
        {
            //迷路作成の下準備で全て壁に埋める
            for (int y = 0; y < height; y++)
            {
                for (int x = 0; x < width; x++)
                {
                    // 壁を設定
                    maze[y, x] = '#';
                }
            }
        }

        // 迷路生成
        static void GenerateMaze()
        {
            //ランダム生成しスタートとゴール位置決める
            Random random = new Random();

            // スタート地点から穴掘りを開始
            Dig(random, 1, 1);

            // ゴール地点からも穴掘りを行う
            Dig(random, height - 2, width - 2);
        }

        // 穴掘り法で迷路を生成する再帰関数
        static void Dig(Random random, int y, int x)
        {
            maze[y, x] = ' ';

            // ランダムに次の掘る方向を選択
            int[] directions = { 1, 2, 3, 4 };

            //
            ShuffleArray(random, directions);

            foreach (int dir in directions)
            {
                int newX = x;
                int newY = y;

                switch (dir)
                {
                    case 1: // 上
                        newY -= 2;
                        break;
                    case 2: // 下
                        newY += 2;
                        break;
                    case 3: // 左
                        newX -= 2;
                        break;
                    case 4: // 右
                        newX += 2;
                        break;
                }

                if (newY > 0 && newY < height && newX > 0 && newX < width && maze[newY, newX] == '#')
                {
                    maze[newY, newX] = ' ';
                    maze[(y + newY) / 2, (x + newX) / 2] = ' ';
                    Dig(random, newY, newX);
                }
            }
        }

        // 配列をランダムにシャッフルするメソッド
        static void ShuffleArray(Random random, int[] array)
        {
            int n = array.Length;
            while (n > 1)
            {
                int k = random.Next(n--);
                int temp = array[n];
                array[n] = array[k];
                array[k] = temp;
            }
        }

        // スタート地点とゴール地点を設定
        static void SetStartAndGoal()
        {
            maze[1, 1] = 'S'; // スタート地点
            maze[height - 2, width - 2] = 'G'; // ゴール地点
        }

        // 迷路の表示
        static void DrawMaze()
        {
            Console.SetCursorPosition(0, 0);

            //迷路の大きさ文繰り返し（高さ）
            for (int y = 0; y < height; y++)
            {
                //迷路の大きさ文繰り返し（幅）
                for (int x = 0; x < width; x++)
                {
                    if (y == playerY && x == playerX)
                    {
                        Console.Write('S'); // プレイヤーの位置を表示
                    }
                    else
                    {
                        //迷路表示
                        Console.Write(maze[y, x]);
                    }
                }

                //改行
                Console.WriteLine();
            }
        }

        // プレイヤーの移動
        static void MovePlayer(ConsoleKey key)
        {
            //playerの移動の先の横の位置
            int newX = playerX;

            //playerの移動の先の縦の位置
            int newY = playerY;

            switch (key)
            {
                //playerの上方向（上のキー）
                case ConsoleKey.UpArrow:
                    newY--;
                    break;

                //playerの下方向（下のキー）
                case ConsoleKey.DownArrow:
                    newY++;
                    break;

                //playerの左方向（左のキー）
                case ConsoleKey.LeftArrow:
                    newX--;
                    break;

                //playerの左方向（右のキー）
                case ConsoleKey.RightArrow:
                    newX++;
                    break;
            }

            //移動の先が壁かチェックし、壁では無いときに移動する
            if (maze[newY, newX] != '#')
            {
                //playerの移動の先の横の位置
                playerX = newX;

                //playerの移動の先の縦の位置
                playerY = newY;
            }
        }
    }
}
