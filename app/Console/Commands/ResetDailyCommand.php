<?php


namespace App\Console\Commands;
use App\Http\Business\V2\CodeBusiness;
use Illuminate\Console\Command;

class ResetDailyCommand extends Command
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'reset_daily';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '每日置零';

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
     * @return mixed
     */
    public function handle()
    {
        //这里编写需要执行的动作
        (new CodeBusiness())->Clean();
        echo "执行完成！".PHP_EOL;
    }
}
