<?php


namespace App\Console\Commands;
use App\Http\Business\V2\CodeBusiness;
use Illuminate\Console\Command;

class ResetWeeklyCommand extends Command
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'reset_weekly';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '周期性置零';

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
        (new CodeBusiness())->ResetWeekly();
        echo "执行完成！".PHP_EOL;
    }
}
