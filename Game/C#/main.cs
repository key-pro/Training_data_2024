using System;

namespace Maze
{
    internal class Program
    {
        internal class Maze
        {
            class MazeGame
            {
                //迷路定義
                static char[,] maze = {
                    { '#', '#', '#', '#', '#', '#', '#', '#', '#', '#' },
                    { '#', 'S', ' ', ' ', '#', ' ', '#', ' ', ' ', '#' },
                    { '#', ' ', '#', ' ', '#', ' ', '#', ' ', '#', '#' },
                    { '#', ' ', '#', ' ', ' ', ' ', ' ', ' ', ' ', '#' },
                    { '#', ' ', '#', ' ', '#', '#', '#', ' ', '#', '#' },
                    { '#', ' ', ' ', ' ', ' ', ' ', '#', ' ', 'G', '#' },
                    { '#', '#', '#', '#', '#', '#', '#', '#', '#', '#' },
                };

                //playerの横の位置定義
                static int playerX = 1;

                //playerの縦の位置定義
                static int playerY = 1;

                // ゴールに到達したかどうかのフラグ
                static bool reachedGoal = false;

                static void Main(string[] args)
                {
                    do
                    {
                        // ゲームを開始するメソッドを呼び出す
                        StartGame();

                        // ゲームが終了した後、再起動するかどうかを尋ねる
                        Console.WriteLine("ゲームを続行しますか？ (y/n)");

                        //ゲーム続行か判定
                    } while (Console.ReadLine().ToLower() == "y");
                }

                //ゲーム開始関数定義
                static void StartGame()
                {
                    // ゲーム開始時に迷路とプレイヤーの位置をリセットする
                    ResetGame();

                    // ゲームループ
                    do
                    {
                        // 迷路を描画
                        DrawMaze();

                        //コンソールからの入力を受け取る
                        ConsoleKeyInfo keyInfo = Console.ReadKey(true);

                        // ゴールに到達していない場合はプレイヤーを移動させる
                        if (!reachedGoal)
                        {
                            //playerの位置を移動
                            MovePlayer(keyInfo.Key);
                        }

                        // ゴールに到達したらループを終了
                    } while (!reachedGoal);

                    //ゴールしたメッセージ表示
                    Console.WriteLine("\nおめでとうございます！ ゴールに到達しました！");
                }

                //ゲームリセット関数定義
                static void ResetGame()
                {
                    // 迷路をリセット
                    maze = new char[,] {
                        { '#', '#', '#', '#', '#', '#', '#', '#', '#', '#' },
                        { '#', 'S', ' ', ' ', '#', ' ', '#', ' ', ' ', '#' },
                        { '#', ' ', '#', ' ', '#', ' ', '#', ' ', '#', '#' },
                        { '#', ' ', '#', ' ', ' ', ' ', ' ', ' ', ' ', '#' },
                        { '#', ' ', '#', ' ', '#', '#', '#', ' ', '#', '#' },
                        { '#', ' ', ' ', ' ', ' ', ' ', '#', ' ', 'G', '#' },
                        { '#', '#', '#', '#', '#', '#', '#', '#', '#', '#' },
                    };

                    // プレイヤーの位置をリセット

                    //playerの横の位置最定義
                    playerX = 1;

                    //playerの縦の位置最定義
                    playerY = 1;

                    // 到達フラグをリセット
                    reachedGoal = false;
                }

                //ゲーム描画関数定義
                static void DrawMaze()
                {
                    //コンソールをクリア
                    Console.Clear();
                    //コンソールに迷路を描画
                    for (int y = 0; y < maze.GetLength(0); y++)
                    {
                        for (int x = 0; x < maze.GetLength(1); x++)
                        {
                            //コンソールに迷路を描画
                            Console.Write(maze[y, x]);
                        }

                        //コンソールに改行を描画
                        Console.WriteLine();
                    }
                }

                //playerの移動関数定義
                static void MovePlayer(ConsoleKey key)
                {
                    //playerの新しい位置を計算
                    int newX = playerX;
                    int newY = playerY;

                    //playerの移動方向を判定
                    switch (key)
                    {
                        //playerの移動方向を判定(戻る)
                        case ConsoleKey.UpArrow:
                            newY--;
                            break;

                        //playerの移動方向を判定(進む)
                        case ConsoleKey.DownArrow:
                            newY++;
                            break;

                        //playerの移動方向を判定(戻る)
                        case ConsoleKey.LeftArrow:
                            newX--;
                            break;

                        //playerの移動方向を判定(進む)
                        case ConsoleKey.RightArrow:
                            newX++;
                            break;
                    }

                    //playerが迷路の外に出ないようにする
                    if (maze[newY, newX] != '#' && maze[newY, newX] != 'G')
                    {
                        maze[playerY, playerX] = ' ';
                        playerX = newX;
                        playerY = newY;
                        maze[playerY, playerX] = 'S';
                    }

                    //playerがゴールにたどり着いた
                    else if (maze[newY, newX] == 'G')
                    {
                        // ゴールに到達したらフラグを立てる
                        reachedGoal = true;
                    }
                }
            }
        }
    }
}
