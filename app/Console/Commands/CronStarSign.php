<?php

namespace App\Console\Commands;

use App\Star;
use App\StarFortune;
use Goutte\Client as GoutteClient;
use Illuminate\Console\Command;

class CronStarSign extends Command
{
    protected $totalScore = 5;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:StarSign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'spider about astro.click108.com.tw';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // echo config("12star.0");
        $this->getTest();
        echo 'done';
    }

    public function getTest()
    {
        $client = new GoutteClient();

        for ($starSign = 0; $starSign < 12; $starSign++) {
            $url = "https://astro.click108.com.tw/daily_{$starSign}.php?iAstro={$starSign}";

            try {
                $response = $client->request('GET', $url);
                $content = $response->filter('.TODAY_CONTENT')->html();
                if ($this->validContent($content, config("12star.{$starSign}"))) {
                    $star = new Star;
                    $star->type = $starSign;
                    $star->content = $content;
                    $star->save();
                    echo $this->htmlParsing($content, $star->id);
                } else {
                    \Log::error("get different sign from config. url: {$url} , content: {$content}");
                }
            } catch (Exception $e) {
                \Log::error($exception->getMessage());
            }
        }
    }

    // html解析
    private function htmlParsing(String $content, $cron_id)
    {
        preg_match_all("|<[^>]+>(.*)</[^>]+>|U", $content, $filter);

        $fortuneType = 0;
        for ($i = 1; $i < 9; $i = $i + 2) {
            $fortune = new StarFortune;
            $fortune->cron_star_sign_id = $cron_id;
            $fortune->type = $this->typeFortune($filter[1][$i], $fortuneType);
            $fortune->score = $this->countStars($filter[1][$i]);
            $fortune->description = $filter[1][$i + 1];
            $fortune->save();
            $fortuneType++;
        }
    }

    // 檢查取回內容是否為正確星座資料
    private function validContent(String $content, String $starSign)
    {
        return strpos($content, $starSign);
    }

    // 運勢類型
    private function typeFortune(String $content, Int $type): int
    {
        $fortunes = [
            0 => 'txt_green',
            1 => 'txt_pink',
            2 => 'txt_blue',
            3 => 'txt_orange',
        ];

        if (strpos($content, $fortunes[$type])) {
            return $type;
        } else {
            \Log::error('check fortunes!?');
        }
    }

    // 星星分數轉換
    private function countStars(String $text): int
    {
        $score = substr_count($text, '★');
        $other = substr_count($text, '☆');
        if ($score + $other != $this->totalScore) {
            \Log::error('check scores!?');
        }
        return $score;
    }
}
